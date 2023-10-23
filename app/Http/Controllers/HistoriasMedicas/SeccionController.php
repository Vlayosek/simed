<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Ajax\SelectController;
use Yajra\DataTables\CollectionDataTable;
use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;
use App\Core\Entities\modules\HistoriasMedicas\Tipo_evaluacion;

use App\Core\Entities\modules\HistoriasMedicas\RevisionOrgano;
use App\Http\Controllers\HistoriasMedicas\RegistroController;
use DB;
use Auth;
class SeccionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $datos = new SelectController();
        $tipos_evaluaciones=$datos->getParametro('TIPO EVALUACION', 'http', 4);
        return view('modulos.historias_medicas.administracion.secciones.index',compact('tipos_evaluaciones'));
    }

    public function datatableSecciones()
    {
        $data = Tipo_evaluacion::where('eliminado',false)->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
             ->addColumn('', function ($row) {
                    $btn = ' <table style="width:100%;border:0px">';
                    $btn .= '<tr><td style="padding: 2px;border:0px"><a title="Editar" class="btn btn-block btn-outline-primary  btn-xs"  href="#modal-secciones" role="button" class="btn"
                    data-toggle="modal"  onclick="app.editarRegistro(\'' . $row->id . '\')"><i class="fa fa-cog"></i>&nbsp;Editar</a></td></tr>';
                    $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger  btn-xs"  onclick="app.eliminarRegistro(\'' . $row->id . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td></tr>';
                    $btn .= '</table>';
                    return $btn;

            })
            ->rawColumns([''])
            ->toJson();
    }
    public function editarRegistro(Request $request)
    {
        try {
            DB::connection('pgsql')->beginTransaction();
            $array_response['status'] = "200";
            $array_response['datos'] = Tipo_evaluacion::find($request->id);
        } catch (\Exception $e) {
            $array_response['status'] = 404;
            $array_response['datos'] = null;
        }
        return response()->json($array_response, 200);
    }
    public function eliminarRegistro(Request $request)
    {
        try {
            DB::connection('pgsql')->beginTransaction();
            $hoy = date("Y-m-d H:i:s");

            $cql = Tipo_evaluacion::where('id',$request->id)->first();
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
    public function guardarSeccion(Request $request)
    {
        try {
            DB::connection('pgsql')->beginTransaction();
            $hoy = date("Y-m-d H:i:s");
            if($request->id!=0){
                $cqlConsulta = Tipo_evaluacion::where('descripcion',$request->descripcion)
                ->whereNotIn('id',[$request->id])
                ->where('eliminado',false)->first();

                $cql = Tipo_evaluacion::find($request->id);
                $cql->fecha_modifica = $hoy;
                $cql->usuario_modifica = Auth::user()->name;
            }else{
                $cqlConsulta = Tipo_evaluacion::where('descripcion',$request->descripcion)->where('eliminado',false)->first();
                $cql = new Tipo_evaluacion();
                $cql->fecha_inserta = $hoy;
                $cql->usuario_inserta = Auth::user()->name;
            }
            if(!is_null($cqlConsulta)) throw new \Exception("Ya se encuentra registrada esta descripción");

            $cql->descripcion = $request->descripcion;
            $cql->seccion = $request->seccion;
            $cql->campos = $request->campos;
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


}
