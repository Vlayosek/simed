<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Http\Controllers\Controller;
use App\Core\Entities\modules\HistoriasMedicas\CodigoHistoria;
use Illuminate\Http\Request;
use App\Http\Controllers\Ajax\SelectController;
use Yajra\DataTables\CollectionDataTable;
use App\Http\Controllers\HistoriasMedicas\RegistroController;
use App\Core\Entities\modules\HistoriasMedicas\FactorRiesgoLaboral;
use App\Core\Entities\Admin\parametro;
use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;

use DB;
use Auth;

class FactoresRiesgosPuestosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function editarFactorRiesgoLaboral(Request $request)
    {
        try {
            DB::connection('pgsql')->beginTransaction();
            $array_response['status'] = "200";
            $array_response['datos'] = FactorRiesgoLaboral::find($request->id);
        } catch (\Exception $e) {
            $array_response['status'] = 404;
            $array_response['datos'] = null;
        }
        return response()->json($array_response, 200);
    }
    public function eliminarFactorRiesgoLaboral(Request $request)
    {
        try {
            DB::connection('pgsql')->beginTransaction();
            $hoy = date("Y-m-d H:i:s");

            $cql = FactorRiesgoLaboral::where('id', $request->id)->first();
            $cql->fecha_modifica = $hoy;
            $cql->usuario_modifica = Auth::user()->name;
            $cql->eliminado = true;
            $cql->save();
            DB::connection('pgsql')->commit();
            $array_response['status'] = "200";
            $array_response['datos'] = "Grabado exitosamente";
        } catch (\Exception $e) {
            DB::connection('pgsql_presidencia')->rollBack();
            $array_response['status'] = 404;
            $array_response['datos'] = $e->getMessage();
        }
        return response()->json($array_response, 200);
    }
    public function guardarFactoresRiesgosos(Request $request)
    {
        $data = FactorRiesgoLaboral::whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $request->identificacion)->where('codigo', $request->codigo)->pluck('codigo'))
            ->where('eliminado', false)
            ->where('estado', 'ACT')
            ->get();
        try {
            DB::connection('pgsql')->beginTransaction();
            $hoy = date("Y-m-d H:i:s");
            if ($request->id != 0) {
                $cql =  FactorRiesgoLaboral::find($request->id);
                $cql->fecha_modifica = $hoy;
                $cql->usuario_modifica = Auth::user()->name;
            } else {
                if (count($data) < 4) {
                    $cql = new FactorRiesgoLaboral();
                    $cql->fecha_inserta = $hoy;
                    $cql->usuario_inserta = Auth::user()->name;
                } else {
                    $array_response['status'] = "201";
                    $array_response['message'] = "Ya tiene el maximo de registros permitidos";
                    return response()->json($array_response, 200);
                }
            }

            $cql->identificacion = $request->identificacion;
            $cql->descripcion = $request->descripcion;
            $cql->medidas_preventivas = $request->medidas_preventivas;
            $cql->puesto_trabajo = $request->puesto_trabajo;
            $cql->actividades = $request->actividades;
            $cql->detalles = $request->detalles;
            $cql->codigo = $request->codigo;
            $cql->eliminado = false;
            $cql->save();
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
    public function datatableFactoresRiesgosos($identificacion, $id)
    {
        $data = FactorRiesgoLaboral::select(
            'id',
            'descripcion',
            'puesto_trabajo',
            'actividades',
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
            ->orderBy('id', 'desc')->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                if (!(new RegistroController())->verificarEdicionDatos($row->codigo)) return 'Consulta';
                $btn = ' <table style="width:100%;border:0px">';
                $btn .= '<tr><td style="padding: 2px;border:0px"><a title="Editar" class="btn btn-block btn-outline-primary  btn-xs"  href="#modal-container-factores_riesgos_puesto" role="button" class="btn"
                    data-toggle="modal"  onclick="app_factores_riesgosos.editarFactorRiesgoLaboral(\'' . $row->id . '\')"><i class="fa fa-cog"></i>&nbsp;Editar</a></td></tr>';
                $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger  btn-xs"  onclick="app_factores_riesgosos.eliminarFactorRiesgoLaboral(\'' . $row->id . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td></tr>';
                $btn .= '</table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }
    public function consultaCombosRegistroFactoresRiegososPuestos(Request $request)
    {
        $datos = new SelectController();
        $cqlDatosFactoresRiesgosPuestos = parametro::select('id')->where('descripcion', 'FACTORES_LABORALES')->first();
        $cqlDatosDetallesFactoresRiesgosPuestos = parametro::select('id', 'descripcion')->where('parametro_id', (is_null($cqlDatosFactoresRiesgosPuestos) ? 0 : $cqlDatosFactoresRiesgosPuestos->id))->where('estado', 'A')->with(['hijos' => function ($q) {
            $q->select('id', 'descripcion', 'parametro_id')->where('estado', 'A')->orderby('id', 'asc');
        }])->orderby('id', 'asc')->get()->toArray();

        $array_response['datos'] = $cqlDatosDetallesFactoresRiesgosPuestos;
        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }
}
