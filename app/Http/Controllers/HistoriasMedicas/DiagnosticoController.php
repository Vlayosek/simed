<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\CollectionDataTable;
use App\Http\Controllers\HistoriasMedicas\RegistroController;
use App\Core\Entities\modules\HistoriasMedicas\Diagnostico;
use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;
use App\Core\Entities\modules\HistoriasMedicas\CodigoCie;

use DB;
use Auth;

class DiagnosticoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function datatableDiagnosticos($identificacion, $id)
    {
        $data = Diagnostico::select(

            'diagnosticos.id',
            'diagnosticos.descripcion',
            'cie.descripcion as cie',
            //'diagnosticos.cie',
            'diagnosticos.tipo',
            'diagnosticos.codigo',
        )
            ->join('sc_historias_clinicas.codigos_cie as cie', 'cie.id', 'diagnosticos.codigo_cie_id')
            ->where('diagnosticos.identificacion', $identificacion);
        if ($id != '0')
            $data = $data->whereIn('diagnosticos.codigo', AtencionMedica::select('codigo')->where('identificacion', $identificacion)->where('id', $id)->pluck('codigo'));
        else {
            $codigo_siguiente = (new RegistroController())->consultaCodigoSiguiente($id);
            $data = $data->whereIn('diagnosticos.codigo', [$codigo_siguiente['codigo']]);
        }
        $data = $data
            ->where('diagnosticos.eliminado', false)
            ->where('diagnosticos.estado', 'ACT')
            ->orderBy('diagnosticos.id', 'desc')->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                if (!(new RegistroController())->verificarEdicionDatos($row->codigo)) return 'Consulta';
                $btn = ' <table style="width:100%;border:0px">';
                $btn .= '<tr><td style="padding: 2px;border:0px"><a title="Editar" class="btn btn-block btn-outline-primary  btn-xs"  href="#modal-nuevo_diagnostico" role="button" data-toggle="modal"  onclick="app_diagnostico.editarRegistro(\'' . $row->id . '\')"><i class="fa fa-cog"></i>&nbsp;Editar</a></td></tr>';
                $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger  btn-xs"  onclick="app_diagnostico.eliminar(\'' . $row->id . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td></tr>';
                $btn .= '</table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }
    public function guardar(request $request)
    {

        $max = 0;

        $request->evaluacion == 'ATENCIONES DIARIAS' ?   $max = 2 : $max = 3;

        $data = Diagnostico::whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $request->identificacion)->where('codigo', $request->codigo)->pluck('codigo'))
            ->where('eliminado', false)
            ->get();

        if (count($data) < $max) {
            try {
                DB::connection('pgsql')->beginTransaction();
                if ($request->id == 0) {
                    $cql = new Diagnostico();
                    $cql->fecha_inserta = date("Y-m-d H:i:s");
                    $cql->usuario_inserta = Auth::user()->name;
                    $cql->estado = 'ACT';
                } else {
                    $cql = Diagnostico::find($request->id);
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

    public function editar(request $request)
    {

        $array_response['datos'] = Diagnostico::where('id', $request->id)->first();
        $data = CodigoCie::select('descripcion')->where('id', $array_response['datos']->codigo_cie_id)->first();
        if (!is_null($data)) {
            $cie_descripcion = !is_null($data->descripcion) ? $data->descripcion : '';
        }
        $array_response['cie_descripcion'] = $cie_descripcion;
        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }
    public function eliminar(request $request)
    {
        try {
            DB::connection('pgsql')->beginTransaction();
            $cql = Diagnostico::find($request->id);
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
    function getCargaDatosCie(request $request)
    {
        $letras_tildes = 'áéíóúÁÉÍÓÚäëïöüÄËÏÖÜ';
        $letras_sin_tildes = 'aeiouAEIOUaeiouAEIOU';
        $input = $request->all();
        if (!empty($input['query'])) {
            $busqueda = strtoupper($input['query']);
            if ($input['tipo'] == "cie_id") {
                $data = CodigoCie::select(
                    DB::RAW("CONCAT(codigo,' / ',descripcion) as descripcion"),
                    "id"
                );
                $buscar = $busqueda;
                $data = $data->where(function ($q) use ($letras_sin_tildes, $letras_tildes, $buscar) {
                    $q->whereRaw('translate(UPPER(codigo),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($buscar) . '%'])
                        ->orwhereRaw('translate(UPPER(descripcion),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($buscar) . '%']);
                });
                $data = $data->orderby('descripcion', 'asc')->get();
            }
        } else {
            if ($input['tipo'] == "cie_id") {
                $data = CodigoCie::select(
                    DB::RAW("CONCAT(codigo,' / ',descripcion) as descripcion"),
                    "id"
                )
                    ->orderby('codigo', 'asc')
                    ->take(5)
                    ->get();
            }
        }
        /* result */
        $selectores = [];
        if (count($data) > 0) {
            foreach ($data as $select) {
                $selectores[] = array(
                    "id" => $select->id,
                    "text" => $select->descripcion,
                );
            }
        }
        return response()->json($selectores);
    }
}
