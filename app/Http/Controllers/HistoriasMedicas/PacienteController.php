<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;
use App\Core\Entities\modules\HistoriasMedicas\CodigoCiuo;
use App\Core\Entities\modules\HistoriasMedicas\Paciente;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Illuminate\Http\Request;

class PacienteController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function grabarPaciente($request, $data, $externo = false)
    {
        // dd($request, $data);
        try {
            DB::connection('pgsql')->beginTransaction();
            $cql = Paciente::where('codigo', $data['codigo'])->where('eliminado', false)->first();
            if (is_null($cql)) {
                Paciente::where('identificacion', $data['identificacion'])->update(['estado' => 'INA']);
                $cql = new Paciente();
                $cql->fecha_inserta = date('Y-m-d H:i:s');
                $cql->usuario_inserta = Auth::user()->name;
            } else {
                if ($cql->identificacion != $data['identificacion']) {
                    $cqlUpdate = Paciente::where('identificacion', $cql->identificacion)->whereNotIn('id', [$cql->id])->orderby('id', 'desc')->first();
                    Paciente::where('identificacion', $data['identificacion'])->update(['estado' => 'INA']);
                    if (!is_null($cqlUpdate)) {
                        $cqlUpdate->estado = 'ACT';
                        $cqlUpdate->save();
                    }
                } else {
                    $cql->fecha_modifica = date('Y-m-d H:i:s');
                    $cql->usuario_inserta = Auth::user()->name;
                }
            }
            $cql->puesto_trabajo_ciuo = $data['puesto_trabajo_id'];
            $cql->save();
            if ($externo)
                $cql->fill($request)->save();
            else
                $cql->fill($request->all())->save();

            AtencionMedica::where('codigo', $data['codigo'])->where('eliminado', false)->update(['identificacion' => $data['identificacion']]);

            DB::connection('pgsql')->commit();
            $array_response['status'] = 200;
            $array_response['datos'] = "Grabado exitosamente";
        } catch (\Exception $e) {
            DB::connection('pgsql_presidencia')->rollBack();
            $array_response['status'] = 404;
            $array_response['datos'] = $e->getMessage();
        }
        return $array_response;
    }

    public function guardarPaciente(Request $request)
    {
        $data['codigo'] = $request->codigo;
        $data['identificacion'] = $request->identificacion;
        $data['puesto_trabajo_id'] = $request->puesto_trabajo_id;

        $array_response = $this->grabarPaciente($request, $data);

        return response()->json($array_response, 200);
    }

    public function getCargaDatosCiuo(request $request)
    {
        $letras_tildes = 'áéíóúÁÉÍÓÚäëïöüÄËÏÖÜ';
        $letras_sin_tildes = 'aeiouAEIOUaeiouAEIOU';
        $input = $request->all();
        if (!empty($input['query'])) {
            $busqueda = strtoupper($input['query']);
            if ($input['tipo'] == "ciuo_id") {
                $data = CodigoCiuo::select(
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
            if ($input['tipo'] == "ciuo_id") {
                $data = CodigoCiuo::select(
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
