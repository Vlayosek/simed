<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\CollectionDataTable;

use App\Core\Entities\modules\HistoriasMedicas\AntecedenteReproductivoMasculino;
use App\Core\Entities\modules\HistoriasMedicas\ExamenReproductivoMasculino;
use App\Http\Controllers\HistoriasMedicas\RegistroController;
use DB;
use Auth;
use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;

class ReproductivoMasculinoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function datatableAntecedentesReproductivosMasculinos($identificacion, $id)
    {
        $data = AntecedenteReproductivoMasculino::select(
            'id',
            'planificacion_familiar',
            'tipo_planificacion_familiar',
            'hijos_vivos',
            'hijos_muertos',
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
            ->where('estado', 'ACT')
            ->orderBy('id', 'desc')->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                if (!(new RegistroController())->verificarEdicionDatos($row->codigo)) return 'Consulta';
                $btn = ' <table style="width:100%;border:0px">';
                $btn .= '<tr><td style="padding: 2px;border:0px"><a title="Editar" class="btn btn-block btn-outline-primary  btn-xs"  href="#modal-antecedentes_reproductivos_masculinos" role="button" data-toggle="modal"  onclick="app_antecedentes_reproductivos_masculinos.editarRegistro(\'' . $row->id . '\',\'antecedente\')"><i class="fa fa-cog"></i>&nbsp;Editar</a></td></tr>';
                $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger  btn-xs"  onclick="app_antecedentes_reproductivos_masculinos.eliminar(\'' . $row->id . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td></tr>';
                $btn .= '</table>';
                return $btn;
            })
            ->addColumn('hijos', function ($row) {
                return "Vivos: " . $row->hijos_vivos . "|" . "Muertos: " . $row->hijos_muertos;
            })
            ->addColumn('planificacion_familiar_', function ($row) {
                if ($row->planificacion_familiar == true)
                    $pf = "SI";
                else {
                    if ($row->planificacion_familiar == false)
                        $pf = "NO";
                    else
                        $pf = "N/A";
                }
                return $pf . "|" . "TIPO: " . $row->tipo_planificacion_familiar;
            })
            ->rawColumns([''])
            ->toJson();
    }
    public function datatableExamenesReproductivosMasculinos($identificacion, $id)
    {
        $data = ExamenReproductivoMasculino::select(
            'id',
            'tipo_examen',
            'realizo_examen',
            'tiempo',
            'resultado',
            'codigo',

        )
            ->where('identificacion', $identificacion);
            if ($id != '0')
            $data = $data->whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $identificacion)->where('id', $id)->pluck('codigo'));
        else {
            $codigo_siguiente = (new RegistroController())->consultaCodigoSiguiente($id);
            $data = $data->whereIn('codigo', [$codigo_siguiente['codigo']]);
        }        $data = $data
            ->where('eliminado', false)
            ->where('estado', 'ACT')
            ->orderBy('id', 'desc')->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                if (!(new RegistroController())->verificarEdicionDatos($row->codigo)) return 'Consulta';
                $btn = ' <table style="width:100%;border:0px">';
                $btn .= '<tr><td style="padding: 2px;border:0px"><a title="Editar" class="btn btn-block btn-outline-primary  btn-xs"  href="#modal-examenes_reproductivos_masculinos" role="button" data-toggle="modal"  onclick="app_examenes_reproductivos_masculinos.editarRegistro(\'' . $row->id . '\',\'examen\')"><i class="fa fa-cog"></i>&nbsp;Editar</a></td></tr>';
                $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger  btn-xs"  onclick="app_examenes_reproductivos_masculinos.eliminar(\'' . $row->id . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td></tr>';
                $btn .= '</table>';
                return $btn;
            })
            ->addColumn('realizo_examen_', function ($row) {
                if ($row->realizo_examen == true)
                    return "SI";
                else {
                    if ($row->realizo_examen == false)
                        return "NO";
                    else
                        return "N/A";
                }
            })
            ->rawColumns([''])
            ->toJson();
    }
    public function guardar(request $request)
    {
        try {
            $editar = false;
            if ($request->id != 0) $editar = true;
            $validaRegistro = $this->validaRegistro($request,$editar);
            if ($validaRegistro){
                $array_response['status'] = 300;
                $array_response['message'] = "Ya tiene un registro";
            } else {
                DB::connection('pgsql')->beginTransaction();
                if ($request->id == 0) {
                    if ($request->tipo_guardar == "antecedente")
                        $cql = new AntecedenteReproductivoMasculino();
                    if ($request->tipo_guardar == "examen")
                        $cql = new ExamenReproductivoMasculino();
                    $cql->fecha_inserta = date("Y-m-d H:i:s");
                    $cql->usuario_inserta = Auth::user()->name;
                    $cql->estado = 'ACT';
                } else {
                    if ($request->tipo_guardar == "antecedente")
                        $cql = AntecedenteReproductivoMasculino::find($request->id);
                    if ($request->tipo_guardar == "examen")
                        $cql = ExamenReproductivoMasculino::find($request->id);
                    $cql->fecha_modifica = date("Y-m-d H:i:s");
                    $cql->usuario_modifica = Auth::user()->name;
                }
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
    public function editar(request $request)
    {
        if ($request->tipo == "antecedente")
            $array_response['datos'] = AntecedenteReproductivoMasculino::where('id', $request->id)->first();
        else
            $array_response['datos'] = ExamenReproductivoMasculino::where('id', $request->id)->first();
        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }
    public function eliminar(request $request)
    {
        try {
            DB::connection('pgsql')->beginTransaction();
            if ($request->tipo == "antecedente")
                $cql = AntecedenteReproductivoMasculino::find($request->id);
            if ($request->tipo == "examen")
                $cql = ExamenReproductivoMasculino::find($request->id);
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
    private function validaRegistro($request,$editar)
    {
        if ($request->tipo_guardar == "antecedente" && !$editar)
            $cql = AntecedenteReproductivoMasculino::select('id');
        else{
            $cql = ExamenReproductivoMasculino::select('id')->where('tipo_examen', $request->tipo_examen);
            if ($editar) $cql = $cql->whereNotIn('id', array(0 => $request->id));
        }
        $cql = $cql->where('eliminado', false)->where('estado', 'ACT')
        ->where('codigo', $request->codigo)->where('identificacion', $request->identificacion)->first();
        //true si hay registro, false sino hay
        return is_null($cql) ? false : true;
    }

}
