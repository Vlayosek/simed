<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Core\Entities\modules\HistoriasMedicas\AntecedentesAccidentesTrabajo;
use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\CollectionDataTable;

class AntecedentesAccidentesTrabajoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function eliminarAntecedenteAccidentesTrabajo(Request $request)
    {
        try {
            DB::connection('pgsql')->beginTransaction();
            $hoy = date("Y-m-d H:i:s");

            $cql = AntecedentesAccidentesTrabajo::where('id', $request->id)->first();
            $cql->fecha_modifica = $hoy;
            $cql->usuario_modifica = Auth::user()->name;
            $cql->estado = 'INA';
            $cql->eliminado = true;
            $cql->save();
            DB::connection('pgsql')->commit();
            $array_response['status'] = "200";
            $array_response['message'] = "Eliminado exitosamente";
        } catch (\Exception $e) {
            DB::connection('pgsql_presidencia')->rollBack();
            $array_response['status'] = 404;
            $array_response['message'] = $e->getMessage();
        }
        return response()->json($array_response, 200);
    }

    public function editarAntecedenteAccidentesTrabajo(Request $request)
    {
        try {
            $array_response['status'] = "200";
            $array_response['datos'] = AntecedentesAccidentesTrabajo::find($request->id);
        } catch (\Exception $e) {
            $array_response['status'] = 404;
            $array_response['datos'] = null;
        }
        return response()->json($array_response, 200);
    }

    public function guardarAntecedenteAccidentesTrabajo(Request $request)
    {
        $hoy = date("Y-m-d H:i:s");

        $data = AntecedentesAccidentesTrabajo::whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $request->identificacion)->where('codigo', $request->codigo)->pluck('codigo'))
            ->where('eliminado', false)
            ->where('estado', 'ACT')
            ->get();

        try {
            DB::connection('pgsql')->beginTransaction();

            if ($request->id != 0) {
                $cql = AntecedentesAccidentesTrabajo::find($request->id);
                $cql->fecha_modifica = $hoy;
                $cql->usuario_modifica = Auth::user()->name;
            } else {
                if (count($data) < 1) {
                    $cql = new AntecedentesAccidentesTrabajo();
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
    public function datatableAntecedentesAccidentesTrabajo($identificacion, $id)
    {
        $data = AntecedentesAccidentesTrabajo::select(
            'id',
            'calificado_accidente',
            'especificar_accidente',
            'fecha_accidente',
            'observaciones_accidente',
            'identificacion',
        );
        if ($id != '0') {
            $data = $data->whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $identificacion)->where('id', $id)->pluck('codigo'));
        } else {
            $codigo_siguiente = (new RegistroController())->consultaCodigoSiguiente($id);
            $data = $data->whereIn('codigo', [$codigo_siguiente['codigo']]);
        }
        $data = $data->where('identificacion', $identificacion)
            ->whereNotIn('eliminado', [true])
            ->orderBy('id', 'desc')->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {

                $btn = ' <table style="width:100%;border:0px">';
                $btn .= '<tr><td style="padding: 2px;border:0px"><a title="Editar" class="btn btn-block btn-outline-primary  btn-xs" href="#modal-accidentes-trabajo" role="button" class="btn"
                data-toggle="modal" onclick="app_antecedentes_trabajo.editarAntecedentesAccidentesTrabajo(\'' . $row->id . '\')" ><i class="fa fa-cog"></i>&nbsp;Editar</a></td></tr>';
                $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger  btn-xs"  onclick="app_antecedentes_trabajo.eliminarAntecedenteAccidentesTrabajo(\'' . $row->id . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td></tr>';
                $btn .= '</table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }
}
