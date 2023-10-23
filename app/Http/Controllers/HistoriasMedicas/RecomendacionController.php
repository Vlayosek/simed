<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\CollectionDataTable;
use App\Http\Controllers\HistoriasMedicas\RegistroController;
use App\Core\Entities\modules\HistoriasMedicas\Recomendacion;
use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;

use DB;
use Auth;

class RecomendacionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function datatableRecomendaciones($identificacion, $id)
    {
        $data = Recomendacion::select(
            'id',
            'codigo',
            'recomendacion',
            'codigo',

        )
            ->where('identificacion', $identificacion);
        if ($id != '0')
            $data = $data->whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $identificacion)->where('id', $id)->pluck('codigo'));
        else {
            $codigo_siguiente = (new RegistroController())->consultaCodigoSiguiente($id);
            $data = $data->whereIn('codigo', [$codigo_siguiente['codigo']]);
        }
        $data = $data
            ->where('eliminado', false)
            ->where('estado', 'ACT')
            ->orderBy('id', 'desc')->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                if (!(new RegistroController())->verificarEdicionDatos($row->codigo)) return 'Consulta';
                $btn = ' <table style="width:100%;border:0px">';
                $btn .= '<tr><td style="padding: 2px;border:0px"><a title="Editar" class="btn btn-block btn-outline-primary  btn-xs"  href="#modal-nueva_recomendacion" role="button" data-toggle="modal"  onclick="app_recomendaciones.editarRegistro(\'' . $row->id . '\')"><i class="fa fa-cog"></i>&nbsp;Editar</a></td>';
                $btn .= '<td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger  btn-xs"  onclick="app_recomendaciones.eliminar(\'' . $row->id . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td></tr>';
                $btn .= '</table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }
    public function guardar(request $request)
    {
        $data = Recomendacion::whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $request->identificacion)->where('codigo', $request->codigo)->pluck('codigo'))
            ->where('eliminado', false)
            ->get();

            try {
                DB::connection('pgsql')->beginTransaction();
                if ($request->id != 0) {

                    $cql = Recomendacion::find($request->id);
                    $cql->fecha_modifica = date("Y-m-d H:i:s");
                    $cql->usuario_modifica = Auth::user()->name;


                } else {
                    if (count($data) < 1) {
                        $cql = new Recomendacion();
                        $cql->fecha_inserta = date("Y-m-d H:i:s");
                        $cql->usuario_inserta = Auth::user()->name;
                        $cql->estado = 'ACT';
                    } else {
                        $array_response['status'] = '201';
                        $array_response['message'] = "Ya tiene el maximo de registros permitidos";
                        return response()->json($array_response, 200);
                    }

                }
                $cql->save();
                $cql->fill($request->all())->save();
                DB::connection('pgsql')->commit();
                $array_response['status'] = 200;
                $array_response['message'] = "Grabado exitosamente";
            } catch (\Exception $e) {
                DB::connection('pgsql_presidencia')->rollBack();
                $array_response['status'] = 404;
                $array_response['message'] = $e->getMessage();
            }


        return response()->json($array_response, 200);
    }
    public function editar(request $request)
    {
        $array_response['datos'] = Recomendacion::where('id', $request->id)->first();
        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }
    public function eliminar(request $request)
    {
        try {
            DB::connection('pgsql')->beginTransaction();
            $cql = Recomendacion::find($request->id);
            $cql->fecha_modifica = date("Y-m-d H:i:s");
            $cql->usuario_modifica = Auth::user()->name;
            $cql->eliminado = true;
            $cql->save();
            DB::connection('pgsql')->commit();
            $array_response['status'] = 200;
            $array_response['message'] = "Eliminado exitosamente";
        } catch (\Exception $e) {
            DB::connection('pgsql_presidencia')->rollBack();
            $array_response['status'] = 404;
            $array_response['message'] = $e->getMessage();
        }
        return response()->json($array_response, 200);
    }
}
