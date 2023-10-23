<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Ajax\SelectController;
use Yajra\DataTables\CollectionDataTable;
use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;

use App\Core\Entities\modules\HistoriasMedicas\RevisionOrgano;
use App\Http\Controllers\HistoriasMedicas\RegistroController;
use DB;
use Auth;
class RevisionOrganosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function editarRevisionOrganos(Request $request)
    {
        try {
            DB::connection('pgsql')->beginTransaction();
            $array_response['status'] = "200";
            $array_response['datos'] = RevisionOrgano::find($request->id);
        } catch (\Exception $e) {
            $array_response['status'] = 404;
            $array_response['datos'] = null;
        }
        return response()->json($array_response, 200);
    }
    public function eliminarRevisionOrganos(Request $request)
    {
        try {
            DB::connection('pgsql')->beginTransaction();
            $hoy = date("Y-m-d H:i:s");

            $cql = RevisionOrgano::where('id',$request->id)->first();
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
    public function guardarRevisionOrganos(Request $request)
    {
        try {
            DB::connection('pgsql')->beginTransaction();
            $hoy = date("Y-m-d H:i:s");
            if($request->id!=0){
                $cql =  RevisionOrgano::find($request->id);
                $cql->fecha_modifica = $hoy;
                $cql->usuario_modifica = Auth::user()->name;
            }else{
                $cql = new RevisionOrgano();
                $cql->fecha_inserta = $hoy;
                $cql->usuario_inserta = Auth::user()->name;
            }
    
            $cql->identificacion = $request->identificacion;
            $cql->codigo = $request->codigo;
            $cql->descripcion = $request->descripcion;
            $cql->detalle = $request->detalle;
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
    public function cargarDatatableRevisionOrganos($identificacion,$id)
    {
        $data = RevisionOrgano::select(
                'id',
                'descripcion',
                'detalle',
                'codigo',

            )
            ->where('identificacion', $identificacion);
            if ($id != '0')
            $data = $data->whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $identificacion)->where('id', $id)->pluck('codigo'));
        else {
            $codigo_siguiente = (new RegistroController())->consultaCodigoSiguiente($id);
            $data = $data->whereIn('codigo', [$codigo_siguiente['codigo']]);
        }              $data=$data
            ->where('eliminado',false)
            ->orderBy('id', 'desc')->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
             ->addColumn('', function ($row) { if(!(new RegistroController())->verificarEdicionDatos($row->codigo)) return 'Consulta';
                    $btn = ' <table style="width:100%;border:0px">';
                    $btn .= '<tr><td style="padding: 2px;border:0px"><a title="Editar" class="btn btn-block btn-outline-primary  btn-xs"  href="#modal-container-revision_organos" role="button" class="btn"
                    data-toggle="modal"  onclick="app_revision_organos.editarRevisionOrganos(\'' . $row->id . '\')"><i class="fa fa-cog"></i>&nbsp;Editar</a></td></tr>';
                    $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger  btn-xs"  onclick="app_revision_organos.eliminarRevisionOrganos(\'' . $row->id . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td></tr>';
                    $btn .= '</table>';
                    return $btn;
               
            })
            ->rawColumns([''])
            ->toJson();
    }
    public function consultaCombosRegistroRevisionOrganos(Request $request)
    {
        $datos = new SelectController();
        $array_response['datos'] = $datos->getParametro('ORGANOS', 'http', 4);
        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }
    
    
}
