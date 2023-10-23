<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;
use App\Core\Entities\modules\HistoriasMedicas\CodigoCiiu;
use App\Core\Entities\modules\HistoriasMedicas\CodigoCiuo;
use App\Core\Entities\modules\HistoriasMedicas\CodigoHistoria;
use App\Core\Entities\modules\HistoriasMedicas\Paciente;
use App\Core\Entities\modules\HistoriasMedicas\Tipo_evaluacion;

use App\Http\Controllers\Ajax\SelectController;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Illuminate\Http\Request;

class RegistroController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function consultas()
    {
        return view('modulos.historias_medicas.consulta.index');
    }

    public function editar($id)
    {
        $variableEntorno = $this->variablesEntorno($id);


        return view('modulos.historias_medicas.registro.index', $variableEntorno);
    }
    public function registro()
    {
        $variableEntorno = $this->variablesEntorno();
        return view('modulos.historias_medicas.registro.index', $variableEntorno);
    }
    protected function consultarDatosFuncionario($identificacion, $codigo = null)
    {
        $datos = new SelectController();
        $datos = $datos->buscarDatosUath($identificacion);
        if (is_null($codigo)) {
            $datosEspecificos = Paciente::where('identificacion', $identificacion)->orderby('id', 'desc')->first();
            $dataAtencionMedica = AtencionMedica::where('identificacion', $identificacion)->orderby('id', 'desc')->first();
        } else {
            $datosEspecificos = Paciente::where('codigo', $codigo)->where('identificacion', $identificacion)->orderby('id', 'desc')->first();
            $dataAtencionMedica = AtencionMedica::where('codigo', $codigo)->where('identificacion', $identificacion)->orderby('id', 'desc')->first();
        }

        $data = null;
        $data_ = null;

        if (!is_null($datosEspecificos)) {
            $data = CodigoCiuo::select('descripcion')->where('id', $datosEspecificos->puesto_trabajo_ciuo)->first();
        }

        if (!is_null($dataAtencionMedica)) {
            $data_ = CodigoCiiu::select('descripcion')->where('id', $dataAtencionMedica->codigo_ciiu)->first();
        }

        !is_null($data) ? $ciuo_descripcion = $data->descripcion : $ciuo_descripcion = '';
        !is_null($data_) ? $ciiu_descripcion = $data_->descripcion : $ciiu_descripcion = '';

        $array_response['status'] = 200;
        $array_response['datos'] = $datos;
        $array_response['especificos'] = $datosEspecificos;
        $array_response['atencion_medica'] = $dataAtencionMedica;
        $array_response['identificacion'] = $identificacion;
        $array_response['ciuo_descripcion'] = $ciuo_descripcion;
        $array_response['ciiu_descripcion'] = $ciiu_descripcion;
        return $array_response;
    }
    public function buscarFuncionario(Request $request)
    {
        $array_response = $this->consultarDatosFuncionario($request->identificacion);
        return response()->json($array_response, 200);
    }
    public function buscarSeccionesTiposEvaluaciones(Request $request)
    {
        $array_response['status'] = 200;
        $array_response['secciones'] = Tipo_evaluacion::select('seccion', 'campos')->where('descripcion', $request->descripcion)->where('eliminado', false)->first();
        $array_response['datos'] = is_null($array_response['secciones']) ? null : $array_response['secciones']->seccion;
        $array_response['campos'] = is_null($array_response['secciones']) ? null : $array_response['secciones']->campos;
        return response()->json($array_response, 200);
    }

    public function editarRegistro(Request $request)
    {
        $cql = AtencionMedica::find($request->id);
        $array_response = $this->consultarDatosFuncionario($cql->identificacion, $cql->codigo);
        $cqlConsultaAtencion = AtencionMedica::where('id', $request->id)->first();

        $array_response['editar'] = $this->verificarEdicionDatos($cqlConsultaAtencion->codigo);
        $array_response['datos'] = $cqlConsultaAtencion;
        return response()->json($array_response, 200);
    }
    public function verificarEdicionDatos($codigo)
    {
        //$edicion = false;
        $edicion = true;
        $cqlConsultaCodigos = CodigoHistoria::select('codigo')->where('eliminado', false)->orderby('id', 'desc')->first()->codigo;
        if ($codigo == $cqlConsultaCodigos) {
            $edicion = true;
        }

        return $edicion;
    }
    public function consultaCombosRegistro(Request $request)
    {
        $datos = new SelectController();
        $array_response['lateralidad'] = $datos->getParametro('LATERALIDAD', 'http', 4);
        $array_response['identidad_genero'] = $datos->getParametro('IDENTIDAD GENERO', 'http', 4);
        $array_response['orientacion_sexual'] = $datos->getParametro('ORIENTACION SEXUAL', 'http', 4);
        $array_response['genero'] = $datos->getParametro('GENERO', 'http', 4);
        $array_response['religion'] = $datos->getParametro('RELIGION', 'http', 4);
        $array_response['tipo_sangre'] = $datos->getParametro('TIPO SANGRE', 'http', 4);
        $array_response['tipo_evaluacion'] = $datos->getParametro('TIPO EVALUACION', 'http', 4);
        $array_response['discapacidades'] = $datos->getParametro('DISCAPACIDADES', 'http', 4);

        $array_response['status'] = 200;
        return response()->json($array_response, 200);
    }

    public function consultaCodigoSiguiente($id = 0)
    {
        $resourse['nuevo'] = false;

        if ($id != 0) {
            $codigo = AtencionMedica::find($id);
            $resourse['codigo'] = $codigo->codigo;
        } else {
            $resourse['nuevo'] = true;

            $anio_actual = date('Y');
            $incremantal = config('app_historias_medicas.numero_inicial_sistema');
            $consultaCodigos = CodigoHistoria::select('incremental')->where('eliminado', false)->first();
            $consultaCodigos_actual = null;
            if (!is_null($consultaCodigos)) {
                $incremantal = config('app_historias_medicas.numero_inicial');
                $consultaCodigos_actual = CodigoHistoria::select('incremental')->where('anio', $anio_actual)->orderby('id', 'desc')->first();
            }
            $incremantal = is_null($consultaCodigos_actual) ? $incremantal : ($consultaCodigos_actual->incremental + 1);
            $codigo = config('app_historias_medicas.codigo_area') . '-' . $anio_actual . '-R-' . $incremantal;
            $resourse['incremental'] = $incremantal;
            $resourse['anio'] = $anio_actual;
            $resourse['codigo'] = $codigo;
            $resourse['cantidad'] = is_null($consultaCodigos) ? 0 : 1;
        }

        return $resourse;
    }
    protected function consultarUltimoCodigoRegistrado()
    {
        $cqlConsultaUltimoCodigo = CodigoHistoria::where('eliminado', false)->orderby('id', 'desc')->first();
        $response['codigo'] = is_null($cqlConsultaUltimoCodigo) ? null : $cqlConsultaUltimoCodigo->codigo;
        return $response;
    }
    protected function variablesEntorno($id = 0)
    {
        $objTipo = new SelectController();
        $examenes = $objTipo->getParametro('GINECO OBSTETRICO', 'http', 4);
        $examenes_masculinos = $objTipo->getParametro('REPRODUCTIVO MASCULINO', 'http', 4);
        $tipo_aptitud = $objTipo->getParametro('TIPO APTITUD', 'http', 4);
        $codigo_siguiente = $this->consultaCodigoSiguiente($id);
        $consultarUltimoCodigoRegistrado = $this->consultarUltimoCodigoRegistrado();
        $editar = $codigo_siguiente['codigo'] == $consultarUltimoCodigoRegistrado['codigo'] || $codigo_siguiente['nuevo'] ? true : false;
        return [
            'editar' => $editar,
            'codigo_siguiente' => $codigo_siguiente['codigo'],
            'id' => $id,
            'examenes' => $examenes,
            'examenes_masculinos' => $examenes_masculinos,
            'tipo_aptitud' => $tipo_aptitud,
        ];
    }
    public function getCargarDatosFuncionario(request $request)
    {
        $letras_tildes = 'áéíóúÁÉÍÓÚäëïöüÄËÏÖÜ';
        $letras_sin_tildes = 'aeiouAEIOUaeiouAEIOU';
        $input = $request->all();
        if (!empty($input['query'])) {
            $busqueda = strtoupper($input['query']);
            $data = Paciente::select(
                DB::RAW("CONCAT(identificacion,' / ',UPPER(apellidos),' ',UPPER(nombres)) as descripcion"),
                "identificacion"
            );
            $buscar = $busqueda;
            $data = $data->where(function ($q) use ($letras_sin_tildes, $letras_tildes, $buscar) {
                $q->whereRaw('translate(UPPER(identificacion),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($buscar) . '%'])
                    ->orwhereRaw('translate(UPPER(apellidos),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($buscar) . '%'])
                    ->orwhereRaw('translate(UPPER(nombres),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($buscar) . '%']);
            });
            $data = $data->orderby('apellidos', 'asc')->groupBy('identificacion', 'apellidos', 'nombres')->get();
        } else {
            $data = Paciente::select(
                DB::RAW("CONCAT(identificacion,' / ',UPPER(apellidos),' ',UPPER(nombres)) as descripcion"),
                "identificacion"
            )
                ->orderby('apellidos', 'asc')
                ->groupBy('identificacion', 'apellidos', 'nombres')
                ->take(5)
                ->get();
        }
        /* result */
        $selectores = [];
        if (count($data) > 0) {
            foreach ($data as $select) {
                $selectores[] = array(
                    "id" => $select->identificacion,
                    "text" => $select->descripcion,
                );
            }
        }
        return response()->json($selectores);
    }

    public function eliminarHistoria(request $request)
    {
        try {
            DB::connection('pgsql_presidencia')->beginTransaction();
            $cql = AtencionMedica::find($request->id);
            $cql->usuario_modifica = Auth::user()->name;
            $cql->fecha_modifica = date('Y-m-d');
            $cql->eliminado = true;
            $cql->estado = 'ACT';
            $cql->save();
            $array_response['status'] = 200;
            $array_response['message'] = "Registro eliminado";
            DB::connection('pgsql_presidencia')->commit();
        } catch (\Exception $e) {
            DB::connection('pgsql_presidencia')->rollBack();
            $array_response['status'] = 404;
            $array_response['message'] = $e->getMessage();
        }
        return response()->json($array_response, 200);
    }
}
