<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\CollectionDataTable;
use App\Http\Controllers\HistoriasMedicas\RegistroController;
use App\Core\Entities\modules\HistoriasMedicas\AptitudMedica;
use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;
use App\Core\Entities\modules\HistoriasMedicas\EvaluacionMedicaRetiro;

use DB;
use Auth;

class EvaluacionMedicaRetiroController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function datatableEvaluacionMedicaRetiro($identificacion, $id)
    {
        $data = EvaluacionMedicaRetiro::select(
            'id',
            'evaluacion_realizada',
            'observaciones',
            'identificacion',
            'codigo',
            'condicion_diagnostico',
            'salud_relacionada'
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
                $btn .= '<tr><td style="padding: 2px;border:0px"><a title="Editar" class="btn btn-block btn-outline-primary  btn-xs"  href="#modal-evaluacion-medica-retiro" role="button" data-toggle="modal"  onclick="app_evaluacion_medica_retiro.editarRegistro(\'' . $row->id . '\')"><i class="fa fa-cog"></i>&nbsp;Editar</a></td></tr>';
                $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger  btn-xs"  onclick="app_evaluacion_medica_retiro.eliminar(\'' . $row->id . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td></tr>';
                $btn .= '</table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }
    public function guardarEvaluacionMedicaRetiro(request $request)
    {
        $data = EvaluacionMedicaRetiro::whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $request->identificacion)->where('codigo', $request->codigo)->pluck('codigo'))
            ->where('eliminado', false)
            ->get();

        try {
            DB::connection('pgsql')->beginTransaction();
            if ($request->id != 0) {
                $cql = EvaluacionMedicaRetiro::find($request->id);
                $cql->fecha_modifica = date("Y-m-d H:i:s");
                $cql->usuario_modifica = Auth::user()->name;
            } else {
                if (count($data) < 1) {
                    $cql = new EvaluacionMedicaRetiro();
                    $cql->fecha_inserta = date("Y-m-d H:i:s");
                    $cql->usuario_inserta = Auth::user()->name;
                    $cql->estado = 'ACT';
                } else {
                    $array_response['status'] = "201";
                    $array_response['message'] = "Ya tiene el maximo de registros permitidos";
                    return response()->json($array_response, 200);
                }
            }

            /* switch ($request->condicion_diagnostico) {
                case 'presuntiva':
                    $cql->condicion_diagnostico = 'pres';
                    break;
                case 'definitiva':
                    $cql->condicion_diagnostico = 'def';
                    break;
                default:
                    $cql->condicion_diagnostico = 'na';
                    break;
            }

            switch ($request->salud_relacionada) {
                case 'true':
                    $cql->condicion_diagnostico = 't';
                    break;
                case 'false':
                    $cql->condicion_diagnostico = 'f';
                    break;
                default:
                    $cql->condicion_diagnostico = 'na';
                    break;
            } */

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
    public function editarEvaluacionMedicaRetiro(request $request)
    {
        $array_response['datos'] = EvaluacionMedicaRetiro::where('id', $request->id)->first();
        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }
    public function eliminarEvaluacionMedicaRetiro(request $request)
    {
        try {
            DB::connection('pgsql')->beginTransaction();
            $cql = EvaluacionMedicaRetiro::find($request->id);
            $cql->fecha_modifica = date("Y-m-d H:i:s");
            $cql->usuario_modifica = Auth::user()->name;
            $cql->estado = 'INA';
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
