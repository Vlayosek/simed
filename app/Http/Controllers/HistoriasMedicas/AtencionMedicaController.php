<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;
use App\Core\Entities\modules\HistoriasMedicas\CodigoCiiu;
use App\Core\Entities\modules\HistoriasMedicas\CodigoHistoria;
use App\Core\Entities\modules\HistoriasMedicas\Diagnostico;
use App\Core\Entities\modules\HistoriasMedicas\Paciente;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HistoriasMedicas\GenerarArchivoController as GA;
use App\Http\Controllers\HistoriasMedicas\RegistroController;
use Auth;
use DB;
//use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\CollectionDataTable;
use Illuminate\Support\Facades\File;

class AtencionMedicaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function guardarAtencionMedica(Request $request)
    {
        $codigo = $request->datos['codigo'];
        $ciiu = $request->datos['ciiu'];
        try {
            DB::connection('pgsql')->beginTransaction();
            /* $cqlPaciente = Paciente::where('codigo', $request->codigo)->where('eliminado', false)->first();
            if (is_null($cqlPaciente)) {
                throw new \Exception("Debe grabar el paciente antes de grabar los datos");
            } */

            $cql = AtencionMedica::where('codigo',  $codigo)->where('eliminado', false)->first();
            $inicia = true;
            if (is_null($cql)) {
                $cql = new AtencionMedica();
                $cql->fecha_inserta = date('Y-m-d H:i:s');
                $cql->usuario_inserta = Auth::user()->name;
            } else {
                $inicia = false;

                $cql->fecha_modifica = date('Y-m-d H:i:s');
                $cql->usuario_inserta = Auth::user()->name;
            }
            $cql->codigo_ciiu = $ciiu;
            $cql->save();
            $cql->fill($request->datos)->save();
            $error = false;

            if ($inicia) {
                try {
                    $this->grabarCodigo();
                } catch (\Exception $e) {
                    $error = true;
                }
                if ($error) {
                    throw new \Exception("Error al grabar codigo");
                }
            }
            $data['codigo'] = $request->datos['codigo'];
            $data['identificacion'] = $request->datos['identificacion'];
            $data['puesto_trabajo_id'] = $request->paciente['puesto_trabajo_id'];
            $array_response = (new PacienteController())->grabarPaciente($request->paciente, $data, true);

            DB::connection('pgsql')->commit();
            $array_response['status'] = 200;
            $array_response['id'] = $cql->id;
            $array_response['datos'] = "Grabado exitosamente";
        } catch (\Exception $e) {
            DB::connection('pgsql_presidencia')->rollBack();
            $array_response['status'] = 404;
            $array_response['id'] = 0;
            $array_response['datos'] = $e->getMessage();
        }

        return response()->json($array_response, 200);
    }
    protected function grabarCodigo()
    {
        $cqlCodigo = new RegistroController();
        $datosCodigo = $cqlCodigo->consultaCodigoSiguiente();
        $cqlGrabarCodigo = CodigoHistoria::where('anio', date('Y'))->where('eliminado', false)->first();
        if (is_null($cqlGrabarCodigo)) {
            $cqlGrabarCodigo = new CodigoHistoria();
        }

        $cqlGrabarCodigo->anio = $datosCodigo['anio'];
        $cqlGrabarCodigo->incremental = $datosCodigo['incremental'];
        $cqlGrabarCodigo->codigo = $datosCodigo['codigo'];
        $cqlGrabarCodigo->fecha_inserta = date('Y-m-d H:i:s');
        $cqlGrabarCodigo->usuario_inserta = Auth::user()->name;
        $cqlGrabarCodigo->save();
    }
    public function datatableConsultaAtenciones($fecha_inicio, $fecha_fin, $paciente_id)
    {
        $diagnosticos_ = Diagnostico::select(
            DB::RAW("array_to_string(ARRAY_AGG(DISTINCT c.descripcion),'|,\r\n\t')")
        )
            ->whereColumn([
                'sc_historias_clinicas.diagnosticos.codigo' => 'atenciones_medicas.codigo',
                'sc_historias_clinicas.diagnosticos.identificacion' => 'atenciones_medicas.identificacion',
            ])
            ->join('sc_historias_clinicas.codigos_cie as c', 'c.id', 'sc_historias_clinicas.diagnosticos.codigo_cie_id');

        $data = AtencionMedica::select(
            'atenciones_medicas.id',
            'atenciones_medicas.tipo_evaluacion',
            'atenciones_medicas.codigo',
            'atenciones_medicas.usuario_inserta',
            'atenciones_medicas.fecha_inserta',
            'p.area',
            'p.identificacion',
            'p.motivo_consulta',
            DB::RAW("CONCAT(p.nombres,' ',p.apellidos) as funcionario")
        )
            ->addSelect(['diagnosticos_' => $diagnosticos_])
            ->join('sc_historias_clinicas.pacientes as p', 'p.codigo', 'atenciones_medicas.codigo')
            ->where('p.eliminado', false)
            ->whereDate('atenciones_medicas.fecha_inserta', '>=', $fecha_inicio)
            ->whereDate('atenciones_medicas.fecha_inserta', '<=', $fecha_fin);

        if ($paciente_id != "null") {
            $data = $data->where('p.identificacion', $paciente_id);
        }

        $data = $data->where('atenciones_medicas.eliminado', false)
            ->orderBy('atenciones_medicas.id', 'desc')
            ->groupBy(
                'atenciones_medicas.id',
                'atenciones_medicas.tipo_evaluacion',
                'atenciones_medicas.codigo',
                'atenciones_medicas.usuario_inserta',
                'atenciones_medicas.fecha_inserta',
                'p.motivo_consulta',
                'p.area',
                'p.identificacion',
                'p.nombres',
                'p.apellidos',
            )

            ->get();

        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                $url = 'editar/' . $row->id;
                $btn = ' <table style="width:100%;border:0px">';
                $btn .= '<tr><td style="padding: 2px;border:0px"><a class="btn btn-block btn-outline-primary" href="' . $url . '"><i class="fa fa-cog"></i>&nbsp;Detalle</a></td></tr>';
                $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Exportar" class="btn btn-block btn-outline-info btn-xs"  onclick="app.consultaExportar(\'' . $row->id . '\')"><i class="fa fa-solid fa-file-excel"></i>&nbsp;Reporte</button></td></tr>';
                if ($row->tipo_evaluacion != 'ATENCIONES DIARIAS') {
                    $btn .= '<tr><td style="padding: 2px;border:0px"><button title="ExportarCertificado" class="btn btn-block btn-outline-dark btn-xs"  onclick="app.consultaCertificado(\'' . $row->id . '\')"><i class="fa fa-file-excel"></i>&nbsp;Certificado</button></td></tr>';
                }
                $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger btn-xs"  onclick="app.eliminarHistoria(\'' . $row->id . '\')"><i class="fa fa-solid fa-trash"></i>&nbsp;Eliminar</button></td></tr>';
                $btn .= '</table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }
    public function exportar(request $request)
    {
        //consultamos el codigo
        $objGenerarExcel = new GA();
        $cql = AtencionMedica::select('codigo', 'identificacion', 'tipo_evaluacion')->where('id', $request->id)->first();
        $editar = (new RegistroController())->verificarEdicionDatos($cql->codigo);
        $nombre_archivo = $cql->identificacion . "_" . $request->id . ".xlsx";
        $path = "SIMED/REPORTES/" . $nombre_archivo;
        $validaGenera = false;

        $tipo = $cql->tipo_evaluacion;
        if ($editar) $validaGenera = $objGenerarExcel->generarExcel($request->id, $tipo);
        else {
            if (Storage::disk('local')->exists($path)) {
                $validaGenera = true;
                File::delete(public_path() . "/storage/" . $path);
                //$validaGenera = $objGenerarExcel->generarExcel($request->id, $tipo);
            }
            $validaGenera = $objGenerarExcel->generarExcel($request->id, $tipo);
        }
        if ($validaGenera) {

            $array_response['path'] = $path;
            $array_response['nombre_archivo'] = $nombre_archivo;
            $array_response['status'] = 200;
        } else {
            $array_response['status'] = 300;
        }
        return response()->json($array_response, 200);
    }


    public function exportarCertificado(request $request)
    {
        //consultamos el codigo
        $objGenerarExcel = new GA();
        $cql = AtencionMedica::select('codigo', 'identificacion', 'tipo_evaluacion')->where('id', $request->id)->first();
        $editar = (new RegistroController())->verificarEdicionDatos($cql->codigo);
        $nombre_archivo = $cql->tipo_evaluacion == 'RETIRO' ? $cql->identificacion . "_" . $request->id . "_CertificadoRetiro" . ".xlsx" : $cql->identificacion . "_" . $request->id . "_Certificado" . ".xlsx";
        $path = "SIMED/REPORTES/" . $nombre_archivo;
        $validaGenera = false;

        $tipo = 'CERTIFICADO';

        if ($editar) $validaGenera = $objGenerarExcel->generarExcel($request->id, $tipo);
        else {
            if (Storage::disk('local')->exists($path)) $validaGenera = true;
            else $validaGenera = $objGenerarExcel->generarExcel($request->id, $tipo);
        }
        if ($validaGenera) {

            $array_response['path'] = $path;
            $array_response['nombre_archivo'] = $nombre_archivo;
            $array_response['status'] = 200;
        } else {
            $array_response['status'] = 300;
        }
        return response()->json($array_response, 200);
    }


    public function getCargaDatosCiiu(request $request)
    {
        $letras_tildes = 'áéíóúÁÉÍÓÚäëïöüÄËÏÖÜ';
        $letras_sin_tildes = 'aeiouAEIOUaeiouAEIOU';
        $input = $request->all();
        if (!empty($input['query'])) {
            $busqueda = strtoupper($input['query']);
            if ($input['tipo'] == "ciiu_id") {
                $data = CodigoCiiu::select(
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
            if ($input['tipo'] == "ciiu_id") {
                $data = CodigoCiiu::select(
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
