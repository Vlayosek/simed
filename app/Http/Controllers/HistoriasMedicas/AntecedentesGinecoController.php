<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Ajax\SelectController;
use Yajra\DataTables\CollectionDataTable;

use App\Core\Entities\modules\HistoriasMedicas\AntecedenteGinecoObstetrico;
use App\Core\Entities\modules\HistoriasMedicas\AntecedenteMedico;
use App\Core\Entities\modules\HistoriasMedicas\ExamenGinecoObstetrico;
use DB;
use Auth;

class AntecedentesGinecoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function guardarGineco(request $request)
    {
        try {
            DB::connection('pgsql')->beginTransaction();
            if ($request->id == 0) {
                if ($request->tipo_guardar == "antecedente")
                    $cql = new AntecedenteGinecoObstetrico();
                if ($request->tipo_guardar == "examen")
                    $cql = new ExamenGinecoObstetrico();
                $cql->fecha_inserta = date("Y-m-d H:i:s");
                $cql->usuario_inserta = Auth::user()->name;
                $cql->estado = 'ACT';
                $cql->save();
                $cql->fill($request->all())->save();
                DB::connection('pgsql')->commit();
                $array_response['status'] = 200;
                $array_response['message'] = "Grabado exitosamente";
            }
        } catch (\Exception $e) {
            DB::connection('pgsql_presidencia')->rollBack();
            $array_response['status'] = 404;
            $array_response['message'] = $e->getMessage();
        }
        return response()->json($array_response, 200);
    }
    public function datatablAntecedentesPersonales($identificacion)
    {
        $data = AntecedenteMedico::select(
            'id',
            'descripcion',
        )
            ->where('identificacion', $identificacion)
            ->whereNotIn('eliminado', [true])
            ->where('tipo', 'PERSONALES')
            ->orderBy('id', 'desc')->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                $tipo = 'PERSONALES';
                $btn = ' <table ><tr>';
                $btn .= '<td style="padding:2px"><button title="Eliminar" class="btn btn-outline-danger  btn-xs"  onclick="app_antecedentes.eliminarAntecedentes(\'' . $row->id . '\',\'' . $tipo . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td>';
                $btn .= ' </tr></table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }
}
