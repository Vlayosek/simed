<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\CollectionDataTable;
use App\Http\Controllers\HistoriasMedicas\RegistroController;
use App\Core\Entities\modules\HistoriasMedicas\AntecedenteMedico;
use App\Core\Entities\modules\HistoriasMedicas\HabitosToxicos;
use DB;
use Auth;
use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;

class AntecedentesMedicosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function eliminarAntecedentes(Request $request)
    {
        try {
            DB::connection('pgsql')->beginTransaction();
            $hoy = date("Y-m-d H:i:s");

            $cql = AntecedenteMedico::where('id', $request->id)
                ->where('tipo', $request->tipo)->first();
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
    public function guardarAntecedentesPersonalesQuirurgicos(Request $request)
    {
        try {
            DB::connection('pgsql')->beginTransaction();
            $hoy = date("Y-m-d H:i:s");

            $cql = new AntecedenteMedico();
            $cql->fecha_inserta = $hoy;
            $cql->usuario_inserta = Auth::user()->name;
            $cql->identificacion = $request->identificacion;
            $cql->descripcion = $request->descripcion;
            $cql->tipo = $request->tipo;
            $cql->codigo = $request->codigo;
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
    public function datatablAntecedentesPersonales($identificacion,$id)
    {
        $data = AntecedenteMedico::select(
            'id',
            'descripcion',
            'codigo',

        )
            ->where('identificacion', $identificacion);
            if ($id != '0')
            $data = $data->whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $identificacion)->where('id', $id)->pluck('codigo'));
        else {
            $codigo_siguiente = (new RegistroController())->consultaCodigoSiguiente($id);
            $data = $data->whereIn('codigo', [$codigo_siguiente['codigo']]);
        }              $data=$data
            ->whereNotIn('eliminado', [true])
            ->where('tipo', 'PERSONALES')
            ->orderBy('id', 'desc')->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
             ->addColumn('', function ($row) { if(!(new RegistroController())->verificarEdicionDatos($row->codigo)) return 'Consulta';
                $tipo = 'PERSONALES';
                $btn = ' <table style="width:100%;border:0px">';
                $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger  btn-xs"  onclick="app_antecedentes.eliminarAntecedentes(\'' . $row->id . '\',\'' . $tipo . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td>';
                $btn .= '</tr></table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }
    public function datatablAntecedentesQuirurgicos($identificacion,$id)
    {
        $data = AntecedenteMedico::select(
            'id',
            'descripcion',
            'codigo',

        )
            ->where('identificacion', $identificacion);
            if ($id != '0')
            $data = $data->whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $identificacion)->where('id', $id)->pluck('codigo'));
        else {
            $codigo_siguiente = (new RegistroController())->consultaCodigoSiguiente($id);
            $data = $data->whereIn('codigo', [$codigo_siguiente['codigo']]);
        }              $data=$data
            ->whereNotIn('eliminado', [true])
            ->where('tipo', 'QUIRURGICOS')
            ->orderBy('id', 'desc')->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
             ->addColumn('', function ($row) { if(!(new RegistroController())->verificarEdicionDatos($row->codigo)) return 'Consulta';
                $tipo = 'QUIRURGICOS';
                $btn = ' <table style="width:100%;border:0px">';
                $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger  btn-xs"  onclick="app_antecedentes.eliminarAntecedentes(\'' . $row->id . '\',\'' . $tipo . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td></tr>';
                $btn .= '</table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }


    public function datatableHabitos($identificacion,$id)
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
            if ($id != '0')
            $data = $data->whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $identificacion)->where('id', $id)->pluck('codigo'));
        else {
            $codigo_siguiente = (new RegistroController())->consultaCodigoSiguiente($id);
            $data = $data->whereIn('codigo', [$codigo_siguiente['codigo']]);
        }              $data=$data
            ->whereNotIn('eliminado', [true])
            ->orderBy('id', 'desc')->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
             ->addColumn('', function ($row) { if(!(new RegistroController())->verificarEdicionDatos($row->codigo)) return 'Consulta';

                $btn = ' <table ><tr>';
                $btn .= '<td style="padding: 2px"><a title="Editar" class="btn btn-outline-primary  btn-xs"  href="#modal-habitos" role="button" class="btn"
                data-toggle="modal" onclick="app_habitos.editarHabito(\'' . $row->id . '\')" ><i class="fa fa-cog"></i>&nbsp;Editar</a></td>';
                $btn .= '<td style="padding:2px"><button title="Eliminar" class="btn btn-outline-danger  btn-xs"  onclick="app_habitos.eliminarHabito(\'' . $row->id . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td>';
                $btn .= ' </tr></table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }
}
