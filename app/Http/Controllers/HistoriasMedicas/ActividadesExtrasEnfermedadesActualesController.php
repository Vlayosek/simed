<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\CollectionDataTable;
use App\Http\Controllers\HistoriasMedicas\RegistroController;
use App\Core\Entities\modules\HistoriasMedicas\ActividadExtraEnfermedadActual;
use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;

use DB;
use Auth;

class ActividadesExtrasEnfermedadesActualesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function eliminarActividadExtraEnfermedadActual(Request $request)
    {
        try {
            DB::connection('pgsql')->beginTransaction();
            $hoy = date("Y-m-d H:i:s");

            $cql = ActividadExtraEnfermedadActual::where('id', $request->id)->first();
            $cql->fecha_modifica = $hoy;
            $cql->usuario_modifica = Auth::user()->name;
            $cql->eliminado = true;
            $cql->save();
            DB::connection('pgsql')->commit();
            $array_response['status'] = "200";
            $array_response['datos'] = "Eliminado exitosamente";
        } catch (\Exception $e) {
            DB::connection('pgsql_presidencia')->rollBack();
            $array_response['status'] = 404;
            $array_response['datos'] = $e->getMessage();
        }
        return response()->json($array_response, 200);
    }
    public function guardarActividadExtraEnfermedadActual(Request $request)
    {
        try {
            DB::connection('pgsql')->beginTransaction();
            $hoy = date("Y-m-d H:i:s");
            if ($request->id != 0) {
                $cql =  ActividadExtraEnfermedadActual::find($request->id);
                $cql->fecha_modifica = $hoy;
                $cql->usuario_modifica = Auth::user()->name;
            } else {
                $cql = new ActividadExtraEnfermedadActual();
                $cql->fecha_inserta = $hoy;
                $cql->usuario_inserta = Auth::user()->name;
            }

            $cql->codigo = $request->codigo;
            $cql->identificacion = $request->identificacion;
            $cql->descripcion = $request->descripcion;
            $cql->tipo = $request->tipo;
            $cql->eliminado = false;
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
    public function cargarDatatableEnfermedadActual($identificacion, $id)
    {
        $data = ActividadExtraEnfermedadActual::select(
            'id',
            'descripcion',
            'codigo',
        )
            ->where('tipo', 'ENFERMEDAD')
            ->where('identificacion', $identificacion);
        if ($id != '0')
            $data = $data->whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $identificacion)->where('id', $id)->pluck('codigo'));
        else {
            $codigo_siguiente = (new RegistroController())->consultaCodigoSiguiente($id);
            $data = $data->whereIn('codigo', [$codigo_siguiente['codigo']]);
        }
        $data = $data->where('eliminado', false)
            ->orderBy('id', 'desc')->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                if (!(new RegistroController())->verificarEdicionDatos($row->codigo)) return 'Consulta';
                $tipo = 'ENFERMEDAD';
                $btn = ' <table style="width:100%;border:0px">';
                $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger  btn-xs"  onclick="app_actividades_extras_enfermedades_actual.eliminarActividadExtraEnfermedadActual(\'' . $row->id . '\',\'' . $tipo . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td></tr>';
                $btn .= '</table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }
    public function cargarDatatableExtraLaborales($identificacion, $id)
    {
        $data = ActividadExtraEnfermedadActual::select(
            'id',
            'descripcion',
            'codigo',
        )
            ->where('tipo', 'ACTIVIDAD')
            ->where('identificacion', $identificacion);
        if ($id != '0')
            $data = $data->whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $identificacion)->where('id', $id)->pluck('codigo'));
        else {
            $codigo_siguiente = (new RegistroController())->consultaCodigoSiguiente($id);
            $data = $data->whereIn('codigo', [$codigo_siguiente['codigo']]);
        }
        $data = $data->where('eliminado', false)
            ->orderBy('id', 'desc')->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                if (!(new RegistroController())->verificarEdicionDatos($row->codigo)) return 'Consulta';
                $tipo = 'ACTIVIDAD';

                $btn = ' <table style="width:100%;border:0px">';
                $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger  btn-xs"  onclick="app_actividades_extras_enfermedades_actual.eliminarActividadExtraEnfermedadActual(\'' . $row->id . '\',\'' . $tipo . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td></tr>';
                $btn .= '</table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }
}
