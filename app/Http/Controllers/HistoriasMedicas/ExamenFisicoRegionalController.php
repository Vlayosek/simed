<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Core\Entities\Admin\parametro;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Ajax\SelectController;
use Yajra\DataTables\CollectionDataTable;
use App\Core\Entities\modules\HistoriasMedicas\ExamenFisicoRegional;
use DB;
use Auth;
use App\Http\Controllers\HistoriasMedicas\RegistroController;
use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;

class ExamenFisicoRegionalController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function datatableExamenFisicoRegional($identificacion,$id)
    {
        $data = ExamenFisicoRegional::select(
            'id',
            'detalles',
            'descripcion',
            'codigo',

        )
            ->where('identificacion', $identificacion);
            if ($id != '0')
            $data = $data->whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $identificacion)->where('id', $id)->pluck('codigo'));
        else {
            $codigo_siguiente = (new RegistroController())->consultaCodigoSiguiente($id);
            $data = $data->whereIn('codigo', [$codigo_siguiente['codigo']]);
        }              $data=$data
            ->whereNotIn('eliminado', [true])
            ->orderBy('id', 'desc')->get();

        return (new CollectionDataTable($data))
            ->addIndexColumn()
             ->addColumn('', function ($row) { if(!(new RegistroController())->verificarEdicionDatos($row->codigo)) return 'Consulta';

                $btn = ' <table style="width:100%;border:0px">';
                $btn .= '<tr><td style="padding: 2px;border:0px"><a title="Editar" class="btn btn-block btn-outline-primary  btn-xs"  href="#modal-examen-fisico-regional" role="button" class="btn"
                data-toggle="modal" onclick="app_examen_fisico_regional.editarExamenFisicoRegional(\'' . $row->id . '\')" ><i class="fa fa-cog"></i>&nbsp;Editar</a></td></tr>';
                $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger  btn-xs"  onclick="app_examen_fisico_regional.eliminarExamenFisicoRegional(\'' . $row->id . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td></tr>';
                $btn .= '</table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }

    public function guardarExamenFisicoRegional(Request $request)
    {
        $hoy = date("Y-m-d H:i:s");


        $data = ExamenFisicoRegional::whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $request->identificacion)->where('codigo', $request->codigo)->pluck('codigo'))
            ->where('eliminado',false)
            ->get();


            try {
                DB::connection('pgsql')->beginTransaction();
                if ($request->id != 0) {
                    $cql =  ExamenFisicoRegional::find($request->id);
                    $cql->fecha_modifica = $hoy;
                    $cql->usuario_modifica = Auth::user()->name;
                } else {

                    if(count($data) < 1){
                        $cql = new ExamenFisicoRegional();
                        $cql->fecha_inserta = $hoy;
                        $cql->usuario_inserta = Auth::user()->name;
                    } else {
                        $array_response['status'] = "201";
                        $array_response['message'] = "Ya tiene el maximo de registros permitidos";
                        return response()->json($array_response, 200);
                    }
                }

                $cql->save();
                $cql->fill($request->all())->save();
                DB::connection('pgsql')->commit();
                $array_response['status'] = "200";
                $array_response['message'] = "Grabado exitosamente";
            } catch (\Exception $e) {
                DB::connection('pgsql_presidencia')->rollBack();
                $array_response['status'] = 404;
                $array_response['message'] = $e->getMessage();
            }

        return response()->json($array_response, 200);
    }

    public function editarExamenFisicoRegional(Request $request)
    {
        try {
            $array_response['status'] = "200";
            $array_response['datos'] = ExamenFisicoRegional::find($request->id);
        } catch (\Exception $e) {
            $array_response['status'] = 404;
            $array_response['datos'] = null;
        }
        return response()->json($array_response, 200);
    }

    public function eliminarExamenFisicoRegional(Request $request)
    {
        try {
            DB::connection('pgsql_presidencia')->beginTransaction();
            ExamenFisicoRegional::where('id', $request->id)->where('eliminado', false)->update(['eliminado' => true, 'usuario_modifica' => Auth::user()->name, 'fecha_modifica' => date("Y-m-d H:i:s"), 'estado' => 'INA']);

            DB::connection('pgsql_presidencia')->commit();
            $array_response['status'] = "200";
            $array_response['message'] = "Eliminado exitosamente";
        } catch (\Exception $e) {
            DB::connection('pgsql_presidencia')->rollBack();
            $array_response['status'] = 404;
            $array_response['message'] = $e->getMessage();
        }
        return response()->json($array_response, 200);
    }

    public function consultaComboExamenFisicoRegional(Request $request)
    {
        $cqlDatosExamenFisicoRegional = parametro::select('id')
            ->where('descripcion', 'REGIONES_FISICAS')
            ->first();
        $cqlDatosDetallesExamenFisicoRegional = parametro::select('id', 'descripcion')
            ->where('parametro_id', (is_null($cqlDatosExamenFisicoRegional) ? 0 : $cqlDatosExamenFisicoRegional->id))
            ->where('estado', 'A')
            ->with(['hijos'  => function ($q) {
                $q->select('id',  'descripcion', 'parametro_id')->where('estado', 'A')->orderby('id', 'asc');
            }])
            ->orderby('id',  'asc')
            ->get()
            ->toArray();
        $array_response['datos'] = $cqlDatosDetallesExamenFisicoRegional;
        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }
}
