<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Core\Entities\modules\HistoriasMedicas\AntecedentesEnfermedadesProfesionales;
use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;
use App\Http\Controllers\Ajax\SelectController;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\CollectionDataTable;

class AntecedentesEnfermedadesProfesionalesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function eliminarAntecedentesEnfermedadesProfesionales(Request $request)
    {
        try {
            DB::connection('pgsql')->beginTransaction();
            $hoy = date("Y-m-d H:i:s");

            $cql = AntecedentesEnfermedadesProfesionales::where('id', $request->id)->first();
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

    public function editarAntecedentesEnfermedadesProfesionales(Request $request)
    {
        try {
            $array_response['status'] = 200;
            $array_response['datos'] = AntecedentesEnfermedadesProfesionales::find($request->id);
        } catch (\Exception $e) {
            $array_response['status'] = 404;
            $array_response['datos'] = null;
        }
        return response()->json($array_response, 200);
    }

    public function guardarAntecedentesEnfermedadesProfesionales(Request $request)
    {
        $hoy = date("Y-m-d H:i:s");

        $data = AntecedentesEnfermedadesProfesionales::whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $request->identificacion)->where('codigo', $request->codigo)->pluck('codigo'))
            ->where('eliminado', false)
            ->where('estado', 'ACT')
            ->get();

        try {
            DB::connection('pgsql')->beginTransaction();

            if ($request->id != 0) {
                $cql = AntecedentesEnfermedadesProfesionales::find($request->id);
                $cql->fecha_modifica = $hoy;
                $cql->usuario_modifica = Auth::user()->name;
            } else {
                if (count($data) < 1) {
                    $cql = new AntecedentesEnfermedadesProfesionales();
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
    public function datatableAntecedentesEnfermedadesProfesionales($identificacion, $id)
    {
        $data = AntecedentesEnfermedadesProfesionales::select(
            'id',
            'calificado_enfermedad',
            'especificar_enfermedad',
            'fecha_enfermedad',
            'observaciones_enfermedad',
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
                $btn .= '<tr><td style="padding: 2px;border:0px"><a title="Editar" class="btn btn-block btn-outline-primary  btn-xs" href="#modal-enfermedades-profesionales" role="button" class="btn"
                data-toggle="modal" onclick="app_antecedentes_trabajo.editarAntecedentesEnfermedadesProfesionales(\'' . $row->id . '\')" ><i class="fa fa-cog"></i>&nbsp;Editar</a></td></tr>';
                $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger  btn-xs"  onclick="app_antecedentes_trabajo.eliminarAntecedentesEnfermedadesProfesionales(\'' . $row->id . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td></tr>';
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

    public function consultaCombosAntecedentesEnfermedadesProfesionales(Request $request)
    {
        $datos = new SelectController();
        $array_response['antecedentes_trabajo'] = $datos->getParametro('RIESGOS_LABORALES', 'http', 2);

        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }

    public function consultaCombosAntecedentesEnfermedadesProfesionales_(Request $request)
    {
        $cqlDatosAntecedentesEnfermedadesProfesionales = parametro::select('id')
            ->where('descripcion', 'RIESGOS_LABORALES')
            ->first();
        $cqlDatosDetallesAntecedentesEnfermedadesProfesionales = parametro::select('id', 'descripcion')
            ->where('parametro_id', (is_null($cqlDatosAntecedentesEnfermedadesProfesionales) ? 0 : $cqlDatosAntecedentesEnfermedadesProfesionales->id))
            ->where('estado', 'A')
            ->with(['hijos' => function ($q) {
                $q->select('id', 'descripcion', 'parametro_id')->where('estado', 'A')->orderby('id', 'asc');
            }])
            ->orderby('id', 'asc')
            ->get()
            ->toArray();
        $array_response['datos'] = $cqlDatosDetallesAntecedentesEnfermedadesProfesionales;
        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }
}
