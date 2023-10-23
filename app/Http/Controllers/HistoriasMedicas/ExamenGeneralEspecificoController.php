<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Ajax\SelectController;
use Yajra\DataTables\CollectionDataTable;
use App\Core\Entities\modules\HistoriasMedicas\ExamenGeneralEspecifico;
use DB;
use Auth;
use App\Http\Controllers\HistoriasMedicas\RegistroController;
use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;

class ExamenGeneralEspecificoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function datatableExamenGeneralEspecifico($identificacion, $id)
    {
        $data = ExamenGeneralEspecifico::select(
            'id',
            'descripcion',
            'fecha',
            'resultados',
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
                $btn .= '<tr><td style="padding: 2px;border:0px"><a title="Editar" class="btn btn-block btn-outline-primary  btn-xs"  href="#modal-examen-general-especifico" role="button" class="btn"
                data-toggle="modal" onclick="app_examen_general_especifico.editarExamenGeneralEspecifico(\'' . $row->id . '\')" ><i class="fa fa-cog"></i>&nbsp;Editar</a></td></tr>';
                $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger  btn-xs"  onclick="app_examen_general_especifico.eliminarExamenGeneralEspecifico(\'' . $row->id . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td></tr>';
                $btn .= '</table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }

    public function consultaComboExamenGeneralEspecifico(Request $request)
    {
        $datos = new SelectController();
        $array_response['examen_general_especifico'] = $datos->getParametro('EXAMEN_GENERAL_ESPECIFICO', 'http', 4);
        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }

    public function guardarExamenGeneralEspecifico(Request $request)
    {   
        $extension=null;
        if (!is_null($request->imagen) )
            $extension = explode('/', mime_content_type($request->imagen))[1];
        $tipo_evaluacion = $request->tipo_evaluacion;
        $hoy = date("Y-m-d H:i:s");
        $cantReg = 0;
        switch ($tipo_evaluacion) {
            case 'INGRESO':
                $cantReg = 4;
                break;
            case 'RETIRO':
                $cantReg = 2;
                break;
            case 'REINGRESO':
                $cantReg = 3;
                break;
            default:
                $cantReg = 4;
                break;
        }

        $data = ExamenGeneralEspecifico::whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $request->identificacion)->where('codigo', $request->codigo)->pluck('codigo'))
            ->where('eliminado', false)
            ->get();

        try {

            if ( !is_null($request->imagen) || $request->imagen!=false) {
                $validaPdf = substr($request->imagen, 0, 20);
                $validaImagen = substr($request->imagen, 0, 10);

                if ($validaPdf != "data:application/pdf" && $validaImagen != "data:image") {
                    $array_response['status'] = 300;
                    $array_response['datos'] = 'Error en el tipo de imagen ingresado';
                    return response()->json($array_response, 200);
                }
            }

            DB::connection('pgsql')->beginTransaction();

            if ($request->id != 0) {
                $cql =  ExamenGeneralEspecifico::find($request->id);
                $cql->fecha_modifica = $hoy;
                $cql->usuario_modifica = Auth::user()->name;
            } else {

                if (count($data) < $cantReg) {
                    $cql = new ExamenGeneralEspecifico();
                    $cql->fecha_inserta = $hoy;
                    $cql->usuario_inserta = Auth::user()->name;
                } else {

                    $array_response['status'] = '201';
                    $array_response['message'] = "Ya tiene el maximo de registros permitidos";
                    return response()->json($array_response, 200);
                }
            }
            $cql->imagen = $request->imagen;
            $cql->extension = $extension;
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

    public function editarExamenGeneralEspecifico(Request $request)
    {
        try {
            $array_response['status'] = "200";
            $array_response['datos'] = ExamenGeneralEspecifico::find($request->id);
        } catch (\Exception $e) {
            $array_response['status'] = 404;
            $array_response['datos'] = null;
        }
        return response()->json($array_response, 200);
    }

    public function eliminarExamenGeneralEspecifico(Request $request)
    {
        try {
            DB::connection('pgsql_presidencia')->beginTransaction();
            ExamenGeneralEspecifico::where('id', $request->id)->where('eliminado', false)->update(['eliminado' => true, 'usuario_modifica' => Auth::user()->name, 'fecha_modifica' => date("Y-m-d H:i:s"), 'estado' => 'INA']);

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
