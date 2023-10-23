<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Http\Controllers\Controller;
use App\Core\Entities\modules\HistoriasMedicas\CodigoHistoria;
use Illuminate\Http\Request;
use App\Http\Controllers\Ajax\SelectController;
use Yajra\DataTables\CollectionDataTable;
use App\Core\Entities\Admin\parametro;
use App\Core\Entities\modules\HistoriasMedicas\AntecedenteMedico;
use App\Core\Entities\modules\HistoriasMedicas\AntecedentesTrabajo;
use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;
use App\Core\Entities\TalentoHumano\Distributivo\Area;
use DB;
use Auth;

class AntecedentesTrabajoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function eliminarAntecedentesTrabajo(Request $request)
    {
        try {
            DB::connection('pgsql')->beginTransaction();
            $hoy = date("Y-m-d H:i:s");

            $cql = AntecedentesTrabajo::where('id', $request->id)->first();
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

    public function editarAntecedentesTrabajo(Request $request)
    {
        try {
            $array_response['status'] = "200";
            $array_response['datos'] = AntecedentesTrabajo::find($request->id);
        } catch (\Exception $e) {
            $array_response['status'] = 404;
            $array_response['datos'] = null;
        }
        return response()->json($array_response, 200);
    }

    public function guardarAntecedentesTrabajo(Request $request)
    {
        $hoy = date("Y-m-d H:i:s");

        $data = AntecedentesTrabajo::whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $request->identificacion)->where('codigo', $request->codigo)->pluck('codigo'))
            ->where('eliminado', false)
            ->where('estado', 'ACT')
            ->get();


        try {
            DB::connection('pgsql')->beginTransaction();

            if ($request->id != 0) {
                $cql =  AntecedentesTrabajo::find($request->id);
                $cql->fecha_modifica = $hoy;
                $cql->usuario_modifica = Auth::user()->name;
            } else {
                if (count($data) < 4) {
                    $cql = new AntecedentesTrabajo();
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
    public function datatableAntecedentesTrabajo($identificacion, $id)
    {
        $data = AntecedentesTrabajo::select(
            'id',
            'empresa',
            'puesto_trabajo',
            'actividades_desempenadas',
            'tiempo_trabajo',
            'descripcion',
            'observaciones'
        );
        /* ->addSelect(
            [
                'riesgos' =>
                parametro::select(
                    DB::RAW("array_to_string(ARRAY_AGG(DISTINCT parametro__.descripcion),',\r\n')")
                )
                    ->leftjoin('sc_core.parametro as parametro', 'parametro.id', 'ubicaciones.parametro_id')
                    ->leftjoin('sc_core.parametro as parametro_', 'parametro_.id', 'parametro.parametro_id')
                    ->leftjoin('sc_core.parametro as parametro__', 'parametro__.id', 'parametro_.parametro_id')
                    ->where('parametro.parametro_id', '216')
            ]
        ) */
        if ($id != '0')
            $data = $data->whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $identificacion)->where('id', $id)->pluck('codigo'));
        else {
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
                $btn .= '<tr><td style="padding: 2px;border:0px"><a title="Editar" class="btn btn-block btn-outline-primary  btn-xs"  href="#modal-antecedentes-trabajo" role="button" class="btn"
                data-toggle="modal" onclick="app_antecedentes_trabajo.editarAntecedentesTrabajo(\'' . $row->id . '\')" ><i class="fa fa-cog"></i>&nbsp;Editar</a></td></tr>';
                $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger  btn-xs"  onclick="app_antecedentes_trabajo.eliminarAntecedentesTrabajo(\'' . $row->id . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td></tr>';
                $btn .= '</table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }

    public function getAreas()
    {
        $areas = Area::orderby('nombre', 'asc')->where('estado', 'ACT')->where('eliminado', false)->pluck('nombre', 'nombre');
        $array_response['areas'] = $areas;
        $array_response['status'] = 200;

        return response()->json($array_response, 200);
    }

    public function consultaCombosAntecedentesTrabajo(Request $request)
    {
        $datos = new SelectController();
        $array_response['antecedentes_trabajo'] = $datos->getParametro('RIESGOS_LABORALES', 'http', 2);

        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }

    public function consultaCombosAntecedentesTrabajo_(Request $request)
    {
        $cqlDatosAntecedentesTrabajo = parametro::select('id')
            ->where('descripcion', 'RIESGOS_LABORALES')
            ->first();
        $cqlDatosDetallesAntecedentesTrabajo = parametro::select('id', 'descripcion')
            ->where('parametro_id', (is_null($cqlDatosAntecedentesTrabajo) ? 0 : $cqlDatosAntecedentesTrabajo->id))
            ->where('estado', 'A')
            ->with(['hijos'  => function ($q) {
                $q->select('id',  'descripcion', 'parametro_id')->where('estado', 'A')->orderby('id', 'asc');
            }])
            ->orderby('id',  'asc')
            ->get()
            ->toArray();
        $array_response['datos'] = $cqlDatosDetallesAntecedentesTrabajo;
        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }
}
