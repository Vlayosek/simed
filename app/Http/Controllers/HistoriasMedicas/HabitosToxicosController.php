<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;
use App\Core\Entities\modules\HistoriasMedicas\HabitosToxicos;
use App\Http\Controllers\Ajax\SelectController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HistoriasMedicas\RegistroController;
use Auth;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\CollectionDataTable;

class HabitosToxicosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function datatableHabitos($identificacion, $id)
    {
        $data = HabitosToxicos::select(
            'id',
            'descripcion',
            'valor',
            'identificacion',
            'tiempo_consumo',
            'cantidad',
            'ex_consumidor',
            'tiempo_abstinencia',
            'codigo',

        )
            ->where('identificacion', $identificacion);

        if ($id != '0') {
            $data = $data->whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $identificacion)->where('id', $id)->pluck('codigo'));
        } else {
            $codigo_siguiente = (new RegistroController())->consultaCodigoSiguiente($id);
            $data = $data->whereIn('codigo', [$codigo_siguiente['codigo']]);
        }
        $data = $data
            ->whereNotIn('eliminado', [true])
            ->orderBy('id', 'desc')->get();

        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                if (!(new RegistroController())->verificarEdicionDatos($row->codigo)) {
                    return 'Consulta';
                }

                $btn = ' <table style="width:100%;border:0px">';
                $btn .= '<tr><td style="padding: 2px;border:0px"><a title="Editar" class="btn btn-block btn-outline-primary  btn-xs"  href="#modal-habitos" role="button" class="btn"
                data-toggle="modal" onclick="app_habitos.editarHabito(\'' . $row->id . '\')" ><i class="fa fa-cog"></i>&nbsp;Editar</a></td></tr>';
                $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger  btn-xs"  onclick="app_habitos.eliminarHabito(\'' . $row->id . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td></tr>';
                $btn .= '</table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }

    public function consultaComboHabitos(Request $request)
    {
        $datos = new SelectController();
        $array_response['habitos'] = $datos->getParametro('CONSUMOS_NOCIVOS', 'http', 4);
        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }

    public function guardarHabitos(Request $request)
    {
        //dd($request);
        try {
            DB::connection('pgsql')->beginTransaction();
            if ($request->id == 0) {
                $cql = new HabitosToxicos();
                $cql->fecha_inserta = date("Y-m-d H:i:s");
                $cql->usuario_inserta = Auth::user()->name;
            } else {
                $cql = HabitosToxicos::find($request->id);
                $cql->fecha_modifica = date("Y-m-d H:i:s");
                $cql->usuario_modifica = Auth::user()->name;
            }

            $cql->save();
            $cql->fill($request->all())->save();
            DB::connection('pgsql')->commit();
            $array_response['status'] = 200;
            $array_response['message'] = "Grabado exitosamente";
        } catch (\Exception$e) {
            DB::connection('pgsql_presidencia')->rollBack();
            $array_response['status'] = 404;
            $array_response['message'] = $e->getMessage();
        }
        return response()->json($array_response, 200);
    }

    public function editarHabito(Request $request)
    {
        try {
            $array_response['status'] = "200";
            $array_response['datos'] = HabitosToxicos::where('id', $request->id)->first();
        } catch (\Exception$e) {
            $array_response['status'] = 404;
            $array_response['datos'] = null;
        }
        return response()->json($array_response, 200);
    }

    public function eliminarHabito(Request $request)
    {
        try {
            DB::connection('pgsql_presidencia')->beginTransaction();
            HabitosToxicos::where('id', $request->id)->where('eliminado', false)->update(['eliminado' => true, 'usuario_modifica' => Auth::user()->name, 'fecha_modifica' => date("Y-m-d H:i:s"), 'estado' => 'INA']);

            DB::connection('pgsql_presidencia')->commit();
            $array_response['status'] = "200";
            $array_response['message'] = "Eliminado exitosamente";
        } catch (\Exception$e) {
            DB::connection('pgsql_presidencia')->rollBack();
            $array_response['status'] = 404;
            $array_response['message'] = $e->getMessage();
        }
        return response()->json($array_response, 200);
    }
}
