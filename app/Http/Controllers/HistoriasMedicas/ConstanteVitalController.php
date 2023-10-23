<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\CollectionDataTable;
use App\Http\Controllers\HistoriasMedicas\RegistroController;
use App\Core\Entities\modules\HistoriasMedicas\ConstanteVital;
use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;

use DB;
use Auth;

class ConstanteVitalController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function datatableConstantesVitales($identificacion,$id)
    {
        $data = ConstanteVital::select(
            'id',
            'codigo',
            'presion_arterial',
            'temperatura',
            'frecuencia_cardiaca',
            'saturacion_oxigeno',
            'frecuencia_respiratoria',
            'peso',
            'talla',
            'indice_masa_corporal',
            'perimetro_abdominal',
        )
        ->where('identificacion', $identificacion);
        if ($id != '0')
        $data = $data->whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $identificacion)->where('id', $id)->pluck('codigo'));
    else {
        $codigo_siguiente = (new RegistroController())->consultaCodigoSiguiente($id);
        $data = $data->whereIn('codigo', [$codigo_siguiente['codigo']]);
    }          $data=$data
        ->where('eliminado', false)
        ->where('estado','ACT')
        ->orderBy('id', 'desc')->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
             ->addColumn('', function ($row) { if(!(new RegistroController())->verificarEdicionDatos($row->codigo)) return 'Consulta';
                $btn = ' <table style="width:100%;border:0px">';
                $btn .= '<tr><td style="padding: 2px;border:0px"><a title="Editar" class="btn btn-block btn-outline-primary  btn-xs"  href="#modal-constantes_vitales" role="button" data-toggle="modal"  onclick="app_constantes.editarRegistro(\'' . $row->id . '\')"><i class="fa fa-cog"></i>&nbsp;Editar</a></tr></td>';
                $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger  btn-xs"  onclick="app_constantes.eliminar(\'' . $row->id . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></tr></td>';
                $btn .= '</table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }
    public function guardar(request $request)
    {

        $data = ConstanteVital::whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $request->identificacion)->where('codigo', $request->codigo)->pluck('codigo'))
        ->where('eliminado',false)
        ->get();

        if(count($data) < 1){
            try {
                DB::connection('pgsql')->beginTransaction();
                if ($request->id == 0){
                    $cql = new ConstanteVital();
                    $cql->fecha_inserta = date("Y-m-d H:i:s");
                    $cql->usuario_inserta = Auth::user()->name;
                    $cql->estado = 'ACT';
                }else{
                    $cql = ConstanteVital::find($request->id);
                    $cql->fecha_modifica = date("Y-m-d H:i:s");
                    $cql->usuario_modifica = Auth::user()->name;
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
        } else {
            $array_response['status'] = "201";
            $array_response['message'] = "Ya tiene el maximo de registros permitidos";
        }
        return response()->json($array_response, 200);
    }
    public function editar(request $request){
        $array_response['datos'] = ConstanteVital::where('id', $request->id)->first();
        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }
    public function eliminar(request $request)
    {
        try {
            DB::connection('pgsql')->beginTransaction();
            $cql = ConstanteVital::find($request->id);
            $cql->fecha_modifica = date("Y-m-d H:i:s");
            $cql->usuario_modifica = Auth::user()->name;
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
