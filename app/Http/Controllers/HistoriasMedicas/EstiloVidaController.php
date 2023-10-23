<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Ajax\SelectController;
use Yajra\DataTables\CollectionDataTable;
use App\Core\Entities\modules\HistoriasMedicas\EstiloVida;
use DB;
use Auth;
use App\Http\Controllers\HistoriasMedicas\RegistroController;
use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;

class EstiloVidaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function datatableEstiloVida($identificacion, $id)
    {
        $data = EstiloVida::select(
            'id',
            'descripcion',
            'valor',
            'tipo_estilo_vida',
            'identificacion',
            'tiempo_cantidad',
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
            ->whereNotIn('eliminado', [true])
            ->orderBy('id', 'desc')->get();

        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                if (!(new RegistroController())->verificarEdicionDatos($row->codigo)) return 'Consulta';

                $btn = ' <table style="width:100%;border:0px">';
                $btn .= '<tr><td style="padding: 2px;border:0px"><a title="Editar" class="btn btn-block btn-outline-primary  btn-xs"  href="#modal-estilo-vida" role="button" class="btn"
                data-toggle="modal" data-backdrop="static" data-keyboard="false" onclick="app_estilo_vida.editarEstiloVida(\'' . $row->id . '\')" ><i class="fa fa-cog"></i>&nbsp;Editar</a></td></tr>';
                $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger  btn-xs"  onclick="app_estilo_vida.eliminarEstiloVida(\'' . $row->id . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td></tr>';
                $btn .= '</table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }

    public function consultaComboEstiloVida(Request $request)
    {
        $datos = new SelectController();
        $array_response['estilo_vida'] = $datos->getParametro('ESTILO_VIDA', 'http', 4);
        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }

    public function guardarEstiloVida(Request $request)
    {
        $maxRow = 0;
        $descripcionEstiloVida = $request->descripcion;

        switch ($descripcionEstiloVida) {
            case 'ACTIVIDAD FÍSICA':
                $maxRow = 1;
                break;

            default:
                $maxRow = 3;
                break;
        }

        $data = EstiloVida::whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $request->identificacion)->where('codigo', $request->codigo)->pluck('codigo'))
            ->where('eliminado', false)
            ->where('descripcion', $descripcionEstiloVida)
            ->get();

            try {
                DB::connection('pgsql')->beginTransaction();

                if ($request->id != 0) {
                    $cql =  EstiloVida::find($request->id);
                    $cql->fecha_modifica = date("Y-m-d H:i:s");
                    $cql->usuario_modifica = Auth::user()->name;
                } else {
                    if (count($data) < $maxRow) {
                        $cql = new EstiloVida();
                        $cql->fecha_inserta = date("Y-m-d H:i:s");
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
                $array_response['status'] = 200;
                $array_response['message'] = "Grabado exitosamente";
            } catch (\Exception $e) {
                DB::connection('pgsql_presidencia')->rollBack();
                $array_response['status'] = 404;
                $array_response['message'] = $e->getMessage();
            }

        return response()->json($array_response, 200);
    }

    public function editarEstiloVida(Request $request)
    {
        try {
            $array_response['status'] = "200";
            $array_response['datos'] = EstiloVida::where('id', $request->id)->first();
        } catch (\Exception $e) {
            $array_response['status'] = 404;
            $array_response['datos'] = null;
        }
        return response()->json($array_response, 200);
    }

    public function eliminarEstiloVida(Request $request)
    {
        try {
            DB::connection('pgsql_presidencia')->beginTransaction();
            EstiloVida::where('id', $request->id)->where('eliminado', false)->update(['eliminado' => true, 'usuario_modifica' => Auth::user()->name, 'fecha_modifica' => date("Y-m-d H:i:s"), 'estado' => 'INA']);

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
}
