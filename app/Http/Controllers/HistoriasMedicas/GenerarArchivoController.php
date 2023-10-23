<?php

namespace App\Http\Controllers\HistoriasMedicas;

use App\Core\Entities\modules\HistoriasMedicas\ActividadExtraEnfermedadActual;
use App\Core\Entities\modules\HistoriasMedicas\AntecedenteFamiliar;
use App\Core\Entities\modules\HistoriasMedicas\AntecedenteMedico;
use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;
use App\Core\Entities\modules\HistoriasMedicas\Discapacidad;
use App\Core\Entities\modules\HistoriasMedicas\FactorRiesgoLaboral;
use App\Core\Entities\Admin\parametro;

/* modelos */

use App\Core\Entities\TalentoHumano\Distributivo\Persona;
use App\Http\Controllers\Controller;
use App\Core\Entities\modules\HistoriasMedicas\AntecedenteGinecoObstetrico;
use App\Core\Entities\modules\HistoriasMedicas\ExamenGinecoObstetrico;
use App\Core\Entities\modules\HistoriasMedicas\AntecedenteReproductivoMasculino;
use App\Core\Entities\modules\HistoriasMedicas\AntecedentesAccidentesTrabajo;
use App\Core\Entities\modules\HistoriasMedicas\AntecedentesEnfermedadesProfesionales;
use App\Core\Entities\modules\HistoriasMedicas\ConstanteVital;
use App\Core\Entities\modules\HistoriasMedicas\ExamenReproductivoMasculino;
use App\Core\Entities\modules\HistoriasMedicas\HabitosToxicos;
use App\Core\Entities\modules\HistoriasMedicas\EstiloVida;
use App\Core\Entities\modules\HistoriasMedicas\AntecedentesTrabajo;
use App\Core\Entities\modules\HistoriasMedicas\AptitudMedica;
use App\Core\Entities\modules\HistoriasMedicas\CodigoCie;
use App\Core\Entities\modules\HistoriasMedicas\Diagnostico;
use App\Core\Entities\modules\HistoriasMedicas\EvaluacionMedicaRetiro;
use App\Core\Entities\modules\HistoriasMedicas\ExamenFisicoRegional;
use App\Core\Entities\modules\HistoriasMedicas\ExamenGeneralEspecifico;
use App\Core\Entities\modules\HistoriasMedicas\Recomendacion;
use App\Core\Entities\modules\HistoriasMedicas\RevisionOrgano;
use Illuminate\Support\Facades\Storage;

use Auth;

/*EDITAR EXCEL */

use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\IOFactory;

//use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

/*FIN EDITAR EXCEL*/

class GenerarArchivoController extends Controller
{
    public function generarExcel($id, $tipo)
    {
        switch ($tipo) {
            case 'INGRESO':
                $data = AtencionMedica::select(
                    'atenciones_medicas.id',
                    'atenciones_medicas.codigo',
                    'atenciones_medicas.identificacion',
                    'atenciones_medicas.tipo_evaluacion',
                    'atenciones_medicas.institucion',
                    'atenciones_medicas.ruc',
                    'atenciones_medicas.codigo_ciiu',
                    'atenciones_medicas.establecimiento_salud',
                    'atenciones_medicas.historia_clinica',
                    'atenciones_medicas.numero_archivo',
                    'p.motivo_consulta',
                    'p.apellidos',
                    'p.nombres',
                    'p.genero',
                    'p.edad',
                    'p.religion',
                    'p.tipo_sangre',
                    'p.lateralidad',
                    'p.orientacion_sexual',
                    'p.identidad_genero',
                    'p.fecha_ingreso',
                    'p.puesto_trabajo_ciuo',
                    'ciuo.descripcion as puesto_trabajo',
                    'ciiu.descripcion as ciiu',
                    'p.area',
                    'p.actividad_relevante',
                    'p.cargo'
                )
                    ->leftjoin('sc_historias_clinicas.pacientes as p', 'p.codigo', 'atenciones_medicas.codigo')
                    ->leftjoin('sc_historias_clinicas.codigos_ciuo as ciuo', 'ciuo.id', 'p.puesto_trabajo_ciuo')
                    ->leftjoin('sc_historias_clinicas.codigos_ciiu as ciiu', 'ciiu.id', 'atenciones_medicas.codigo_ciiu')
                    ->where('p.eliminado', false)
                    ->where('atenciones_medicas.eliminado', false)
                    ->where('atenciones_medicas.id', $id)
                    ->first();
                $dataDiscapacidad = [];
                $dataClinicos = [];
                $dataAntecedenteGineco = [];
                if (!is_null($data)) {
                    $cqlPersona_id = Persona::select('id')->where('identificacion', $data->identificacion)->where('eliminado', false)->where('estado', 'ACT')->orderBy('id', 'desc')->first()->id;
                    $dataDiscapacidad = Discapacidad::select('nombre', 'porcentaje',)->where('codigo', $data->codigo)->where('persona_id', $cqlPersona_id)->get()->toArray();
                    $dataClinicos = AntecedenteMedico::select('tipo', 'descripcion')->where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->get()->toArray();
                    $dataAntecedenteGineco = AntecedenteGinecoObstetrico::where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->first();
                    $dataExamenGineco = ExamenGinecoObstetrico::where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->get()->toArray();
                    $dataAntecedenteReproMasculino = AntecedenteReproductivoMasculino::where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->first();
                    $dataExamenReproMasculino = ExamenReproductivoMasculino::where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->get()->toArray();
                    $dataHabitos = HabitosToxicos::where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->get()->toArray();
                    $dataEstiloVida = EstiloVida::where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->get()->toArray();
                    $dataAntecedentes = AntecedenteFamiliar::select('detalle', 'descripcion')->where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->get()->toArray();
                    $dataFactoresRiesgosPuestos = FactorRiesgoLaboral::select('puesto_trabajo', 'actividades', 'medidas_preventivas', 'detalles')->where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataActividadesExtraLaborales = ActividadExtraEnfermedadActual::select('descripcion')->where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->where('tipo', 'ACTIVIDAD')->orderBy('id')->get()->toArray();
                    $dataEnfermedadActual = ActividadExtraEnfermedadActual::select('descripcion')->where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->where('tipo', 'ENFERMEDAD')->orderBy('id')->get()->toArray();
                    $dataAntecedenteTrabajo = AntecedentesTrabajo::where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataAntecedenteAccidenteTrabajo = AntecedentesAccidentesTrabajo::select(
                        'calificado_accidente',
                        'especificar_accidente',
                        'fecha_accidente',
                        'observaciones_accidente'
                    )
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataAntecedenteEnfermedadProfesionales = AntecedentesEnfermedadesProfesionales::select(
                        'calificado_enfermedad',
                        'especificar_enfermedad',
                        'fecha_enfermedad',
                        'observaciones_enfermedad'
                    )
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataRevisionActual = RevisionOrgano::select('descripcion', 'detalle')->where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataConstantesVitales = ConstanteVital::select(
                        'presion_arterial',
                        'temperatura',
                        'frecuencia_cardiaca',
                        'saturacion_oxigeno',
                        'frecuencia_respiratoria',
                        'peso',
                        'talla',
                        'indice_masa_corporal',
                        'perimetro_abdominal'
                    )
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->get()->toArray();
                    $dataExamenenFisicioRegional = ExamenFisicoRegional::select('detalles', 'descripcion')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataExamenenGeneralEspecifico = ExamenGeneralEspecifico::select('descripcion', 'fecha', 'resultados')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();

                    $dataDiagnostico = Diagnostico::select('descripcion', 'codigo_cie_id', 'tipo')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();

                    $dataAptitudMedica = AptitudMedica::select('aptitud', 'observacion', 'limitacion')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();

                    $dataRecomendacion = Recomendacion::select('recomendacion')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();

                    return $this->formarExcel(
                        $tipo,
                        $data,
                        $dataDiscapacidad,
                        $dataClinicos,
                        $dataAntecedenteGineco,
                        $dataExamenGineco,
                        $dataAntecedenteReproMasculino,
                        $dataExamenReproMasculino,
                        $dataHabitos,
                        $dataEstiloVida,
                        $dataAntecedentes,
                        $dataFactoresRiesgosPuestos,
                        $dataActividadesExtraLaborales,
                        $dataEnfermedadActual,
                        $dataAntecedenteTrabajo,
                        $dataRevisionActual,
                        $dataConstantesVitales,
                        $dataAntecedenteAccidenteTrabajo,
                        $dataAntecedenteEnfermedadProfesionales,
                        $dataExamenenFisicioRegional,
                        $dataExamenenGeneralEspecifico,
                        $dataDiagnostico,
                        $dataAptitudMedica,
                        $dataRecomendacion
                    );
                } else
                    return false;
                break;
            case 'RETIRO':
                $data = AtencionMedica::select(
                    'atenciones_medicas.id',
                    'atenciones_medicas.codigo',
                    'atenciones_medicas.identificacion',
                    'atenciones_medicas.tipo_evaluacion',
                    'atenciones_medicas.institucion',
                    'atenciones_medicas.ruc',
                    'atenciones_medicas.codigo_ciiu',
                    'atenciones_medicas.establecimiento_salud',
                    'atenciones_medicas.historia_clinica',
                    'atenciones_medicas.numero_archivo',
                    'p.motivo_consulta',
                    'p.apellidos',
                    'p.nombres',
                    'p.genero',
                    'p.edad',
                    'p.religion',
                    'p.tipo_sangre',
                    'p.lateralidad',
                    'p.orientacion_sexual',
                    'p.identidad_genero',
                    'p.fecha_ingreso',
                    'p.puesto_trabajo_ciuo',
                    'ciuo.descripcion as puesto_trabajo',
                    'ciiu.descripcion as ciiu',
                    'p.area',
                    'p.actividad_relevante',
                    'p.tiempo_meses',
                    'p.factores_riesgo',
                    'p.fecha_salida',
                    'p.cargo'
                )
                    ->leftjoin('sc_historias_clinicas.pacientes as p', 'p.codigo', 'atenciones_medicas.codigo')
                    ->leftjoin('sc_historias_clinicas.codigos_ciuo as ciuo', 'ciuo.id', 'p.puesto_trabajo_ciuo')
                    ->leftjoin('sc_historias_clinicas.codigos_ciiu as ciiu', 'ciiu.id', 'atenciones_medicas.codigo_ciiu')
                    ->where('p.eliminado', false)
                    ->where('atenciones_medicas.eliminado', false)
                    ->where('atenciones_medicas.id', $id)
                    ->first();
                $dataDiscapacidad = [];
                $dataClinicos = [];
                $dataAntecedenteGineco = [];
                if (!is_null($data)) {
                    $cqlPersona_id = Persona::select('id')->where('identificacion', $data->identificacion)->where('eliminado', false)->where('estado', 'ACT')->orderBy('id', 'desc')->first()->id;
                    $dataDiscapacidad = Discapacidad::select('nombre', 'porcentaje',)->where('codigo', $data->codigo)->where('persona_id', $cqlPersona_id)->get()->toArray();
                    $dataClinicos = AntecedenteMedico::select('tipo', 'descripcion')->where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->get()->toArray();
                    $dataAntecedenteTrabajo = AntecedentesTrabajo::where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataAntecedenteAccidenteTrabajo = AntecedentesAccidentesTrabajo::select(
                        'calificado_accidente',
                        'especificar_accidente',
                        'fecha_accidente',
                        'observaciones_accidente'
                    )
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataAntecedenteEnfermedadProfesionales = AntecedentesEnfermedadesProfesionales::select(
                        'calificado_enfermedad',
                        'especificar_enfermedad',
                        'fecha_enfermedad',
                        'observaciones_enfermedad'
                    )
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataConstantesVitales = ConstanteVital::select(
                        'presion_arterial',
                        'temperatura',
                        'frecuencia_cardiaca',
                        'saturacion_oxigeno',
                        'frecuencia_respiratoria',
                        'peso',
                        'talla',
                        'indice_masa_corporal',
                        'perimetro_abdominal'
                    )
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->get()->toArray();
                    $dataExamenenFisicioRegional = ExamenFisicoRegional::select('detalles', 'descripcion')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataExamenenGeneralEspecifico = ExamenGeneralEspecifico::select('descripcion', 'fecha', 'resultados')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();

                    $dataDiagnostico = Diagnostico::select('descripcion', 'codigo_cie_id', 'tipo')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();

                    $dataEvaluacionMedicaRetiro = EvaluacionMedicaRetiro::select('evaluacion_realizada', 'observaciones')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();

                    $dataRecomendacion = Recomendacion::select('recomendacion')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();

                    return $this->formarExcelRetiro(
                        $tipo,
                        $data,
                        $dataDiscapacidad,
                        $dataClinicos,
                        $dataAntecedenteTrabajo,
                        $dataConstantesVitales,
                        $dataAntecedenteAccidenteTrabajo,
                        $dataAntecedenteEnfermedadProfesionales,
                        $dataExamenenFisicioRegional,
                        $dataExamenenGeneralEspecifico,
                        $dataDiagnostico,
                        $dataEvaluacionMedicaRetiro,
                        $dataRecomendacion
                    );
                } else
                    return false;
                break;
            case 'REINGRESO':
                $data = AtencionMedica::select(
                    'atenciones_medicas.id',
                    'atenciones_medicas.codigo',
                    'atenciones_medicas.identificacion',
                    'atenciones_medicas.tipo_evaluacion',
                    'atenciones_medicas.institucion',
                    'atenciones_medicas.ruc',
                    'atenciones_medicas.codigo_ciiu',
                    'atenciones_medicas.establecimiento_salud',
                    'atenciones_medicas.historia_clinica',
                    'atenciones_medicas.numero_archivo',
                    'p.motivo_consulta',
                    'p.apellidos',
                    'p.nombres',
                    'p.genero',
                    'p.edad',
                    'p.religion',
                    'p.tipo_sangre',
                    'p.lateralidad',
                    'p.orientacion_sexual',
                    'p.identidad_genero',
                    'p.fecha_ingreso',
                    'p.puesto_trabajo_ciuo',
                    'ciuo.descripcion as puesto_trabajo',
                    'ciiu.descripcion as ciiu',
                    'p.area',
                    'p.actividad_relevante',
                    'p.tiempo_meses',
                    'p.factores_riesgo',
                    'p.fecha_salida',
                    'p.causa_salida',
                    'p.cargo'
                )
                    ->leftjoin('sc_historias_clinicas.pacientes as p', 'p.codigo', 'atenciones_medicas.codigo')
                    ->leftjoin('sc_historias_clinicas.codigos_ciuo as ciuo', 'ciuo.id', 'p.puesto_trabajo_ciuo')
                    ->leftjoin('sc_historias_clinicas.codigos_ciiu as ciiu', 'ciiu.id', 'atenciones_medicas.codigo_ciiu')
                    ->where('p.eliminado', false)
                    ->where('atenciones_medicas.eliminado', false)
                    ->where('atenciones_medicas.id', $id)
                    ->first();
                $dataDiscapacidad = [];
                $dataClinicos = [];
                $dataAntecedenteGineco = [];
                if (!is_null($data)) {
                    $cqlPersona_id = Persona::select('id')->where('identificacion', $data->identificacion)->where('eliminado', false)->where('estado', 'ACT')->orderBy('id', 'desc')->first()->id;
                    $dataDiscapacidad = Discapacidad::select('nombre', 'porcentaje',)->where('codigo', $data->codigo)->where('persona_id', $cqlPersona_id)->get()->toArray();
                    $dataClinicos = AntecedenteMedico::select('tipo', 'descripcion')->where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->get()->toArray();
                    $dataAntecedenteTrabajo = AntecedentesTrabajo::where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataAntecedenteAccidenteTrabajo = AntecedentesAccidentesTrabajo::select(
                        'calificado_accidente',
                        'especificar_accidente',
                        'fecha_accidente',
                        'observaciones_accidente'
                    )
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataAntecedenteEnfermedadProfesionales = AntecedentesEnfermedadesProfesionales::select(
                        'calificado_enfermedad',
                        'especificar_enfermedad',
                        'fecha_enfermedad',
                        'observaciones_enfermedad'
                    )
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataConstantesVitales = ConstanteVital::select(
                        'presion_arterial',
                        'temperatura',
                        'frecuencia_cardiaca',
                        'saturacion_oxigeno',
                        'frecuencia_respiratoria',
                        'peso',
                        'talla',
                        'indice_masa_corporal',
                        'perimetro_abdominal'
                    )
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->get()->toArray();
                    $dataExamenenFisicioRegional = ExamenFisicoRegional::select('detalles', 'descripcion')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataExamenenGeneralEspecifico = ExamenGeneralEspecifico::select('descripcion', 'fecha', 'resultados')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();

                    $dataDiagnostico = Diagnostico::select('descripcion', 'codigo_cie_id', 'tipo')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();

                    $dataEvaluacionMedicaRetiro = EvaluacionMedicaRetiro::select('evaluacion_realizada', 'observaciones')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();

                    $dataRecomendacion = Recomendacion::select('recomendacion')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();

                    $dataEnfermedadActual = ActividadExtraEnfermedadActual::select('descripcion')->where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->where('tipo', 'ENFERMEDAD')->orderBy('id')->get()->toArray();

                    $dataAptitudMedica = AptitudMedica::select('aptitud', 'observacion', 'limitacion')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();

                    $dataRecomendacion = Recomendacion::select('recomendacion')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();

                    return $this->formarExcelReingreso(
                        $tipo,
                        $data,
                        $dataDiscapacidad,
                        $dataEnfermedadActual,
                        $dataClinicos,
                        $dataAntecedenteTrabajo,
                        $dataConstantesVitales,
                        $dataAntecedenteAccidenteTrabajo,
                        $dataAntecedenteEnfermedadProfesionales,
                        $dataExamenenFisicioRegional,
                        $dataExamenenGeneralEspecifico,
                        $dataDiagnostico,
                        $dataEvaluacionMedicaRetiro,
                        $dataAptitudMedica,
                        $dataRecomendacion
                    );
                } else
                    return false;
                break;
            case 'ATENCIONES DIARIAS':
                $data = AtencionMedica::select(
                    'atenciones_medicas.id',
                    'atenciones_medicas.codigo',
                    'atenciones_medicas.identificacion',
                    'atenciones_medicas.tipo_evaluacion',
                    'atenciones_medicas.institucion',
                    'atenciones_medicas.ruc',
                    'atenciones_medicas.codigo_ciiu',
                    'atenciones_medicas.establecimiento_salud',
                    'atenciones_medicas.historia_clinica',
                    'atenciones_medicas.numero_archivo',
                    'p.motivo_consulta',
                    'p.apellidos',
                    'p.nombres',
                    'p.genero',
                    'p.edad',
                    'p.religion',
                    'p.tipo_sangre',
                    'p.lateralidad',
                    'p.orientacion_sexual',
                    'p.identidad_genero',
                    'p.fecha_ingreso',
                    'p.puesto_trabajo_ciuo',
                    'ciuo.descripcion as puesto_trabajo',
                    'ciiu.descripcion as ciiu',
                    'p.area',
                    'p.actividad_relevante',
                    'p.primer_nombre',
                    'p.segundo_nombre',
                    'p.primer_apellido',
                    'p.segundo_apellido',
                    'p.motivo_consulta',
                    'p.cargo'
                )
                    ->leftjoin('sc_historias_clinicas.pacientes as p', 'p.codigo', 'atenciones_medicas.codigo')
                    ->leftjoin('sc_historias_clinicas.codigos_ciuo as ciuo', 'ciuo.id', 'p.puesto_trabajo_ciuo')
                    ->leftjoin('sc_historias_clinicas.codigos_ciiu as ciiu', 'ciiu.id', 'atenciones_medicas.codigo_ciiu')
                    ->where('p.eliminado', false)
                    ->where('atenciones_medicas.eliminado', false)
                    ->where('atenciones_medicas.id', $id)
                    ->first();
                $dataDiscapacidad = [];
                if (!is_null($data)) {
                    $cqlPersona_id = Persona::select('id')->where('identificacion', $data->identificacion)->where('eliminado', false)->where('estado', 'ACT')->orderBy('id', 'desc')->first()->id;
                    $dataDiscapacidad = Discapacidad::select('nombre', 'porcentaje',)->where('codigo', $data->codigo)->where('persona_id', $cqlPersona_id)->get()->toArray();
                    $dataEnfermedadActual = ActividadExtraEnfermedadActual::select('descripcion')->where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->where('tipo', 'ENFERMEDAD')->orderBy('id')->get()->toArray();
                    $dataExamenenFisicioRegional = ExamenFisicoRegional::select('detalles', 'descripcion')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataDiagnostico = Diagnostico::select('descripcion', 'codigo_cie_id', 'tipo')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();

                    $dataRecomendacion = Recomendacion::select('recomendacion')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    return $this->formarExcelAtencionDiaria(
                        $tipo,
                        $data,
                        $dataDiscapacidad,
                        $dataEnfermedadActual,
                        $dataExamenenFisicioRegional,
                        $dataDiagnostico,
                        $dataRecomendacion
                    );
                } else
                    return false;
                break;
            case 'CERTIFICADO':
                $data = AtencionMedica::select(
                    'atenciones_medicas.id',
                    'atenciones_medicas.codigo',
                    'atenciones_medicas.identificacion',
                    'atenciones_medicas.tipo_evaluacion',
                    'atenciones_medicas.institucion',
                    'atenciones_medicas.ruc',
                    'atenciones_medicas.codigo_ciiu',
                    'atenciones_medicas.establecimiento_salud',
                    'atenciones_medicas.historia_clinica',
                    'atenciones_medicas.numero_archivo',
                    'p.motivo_consulta',
                    'p.apellidos',
                    'p.nombres',
                    'p.genero',
                    'p.edad',
                    'p.religion',
                    'p.tipo_sangre',
                    'p.lateralidad',
                    'p.orientacion_sexual',
                    'p.identidad_genero',
                    'p.fecha_ingreso',
                    'p.puesto_trabajo_ciuo',
                    'ciuo.descripcion as puesto_trabajo',
                    'ciiu.descripcion as ciiu',
                    'p.area',
                    'p.actividad_relevante',
                    'p.primer_nombre',
                    'p.segundo_nombre',
                    'p.primer_apellido',
                    'p.segundo_apellido',
                    'p.cargo'
                )
                    ->leftjoin('sc_historias_clinicas.pacientes as p', 'p.codigo', 'atenciones_medicas.codigo')
                    ->leftjoin('sc_historias_clinicas.codigos_ciuo as ciuo', 'ciuo.id', 'p.puesto_trabajo_ciuo')
                    ->leftjoin('sc_historias_clinicas.codigos_ciiu as ciiu', 'ciiu.id', 'atenciones_medicas.codigo_ciiu')
                    ->where('p.eliminado', false)
                    ->where('atenciones_medicas.eliminado', false)
                    ->where('atenciones_medicas.id', $id)
                    ->first();
                $dataDiscapacidad = [];
                if (!is_null($data)) {
                    $cqlPersona_id = Persona::select('id')->where('identificacion', $data->identificacion)->where('eliminado', false)->where('estado', 'ACT')->orderBy('id', 'desc')->first()->id;
                    $dataDiscapacidad = Discapacidad::select('nombre', 'porcentaje',)->where('codigo', $data->codigo)->where('persona_id', $cqlPersona_id)->get()->toArray();
                    $dataAptitudMedica = AptitudMedica::select('aptitud', 'observacion', 'limitacion')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataRecomendacion = Recomendacion::select('recomendacion')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataEvaluacionMedicaRetiro = EvaluacionMedicaRetiro::select('evaluacion_realizada', 'observaciones', 'condicion_diagnostico', 'salud_relacionada')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    return $this->formarExcelCertificado(
                        $tipo,
                        $data,
                        $dataDiscapacidad,
                        $dataAptitudMedica,
                        $dataEvaluacionMedicaRetiro,
                        $dataRecomendacion
                    );
                } else
                    return false;
                break;
            default:
                $data = AtencionMedica::select(
                    'atenciones_medicas.id',
                    'atenciones_medicas.codigo',
                    'atenciones_medicas.identificacion',
                    'atenciones_medicas.tipo_evaluacion',
                    'atenciones_medicas.institucion',
                    'atenciones_medicas.ruc',
                    'atenciones_medicas.codigo_ciiu',
                    'atenciones_medicas.establecimiento_salud',
                    'atenciones_medicas.historia_clinica',
                    'atenciones_medicas.numero_archivo',
                    'p.motivo_consulta',
                    'p.apellidos',
                    'p.nombres',
                    'p.genero',
                    'p.edad',
                    'p.religion',
                    'p.tipo_sangre',
                    'p.lateralidad',
                    'p.orientacion_sexual',
                    'p.identidad_genero',
                    'p.fecha_ingreso',
                    'p.puesto_trabajo_ciuo',
                    'ciuo.descripcion as puesto_trabajo',
                    'ciiu.descripcion as ciiu',
                    'p.area',
                    'p.actividad_relevante',
                    'p.cargo'
                )
                    ->leftjoin('sc_historias_clinicas.pacientes as p', 'p.codigo', 'atenciones_medicas.codigo')
                    ->leftjoin('sc_historias_clinicas.codigos_ciuo as ciuo', 'ciuo.id', 'p.puesto_trabajo_ciuo')
                    ->leftjoin('sc_historias_clinicas.codigos_ciiu as ciiu', 'ciiu.id', 'atenciones_medicas.codigo_ciiu')
                    ->where('p.eliminado', false)
                    ->where('atenciones_medicas.eliminado', false)
                    ->where('atenciones_medicas.id', $id)
                    ->first();
                $dataDiscapacidad = [];
                $dataClinicos = [];
                $dataAntecedenteGineco = [];
                if (!is_null($data)) {
                    $cqlPersona_id = Persona::select('id')->where('identificacion', $data->identificacion)->where('eliminado', false)->where('estado', 'ACT')->orderBy('id', 'desc')->first()->id;
                    $dataDiscapacidad = Discapacidad::select('nombre', 'porcentaje',)->where('codigo', $data->codigo)->where('persona_id', $cqlPersona_id)->get()->toArray();
                    $dataClinicos = AntecedenteMedico::select('tipo', 'descripcion')->where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->get()->toArray();
                    $dataAntecedenteGineco = AntecedenteGinecoObstetrico::where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->first();
                    $dataExamenGineco = ExamenGinecoObstetrico::where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->get()->toArray();
                    $dataAntecedenteReproMasculino = AntecedenteReproductivoMasculino::where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->first();
                    $dataExamenReproMasculino = ExamenReproductivoMasculino::where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->get()->toArray();
                    $dataHabitos = HabitosToxicos::where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->get()->toArray();
                    $dataEstiloVida = EstiloVida::where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->get()->toArray();
                    $dataAntecedentes = AntecedenteFamiliar::select('detalle', 'descripcion')->where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->get()->toArray();
                    $dataFactoresRiesgosPuestos = FactorRiesgoLaboral::select('puesto_trabajo', 'actividades', 'medidas_preventivas', 'detalles')->where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataActividadesExtraLaborales = ActividadExtraEnfermedadActual::select('descripcion')->where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->where('tipo', 'ACTIVIDAD')->orderBy('id')->get()->toArray();
                    $dataEnfermedadActual = ActividadExtraEnfermedadActual::select('descripcion')->where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->where('tipo', 'ENFERMEDAD')->orderBy('id')->get()->toArray();
                    $dataAntecedenteTrabajo = AntecedentesTrabajo::where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataAntecedenteAccidenteTrabajo = AntecedentesAccidentesTrabajo::select(
                        'calificado_accidente',
                        'especificar_accidente',
                        'fecha_accidente',
                        'observaciones_accidente'
                    )
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataAntecedenteEnfermedadProfesionales = AntecedentesEnfermedadesProfesionales::select(
                        'calificado_enfermedad',
                        'especificar_enfermedad',
                        'fecha_enfermedad',
                        'observaciones_enfermedad'
                    )
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataRevisionActual = RevisionOrgano::select('descripcion', 'detalle')->where('eliminado', false)->where('identificacion', $data->identificacion)->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataConstantesVitales = ConstanteVital::select(
                        'presion_arterial',
                        'temperatura',
                        'frecuencia_cardiaca',
                        'saturacion_oxigeno',
                        'frecuencia_respiratoria',
                        'peso',
                        'talla',
                        'indice_masa_corporal',
                        'perimetro_abdominal'
                    )
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->get()->toArray();
                    $dataExamenenFisicioRegional = ExamenFisicoRegional::select('detalles', 'descripcion')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();
                    $dataExamenenGeneralEspecifico = ExamenGeneralEspecifico::select('descripcion', 'fecha', 'resultados')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();

                    $dataDiagnostico = Diagnostico::select('descripcion', 'codigo_cie_id', 'tipo')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();

                    $dataAptitudMedica = AptitudMedica::select('aptitud', 'observacion', 'limitacion')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();

                    $dataRecomendacion = Recomendacion::select('recomendacion')
                        ->where('eliminado', false)
                        ->where('identificacion', $data->identificacion)
                        ->where('codigo', $data->codigo)->orderBy('id')->get()->toArray();

                    return $this->formarExcelPeriodico(
                        $tipo,
                        $data,
                        $dataDiscapacidad,
                        $dataClinicos,
                        $dataAntecedenteGineco,
                        $dataExamenGineco,
                        $dataAntecedenteReproMasculino,
                        $dataExamenReproMasculino,
                        $dataHabitos,
                        $dataEstiloVida,
                        $dataAntecedentes,
                        $dataFactoresRiesgosPuestos,
                        $dataActividadesExtraLaborales,
                        $dataEnfermedadActual,
                        $dataAntecedenteTrabajo,
                        $dataRevisionActual,
                        $dataConstantesVitales,
                        $dataAntecedenteAccidenteTrabajo,
                        $dataAntecedenteEnfermedadProfesionales,
                        $dataExamenenFisicioRegional,
                        $dataExamenenGeneralEspecifico,
                        $dataDiagnostico,
                        $dataAptitudMedica,
                        $dataRecomendacion
                    );
                } else
                    return false;
                break;
        }
        //consulta de los datos

    }

    private function formarExcelAtencionDiaria(
        $tipo,
        $data,
        $dataDiscapacidad,
        $dataEnfermedadActual,
        $dataExamenenFisicioRegional,
        $dataDiagnostico,
        $dataRecomendacion
    )
    {
        $url = 'storage/SIMED/FORMATOS/atencion_diaria.xlsx';
        $reader = IOFactory::createReader("Xlsx");
        $spread = $reader->load($url);
        $spread->setActiveSheetIndex(0); //carga la primera hoja
        $sheet_1 = $spread->getActiveSheet(); //activa la primera hoja

        $this->datosCabecera($data, $sheet_1, $tipo);
        $this->datosPacientes($data, $dataDiscapacidad, $sheet_1, $tipo);
        $this->enfermedadActual($dataEnfermedadActual, $sheet_1, $tipo);
        $this->examenFisicoRegional($dataExamenenFisicioRegional, $sheet_1, $tipo);
        $this->diagnostico($dataDiagnostico, $sheet_1, $tipo);
        //$this->recomendacion($dataRecomendacion, $sheet_1, $tipo);
        //$this->datosProfesional($data, $sheet_1, $tipo);

        /* ESCRIBIR LOS CAMPOS */
        $writer = IOFactory::createWriter($spread, 'Xlsx');
        /* GRABADOS DE ARCHIVOS */
        $nombreFile = $data->identificacion . "_" . $data->id . ".xlsx";
        $writer->save("storage/SIMED/REPORTES/" . $nombreFile);
        return true;
    }

    private function formarExcelCertificado(
        $tipo,
        $data,
        $dataDiscapacidad,
        $dataAptitudMedica,
        $dataEvaluacionMedicaRetiro,
        $dataRecomendacion
    )
    {
        //dd($data, $tipo);
        switch ($data->tipo_evaluacion) {
            case 'RETIRO':
                $url = 'storage/SIMED/FORMATOS/certificado_retiro.xlsx';
                $reader = IOFactory::createReader("Xlsx");
                $spread = $reader->load($url);
                $spread->setActiveSheetIndex(0); //carga la primera hoja
                $sheet_1 = $spread->getActiveSheet(); //activa la primera hoja

                $this->datosCabecera($data, $sheet_1, $tipo);
                $this->datosPacientes($data, $dataDiscapacidad, $sheet_1, $tipo);
                $this->datosGenerales($data, $sheet_1);

                $this->evaluacionMedicaRetiro($dataEvaluacionMedicaRetiro, $sheet_1, $tipo);
                $this->recomendacionRetiro($dataRecomendacion, $sheet_1, $tipo);
                $this->datosProfesional($data, $sheet_1, $tipo);

                /* ESCRIBIR LOS CAMPOS */
                $writer = IOFactory::createWriter($spread, 'Xlsx');
                /* GRABADOS DE ARCHIVOS */
                $nombreFile = $data->identificacion . "_" . $data->id . "_CertificadoRetiro" . ".xlsx";
                $writer->save("storage/SIMED/REPORTES/" . $nombreFile);
                return true;
                break;

            default:
                $url = 'storage/SIMED/FORMATOS/certificado.xlsx';
                $reader = IOFactory::createReader("Xlsx");
                $spread = $reader->load($url);
                $spread->setActiveSheetIndex(0); //carga la primera hoja
                $sheet_1 = $spread->getActiveSheet(); //activa la primera hoja

                $this->datosCabecera($data, $sheet_1, $tipo);
                $this->datosPacientes($data, $dataDiscapacidad, $sheet_1, $tipo);
                $this->datosGenerales($data, $sheet_1);
                $this->aptitudMedica($dataAptitudMedica, $sheet_1, $tipo);
                //$this->evaluacionMedicaRetiro($dataEvaluacionMedicaRetiro, $sheet_1, $tipo);
                $this->recomendacion($dataRecomendacion, $sheet_1, $tipo);
                $this->datosProfesional($data, $sheet_1, $tipo);

                /* ESCRIBIR LOS CAMPOS */
                $writer = IOFactory::createWriter($spread, 'Xlsx');
                /* GRABADOS DE ARCHIVOS */
                $nombreFile = $data->identificacion . "_" . $data->id . "_Certificado" . ".xlsx";
                $writer->save("storage/SIMED/REPORTES/" . $nombreFile);
                return true;
                break;
        }
    }

    private function formarExcel(
        $tipo,
        $data,
        $discapacidad,
        $dataClinicos,
        $dataAntecedenteGineco,
        $dataExamenGineco,
        $dataAntecedenteReproMasculino,
        $dataExamenReproMasculino,
        $dataHabitos,
        $dataEstiloVida,
        $dataAntecedentes,
        $dataFactoresRiesgosPuestos,
        $dataActividadesExtraLaborales,
        $dataEnfermedadActual,
        $dataAntecedenteTrabajo,
        $dataRevisionActual,
        $dataConstantesVitales,
        $dataAntecedenteAccidenteTrabajo,
        $dataAntecedenteEnfermedadProfesionales,
        $dataExamenenFisicioRegional,
        $dataExamenenGeneralEspecifico,
        $dataDiagnostico,
        $dataAptitudMedica,
        $dataRecomendacion
    )
    {
        /* CARGA DE FORMATO */
        $url = 'storage/SIMED/FORMATOS/ingreso.xlsx';
        $reader = IOFactory::createReader("Xlsx");
        $spread = $reader->load($url);
        $spread->setActiveSheetIndex(0); //carga la primera hoja
        $sheet_1 = $spread->getActiveSheet(); //activa la primera hoja
        /* SET CAMPOS */
        $this->datosCabecera($data, $sheet_1, $tipo);
        $this->datosPacientes($data, $discapacidad, $sheet_1, $tipo); //A. DATOS DEL ESTABLECIMIENTO - EMPRESA Y USUARIO b. MOTIVO CONSULTA
        $this->antecedentesPersonales($dataClinicos, $sheet_1, $tipo); //C. ANTECEDENTES PERSONALES
        $this->antecedentesGineco($dataAntecedenteGineco, $sheet_1, $tipo); //C. ANTECEDENTES GINECO OBSTETRICO
        $this->examenesGineco($dataExamenGineco, $sheet_1, $tipo); //C. EXAMENES GINECO
        $this->antecedenteReproMasculino($dataAntecedenteReproMasculino, $sheet_1, $tipo); //C. ANTECEDENTES REPRODUCTIVOS MASCULINOS
        $this->examenesReproMasculino($dataExamenReproMasculino, $sheet_1, $tipo); //C. EXAMENES REPRODUCTIVOS MASCULINOS
        $this->habitosToxicos($dataHabitos, $sheet_1, $tipo); //C. HABITOS TOXICOS
        $this->estiloVida($dataEstiloVida, $sheet_1, $tipo); //C. HABITOS TOXICOS
        $this->antecedenteTrabajo($dataAntecedenteTrabajo, $sheet_1, $tipo);
        $this->antecedenteAccidente($dataAntecedenteAccidenteTrabajo, $sheet_1, $tipo);
        $this->antecedenteEnfermedad($dataAntecedenteEnfermedadProfesionales, $sheet_1, $tipo);

        /**
         * Cargar datos a la segunda hoja
         */
        $spread->setActiveSheetIndex(1);
        $sheet_2 = $spread->getActiveSheet();
        $this->datosCabecera($data, $sheet_2, $tipo);
        $this->antecedentesFamiliares($dataAntecedentes, $sheet_2, $tipo); //E. ANTECEDENTES FAMILIARES
        $this->factoresRiesgosPuestos($dataFactoresRiesgosPuestos, $sheet_2, $tipo); //E. ANTECEDENTES FAMILIARES
        $this->actividadesExtraLaborales($dataActividadesExtraLaborales, $sheet_2, $tipo);
        $this->enfermedadActual($dataEnfermedadActual, $sheet_2, $tipo);
        $this->revisionActual($dataRevisionActual, $sheet_2, $tipo);
        $this->constantesVitales($dataConstantesVitales, $sheet_2, $tipo);

        /**
         * Cargar datis ena la tercera hoja
         */
        $spread->setActiveSheetIndex(2);
        $sheet_3 = $spread->getActiveSheet();
        $this->datosCabecera($data, $sheet_3, $tipo);
        $this->examenFisicoRegional($dataExamenenFisicioRegional, $sheet_3, $tipo);
        $this->examenGeneralEspecifico($dataExamenenGeneralEspecifico, $sheet_3, $tipo);
        $this->diagnostico($dataDiagnostico, $sheet_3, $tipo);
        $this->aptitudMedica($dataAptitudMedica, $sheet_3, $tipo);
        $this->recomendacion($dataRecomendacion, $sheet_3, $tipo);
        $this->datosProfesional($data, $sheet_3, $tipo);


        /* ESCRIBIR LOS CAMPOS */
        $writer = IOFactory::createWriter($spread, 'Xlsx');
        /* GRABADOS DE ARCHIVOS */
        $nombreFile = $data->identificacion . "_" . $data->id . ".xlsx";
        $writer->save("storage/SIMED/REPORTES/" . $nombreFile);
        return true;
    }

    private function formarExcelPeriodico(
        $tipo,
        $data,
        $discapacidad,
        $dataClinicos,
        $dataAntecedenteGineco,
        $dataExamenGineco,
        $dataAntecedenteReproMasculino,
        $dataExamenReproMasculino,
        $dataHabitos,
        $dataEstiloVida,
        $dataAntecedentes,
        $dataFactoresRiesgosPuestos,
        $dataActividadesExtraLaborales,
        $dataEnfermedadActual,
        $dataAntecedenteTrabajo,
        $dataRevisionActual,
        $dataConstantesVitales,
        $dataAntecedenteAccidenteTrabajo,
        $dataAntecedenteEnfermedadProfesionales,
        $dataExamenenFisicioRegional,
        $dataExamenenGeneralEspecifico,
        $dataDiagnostico,
        $dataAptitudMedica,
        $dataRecomendacion
    )
    {
        /* CARGA DE FORMATO */
        $url = 'storage/SIMED/FORMATOS/periodica.xlsx';
        $reader = IOFactory::createReader("Xlsx");
        $spread = $reader->load($url);
        $spread->setActiveSheetIndex(0); //carga la primera hoja
        $sheet_1 = $spread->getActiveSheet(); //activa la primera hoja
        /* SET CAMPOS */
        $this->datosCabecera($data, $sheet_1, $tipo);
        $this->datosPacientes($data, $discapacidad, $sheet_1, $tipo); //A. DATOS DEL ESTABLECIMIENTO - EMPRESA Y USUARIO b. MOTIVO CONSULTA
        $this->antecedentesPersonales($dataClinicos, $sheet_1, $tipo); //C. ANTECEDENTES PERSONALES
        $this->antecedentesGineco($dataAntecedenteGineco, $sheet_1, $tipo); //C. ANTECEDENTES GINECO OBSTETRICO
        $this->examenesGineco($dataExamenGineco, $sheet_1, $tipo); //C. EXAMENES GINECO
        $this->antecedenteReproMasculino($dataAntecedenteReproMasculino, $sheet_1, $tipo); //C. ANTECEDENTES REPRODUCTIVOS MASCULINOS
        $this->examenesReproMasculino($dataExamenReproMasculino, $sheet_1, $tipo); //C. EXAMENES REPRODUCTIVOS MASCULINOS
        $this->habitosToxicos($dataHabitos, $sheet_1, $tipo); //C. HABITOS TOXICOS
        $this->estiloVida($dataEstiloVida, $sheet_1, $tipo); //C. HABITOS TOXICOS
        $this->antecedenteTrabajo($dataAntecedenteTrabajo, $sheet_1, $tipo);
        $this->antecedenteAccidente($dataAntecedenteAccidenteTrabajo, $sheet_1, $tipo);
        $this->antecedenteEnfermedad($dataAntecedenteEnfermedadProfesionales, $sheet_1, $tipo);

        /**
         * Cargar datos a la segunda hoja
         */
        $spread->setActiveSheetIndex(1);
        $sheet_2 = $spread->getActiveSheet();
        $this->datosCabecera($data, $sheet_2, $tipo);
        $this->antecedentesFamiliares($dataAntecedentes, $sheet_2, $tipo); //E. ANTECEDENTES FAMILIARES
        $this->factoresRiesgosPuestos($dataFactoresRiesgosPuestos, $sheet_2, $tipo); //E. ANTECEDENTES FAMILIARES
        $this->actividadesExtraLaborales($dataActividadesExtraLaborales, $sheet_2, $tipo);
        $this->enfermedadActual($dataEnfermedadActual, $sheet_2, $tipo);
        $this->revisionActual($dataRevisionActual, $sheet_2, $tipo);
        $this->constantesVitales($dataConstantesVitales, $sheet_2, $tipo);

        /**
         * Cargar datis ena la tercera hoja
         */
        $spread->setActiveSheetIndex(2);
        $sheet_3 = $spread->getActiveSheet();
        $this->datosCabecera($data, $sheet_3, $tipo);
        $this->examenFisicoRegional($dataExamenenFisicioRegional, $sheet_3, $tipo);
        $this->examenGeneralEspecifico($dataExamenenGeneralEspecifico, $sheet_3, $tipo);
        $this->diagnostico($dataDiagnostico, $sheet_3, $tipo);
        $this->aptitudMedica($dataAptitudMedica, $sheet_3, $tipo);
        $this->recomendacion($dataRecomendacion, $sheet_3, $tipo);
        $this->datosProfesional($data, $sheet_3, $tipo);


        /* ESCRIBIR LOS CAMPOS */
        $writer = IOFactory::createWriter($spread, 'Xlsx');
        /* GRABADOS DE ARCHIVOS */
        $nombreFile = $data->identificacion . "_" . $data->id . ".xlsx";
        $writer->save("storage/SIMED/REPORTES/" . $nombreFile);
        return true;
    }

    private function formarExcelRetiro(
        $tipo,
        $data,
        $discapacidad,
        $dataClinicos,
        $dataAntecedenteTrabajo,
        $dataConstantesVitales,
        $dataAntecedenteAccidenteTrabajo,
        $dataAntecedenteEnfermedadProfesionales,
        $dataExamenenFisicioRegional,
        $dataExamenenGeneralEspecifico,
        $dataDiagnostico,
        $dataEvaluacionMedicaRetiro,
        $dataRecomendacion
    )
    {
        /* CARGA DE FORMATO */
        $url = 'storage/SIMED/FORMATOS/retiro.xlsx';
        $reader = IOFactory::createReader("Xlsx");
        $spread = $reader->load($url);
        $spread->setActiveSheetIndex(0); //carga la primera hoja
        $sheet_1 = $spread->getActiveSheet(); //activa la primera hoja
        /* SET CAMPOS */
        $this->datosCabecera($data, $sheet_1, $tipo);
        $this->datosPacientes($data, $discapacidad, $sheet_1, $tipo); //A. DATOS DEL ESTABLECIMIENTO - EMPRESA Y USUARIO b. MOTIVO CONSULTA
        $this->antecedentesPersonales($dataClinicos, $sheet_1, $tipo); //C. ANTECEDENTES PERSONALES
        $this->antecedenteAccidente($dataAntecedenteAccidenteTrabajo, $sheet_1, $tipo);
        $this->antecedenteEnfermedad($dataAntecedenteEnfermedadProfesionales, $sheet_1, $tipo);
        $this->constantesVitales($dataConstantesVitales, $sheet_1, $tipo);
        $this->examenFisicoRegional($dataExamenenFisicioRegional, $sheet_1, $tipo);

        /**
         * Cargar datos a la segunda hoja
         */
        $spread->setActiveSheetIndex(1);
        $sheet_2 = $spread->getActiveSheet();
        $this->datosCabecera($data, $sheet_2, $tipo);
        $this->examenGeneralEspecifico($dataExamenenGeneralEspecifico, $sheet_2, $tipo);
        $this->diagnostico($dataDiagnostico, $sheet_2, $tipo);
        $this->evaluacionMedicaRetiro($dataEvaluacionMedicaRetiro, $sheet_2, $tipo);
        $this->recomendacion($dataRecomendacion, $sheet_2, $tipo);
        $this->datosProfesional($data, $sheet_2, $tipo);


        /* ESCRIBIR LOS CAMPOS */
        $writer = IOFactory::createWriter($spread, 'Xlsx');
        /* GRABADOS DE ARCHIVOS */
        $nombreFile = $data->identificacion . "_" . $data->id . ".xlsx";
        $writer->save("storage/SIMED/REPORTES/" . $nombreFile);
        return true;
    }

    private function formarExcelReingreso(
        $tipo,
        $data,
        $discapacidad,
        $dataEnfermedadActual,
        $dataClinicos,
        $dataAntecedenteTrabajo,
        $dataConstantesVitales,
        $dataAntecedenteAccidenteTrabajo,
        $dataAntecedenteEnfermedadProfesionales,
        $dataExamenenFisicioRegional,
        $dataExamenenGeneralEspecifico,
        $dataDiagnostico,
        $dataEvaluacionMedicaRetiro,
        $dataAptitudMedica,
        $dataRecomendacion
    )
    {
        /* CARGA DE FORMATO */
        $url = 'storage/SIMED/FORMATOS/reingreso.xlsx';
        $reader = IOFactory::createReader("Xlsx");
        $spread = $reader->load($url);
        $spread->setActiveSheetIndex(0); //carga la primera hoja
        $sheet_1 = $spread->getActiveSheet(); //activa la primera hoja
        /* SET CAMPOS */
        $this->datosCabecera($data, $sheet_1, $tipo);
        $this->datosPacientes($data, $discapacidad, $sheet_1, $tipo); //A. DATOS DEL ESTABLECIMIENTO - EMPRESA Y USUARIO b. MOTIVO CONSULTA
        //$this->antecedentesPersonales($dataClinicos, $sheet_1, $tipo); //C. ANTECEDENTES PERSONALES

        //$this->actividadesExtraLaborales($dataEnfermedadActual,$sheet_1,$tipo);
        $this->enfermedadActual($dataEnfermedadActual, $sheet_1, $tipo);

        //$this->antecedenteAccidente($dataAntecedenteAccidenteTrabajo, $sheet_1,$tipo);
        //$this->antecedenteEnfermedad($dataEnfermedadActual, $sheet_1,$tipo);
        $this->constantesVitales($dataConstantesVitales, $sheet_1, $tipo);
        $this->examenFisicoRegional($dataExamenenFisicioRegional, $sheet_1, $tipo);
        $this->examenGeneralEspecifico($dataExamenenGeneralEspecifico, $sheet_1, $tipo);
        $this->diagnostico($dataDiagnostico, $sheet_1, $tipo);
        $this->aptitudMedica($dataAptitudMedica, $sheet_1, $tipo);
        $this->recomendacion($dataRecomendacion, $sheet_1, $tipo);
        $this->datosProfesional($data, $sheet_1, $tipo);


        /* ESCRIBIR LOS CAMPOS */
        $writer = IOFactory::createWriter($spread, 'Xlsx');
        /* GRABADOS DE ARCHIVOS */
        $nombreFile = $data->identificacion . "_" . $data->id . ".xlsx";
        $writer->save("storage/SIMED/REPORTES/" . $nombreFile);
        return true;
    }

    public function datosGenerales($data, $sheet)
    {
        $fechaEmision = date('Y-m-d');
        $arrayFechaEmision = explode("-", $fechaEmision);
        $sheet->setCellValue("L11", $arrayFechaEmision[0]);
        $sheet->setCellValue("N11", $arrayFechaEmision[1]);
        $sheet->setCellValue("P11", $arrayFechaEmision[2]);

        switch ($data->tipo_evaluacion) {
            case 'INGRESO':
                $sheet->setCellValue("M13", 'X');
                break;
            case 'RETIRO':
                $sheet->setCellValue("AJ13", 'X');
                break;
            case 'REINGRESO':
                $sheet->setCellValue("AD13", 'X');
                break;
            default:
                $sheet->setCellValue("V13", 'X');
                break;
        }
    }

    private function datosCabecera($data, $sheet, $tipo)
    {
        $codigo = $data->codigo;
        $meses = array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE");
        $fecha_elaboracion = $meses[date('n') - 1] . ' ' . date('Y');
        $version = "01";
        $revision = $meses[date('n') - 1] . ' ' . date('Y');
        switch ($tipo) {
            case 'INGRESO':
                $sheet->setCellValue("AL1", "CODIGO: " . $codigo);
                $sheet->setCellValue("AL2", "FECHA ELABORACIÓN: " . $fecha_elaboracion);
                $sheet->setCellValue("AY1", "Versión: " . $version);
                $sheet->setCellValue("AY2", "REVISION: " . $revision);
                break;
            case 'RETIRO':
                $sheet->setCellValue("R2", "HISTORIA CLÍNICA DE RETIRO");
                $sheet->setCellValue("AL1", "CODIGO: " . $codigo);
                $sheet->setCellValue("AL2", "FECHA ELABORACIÓN: " . $fecha_elaboracion);
                $sheet->setCellValue("AY1", "Versión: " . $version);
                $sheet->setCellValue("AY2", "REVISION: " . $revision);
                break;
            case 'REINGRESO':
                $sheet->setCellValue("R2", "HISTORIA CLÍNICA DE REINGRESO");
                $sheet->setCellValue("AL1", "CODIGO: " . $codigo);
                $sheet->setCellValue("AL2", "FECHA ELABORACIÓN: " . $fecha_elaboracion);
                $sheet->setCellValue("AY1", "Versión: " . $version);
                $sheet->setCellValue("AY2", "REVISION: " . $revision);
                break;
            case 'ATENCIONES DIARIAS':
                $sheet->setCellValue("AL1", "CODIGO: " . $codigo);
                $sheet->setCellValue("AL2", "FECHA ELABORACIÓN: " . $fecha_elaboracion);
                $sheet->setCellValue("AY1", "Versión: " . $version);
                $sheet->setCellValue("AY2", "REVISION: " . $revision);
                break;
            case 'CERTIFICADO':
                $sheet->setCellValue("AA1", "CODIGO: " . " \n" . $codigo);
                $sheet->setCellValue("AA2", "FECHA ELABORACIÓN: " . " \n" . $fecha_elaboracion);
                $sheet->setCellValue("AI1", "Versión: " . $version);
                $sheet->setCellValue("AI2", "REVISION: " . " \n" . $revision);
                break;
            default:
                $sheet->setCellValue("R2", "HISTORIA CLÍNICA PERIÓDICO");
                $sheet->setCellValue("AL1", "CODIGO: " . $codigo);
                $sheet->setCellValue("AL2", "FECHA ELABORACIÓN: " . $fecha_elaboracion);
                $sheet->setCellValue("AY1", "Versión: " . $version);
                $sheet->setCellValue("AY2", "REVISION: " . $revision);
                break;
        }
    }

    private function datosPacientes($data, $discapacidad, $sheet_1, $tipo)
    {
        $descripcion_actividad = "Descripcion: " . " \n";
        $descripcion_factores = "Descripcion: " . " \n";

        switch ($tipo) {
            case 'INGRESO':
                /* hoja_1 */
                $codigo = $data->codigo;
                //$sheet_1->setCellValue("AL1", "CODIGO: " . $codigo);
                $sheet_1->setCellValue("B5", !is_null($data->institucion) ? $data->institucion : '');
                $sheet_1->setCellValue("P5", !is_null($data->ruc) ? $data->ruc : '');
                $sheet_1->setCellValue("X5", !is_null($data->ciiu) ? $data->ciiu : '');
                $sheet_1->setCellValue("AA5", !is_null($data->establecimiento_salud) ? $data->establecimiento_salud : '');
                $sheet_1->setCellValue("AN5", !is_null($data->historia_clinica) ? $data->historia_clinica : '');
                $sheet_1->setCellValue("AY5", !is_null($data->numero_archivo) ? $data->numero_archivo : '');
                $sheet_1->setCellValue("B8", !is_null($data->apellidos) ? $data->apellidos : '');
                $sheet_1->setCellValue("T8", !is_null($data->nombres) ? $data->nombres : '');
                $sheet_1->setCellValue("AI8", !is_null($data->genero) ? ($data->genero == 'MASCULINO' ? 'M' : 'F') : '');
                $sheet_1->setCellValue("AK8", !is_null($data->edad) ? $data->edad : '');
                /* SETEAR RELIGION */
                $cellReligion = "";
                switch ($data->religion) {
                    case 'CATÓLICA':
                        $cellReligion = "AN8";
                        break;
                    case 'EVANGÉLICA':
                        $cellReligion = "AO8";
                        break;
                    case 'TESTIGOS DE JEHOVÁ':
                        $cellReligion = "AP8";
                        break;
                    case 'MORMONA':
                        $cellReligion = "AQ8";
                        break;
                    case 'OTRAS':
                        $cellReligion = "AR8";
                        break;
                    default:
                        $cellReligion = "";
                        break;
                }
                if ($cellReligion != "")
                    $sheet_1->setCellValue($cellReligion, "X");
                /* FIN RELIGION */
                $sheet_1->setCellValue("AS8", !is_null($data->tipo_sangre) ? $data->tipo_sangre : '');
                $sheet_1->setCellValue("AY8", !is_null($data->lateralidad) ? $data->lateralidad : '');
                /* SETEAR ORIENTACION SEXUAL */
                $cellOrientacion = "";
                switch ($data->orientacion_sexual) {
                    case 'LESBIANA':
                        $cellOrientacion = "B11";
                        break;
                    case 'GAY':
                        $cellOrientacion = "C11";
                        break;
                    case 'BISEXUAL':
                        $cellOrientacion = "D11";
                        break;
                    case 'HETEROSEXUAL':
                        $cellOrientacion = "E11";
                        break;
                    case 'NO SABE/NO RESPONDE':
                        $cellOrientacion = "F11";
                        break;
                    default:
                        $cellOrientacion = "";
                        break;
                }
                if ($cellOrientacion != "") {
                    $sheet_1->setCellValue($cellOrientacion, "X");
                }
                /* SETEAR IDENTIDAD DE GENERO */
                $cellIdentidad = "";
                switch ($data->orientacion_sexual) {
                    case 'FEMENINO':
                        $cellIdentidad = "G11";
                        break;
                    case 'MASCULINO':
                        $cellIdentidad = "H11";
                        break;
                    case 'TRANS-FEMENINO':
                        $cellIdentidad = "I11";
                        break;
                    case 'TRANS-MASCULINO':
                        $cellIdentidad = "J11";
                        break;
                    case 'NO SABE /NO RESPONDE':
                        $cellIdentidad = "K11";
                        break;
                    default:
                        $cellIdentidad = "";
                        break;
                }
                if ($cellIdentidad != "")
                    $sheet_1->setCellValue($cellIdentidad, "X");
                /* SETEAR DISCAPACIDAD */
                $nombre_ = "";
                $porcentaje_ = "";
                if (count($discapacidad) == 0) {
                    $sheet_1->setCellValue("M11", "X");
                } else {
                    $sheet_1->setCellValue("L11", "X");
                    foreach ($discapacidad as $value) {
                        foreach ($value as $key => $value_) {
                            if ($key == "nombre") $nombre_ .= $value_ . " \n";
                            if ($key == "porcentaje") $porcentaje_ .= $value_ . " \n";
                        }
                    }
                    $sheet_1->setCellValue("N11", $nombre_);
                    $sheet_1->setCellValue("T11", $porcentaje_);
                }
                $sheet_1->setCellValue("W11", !is_null($data->fecha_ingreso) ? $data->fecha_ingreso : ''); //SETEAR FECHA INGRESO
                $sheet_1->setCellValue("Z11", !is_null($data->cargo) ? $data->cargo : ''); //SETEAR PUESTO DE TRABAJO CIUO
                $sheet_1->setCellValue("AD11", !is_null($data->area) ? $data->area : ''); //SETEAR AREA DE TRABAJO
                $sheet_1->setCellValue("AJ11", !is_null($data->actividad_relevante) ? $data->actividad_relevante : ''); //SETEAR ACTIVIDADES
                $sheet_1->setCellValue("B15", !is_null($data->motivo_consulta) ? $data->motivo_consulta : ''); //MOTIVO CONSULTA
                break;
            case 'RETIRO':
                /* hoja_1 */
                $sheet_1->setCellValue("B5", !is_null($data->institucion) ? $data->institucion : '');
                $sheet_1->setCellValue("P5", !is_null($data->ruc) ? $data->ruc : '');
                $sheet_1->setCellValue("X5", !is_null($data->ciiu) ? $data->ciiu : '');
                $sheet_1->setCellValue("AA5", !is_null($data->establecimiento_salud) ? $data->establecimiento_salud : '');
                $sheet_1->setCellValue("AN5", !is_null($data->historia_clinica) ? $data->historia_clinica : '');
                $sheet_1->setCellValue("AZ5", !is_null($data->numero_archivo) ? $data->numero_archivo : '');
                $sheet_1->setCellValue("B7", !is_null($data->apellidos) ? $data->apellidos : '');
                $sheet_1->setCellValue("R7", !is_null($data->nombres) ? $data->nombres : '');
                $sheet_1->setCellValue("AG7", !is_null($data->genero) ? ($data->genero == 'MASCULINO' ? 'M' : 'F') : '');
                $sheet_1->setCellValue("AJ7", !is_null($data->fecha_ingreso) ? $data->fecha_ingreso : ''); //SETEAR FECHA INGRESO
                $sheet_1->setCellValue("AO7", !is_null($data->fecha_salida) ? $data->fecha_salida : ''); //SETEAR FECHA SALIDA
                $sheet_1->setCellValue("AT7", !is_null($data->tiempo_meses) ? $data->tiempo_meses : ''); //SETEAR FECHA SALIDA
                $sheet_1->setCellValue("AY7", !is_null($data->cargo) ? $data->cargo : ''); //SETEAR PUESTO DE TRABAJO CIUO
                $sheet_1->setCellValue("B9", !is_null($data->actividad_relevante) ?  $descripcion_actividad .= $data->actividad_relevante  : ''); //SETEAR PUESTO DE TRABAJO CIUO
                $sheet_1->setCellValue("S9", !is_null($data->factores_riesgo) ? $descripcion_factores .= $data->factores_riesgo : ''); //SETEAR PUESTO DE TRABAJO CIUO

                break;
            case 'REINGRESO':
                /* hoja_1 */
                $sheet_1->setCellValue("B5", !is_null($data->institucion) ? $data->institucion : '');
                $sheet_1->setCellValue("T5", !is_null($data->ruc) ? $data->ruc : '');
                $sheet_1->setCellValue("AA5", !is_null($data->ciiu) ? $data->ciiu : '');
                $sheet_1->setCellValue("AF5", !is_null($data->establecimiento_salud) ? $data->establecimiento_salud : '');
                $sheet_1->setCellValue("AQ5", !is_null($data->historia_clinica) ? $data->historia_clinica : '');
                $sheet_1->setCellValue("AZ5", !is_null($data->numero_archivo) ? $data->numero_archivo : '');
                $sheet_1->setCellValue("B7", !is_null($data->apellidos) ? $data->apellidos : '');
                $sheet_1->setCellValue("P7", !is_null($data->nombres) ? $data->nombres : '');
                $sheet_1->setCellValue("AC7", !is_null($data->genero) ? ($data->genero == 'MASCULINO' ? 'M' : 'F') : '');
                $sheet_1->setCellValue("AD7", !is_null($data->edad) ? $data->edad : '');
                $sheet_1->setCellValue("AF7", !is_null($data->cargo) ? $data->cargo : ''); //SETEAR PUESTO DE TRABAJO CIUO
                $sheet_1->setCellValue("AK7", !is_null($data->fecha_salida) ? $data->fecha_salida : ''); //SETEAR PUESTO DE TRABAJO CIUO
                $sheet_1->setCellValue("AV7", !is_null($data->causa_salida) ? $data->causa_salida : ''); //MOTIVO CONSULTA
                //$sheet_1->setCellValue("AO7", !is_null($data->fecha_reingreso) ? $data->fecha_reingreso : ''); //SETEAR PUESTO DE TRABAJO CIUO
                //$sheet_1->setCellValue("AS7", !is_null($data->total_dias) ? $data->total_dias : ''); //SETEAR PUESTO DE TRABAJO CIUO
                //$sheet_1->setCellValue("AV7", !is_null($data->causa_salida) ? $data->causa_salida : ''); //SETEAR PUESTO DE TRABAJO CIUO
                $sheet_1->setCellValue("B11", !is_null($data->motivo_consulta) ? $data->motivo_consulta : ''); //MOTIVO CONSULTA
                break;
            case 'ATENCIONES DIARIAS':
                $sheet_1->setCellValue("B4", !is_null($data->motivo_consulta) ? $data->motivo_consulta : ''); //MOTIVO CONSULTA
                break;
            case 'CERTIFICADO':
                $sheet_1->setCellValue("B5", !is_null($data->institucion) ? $data->institucion : '');
                $sheet_1->setCellValue("M5", !is_null($data->ruc) ? $data->ruc : '');
                $sheet_1->setCellValue("S5", !is_null($data->ciiu) ? $data->ciiu : '');
                $sheet_1->setCellValue("W5", !is_null($data->establecimiento_salud) ? $data->establecimiento_salud : '');
                $sheet_1->setCellValue("AD5", !is_null($data->historia_clinica) ? $data->historia_clinica : '');
                $sheet_1->setCellValue("AJ5", !is_null($data->numero_archivo) ? $data->numero_archivo : '');

                $sheet_1->setCellValue("B7", !is_null($data->primer_apellido) ? $data->primer_apellido : '');
                $sheet_1->setCellValue("K7", !is_null($data->segundo_apellido) ? $data->segundo_apellido : '');
                $sheet_1->setCellValue("R7", !is_null($data->primer_nombre) ? $data->primer_nombre : '');
                $sheet_1->setCellValue("Y7", !is_null($data->segundo_nombre) ? $data->segundo_nombre : '');

                $sheet_1->setCellValue("AE7", !is_null($data->genero) ? ($data->genero == 'MASCULINO' ? 'M' : 'F') : '');
                $sheet_1->setCellValue("AH7", !is_null($data->cargo) ? $data->cargo : ''); //SETEAR PUESTO DE TRABAJO CIUO
                break;
            default:
                /* hoja_1 */
                $sheet_1->setCellValue("B5", !is_null($data->institucion) ? $data->institucion : '');
                $sheet_1->setCellValue("P5", !is_null($data->ruc) ? $data->ruc : '');
                $sheet_1->setCellValue("X5", !is_null($data->ciiu) ? $data->ciiu : '');
                $sheet_1->setCellValue("AA5", !is_null($data->establecimiento_salud) ? $data->establecimiento_salud : '');
                $sheet_1->setCellValue("AN5", !is_null($data->historia_clinica) ? $data->historia_clinica : '');
                $sheet_1->setCellValue("AY5", !is_null($data->numero_archivo) ? $data->numero_archivo : '');
                $sheet_1->setCellValue("B8", !is_null($data->apellidos) ? $data->apellidos : '');
                $sheet_1->setCellValue("T8", !is_null($data->nombres) ? $data->nombres : '');
                $sheet_1->setCellValue("AI8", !is_null($data->genero) ? ($data->genero == 'MASCULINO' ? 'M' : 'F') : '');

                $sheet_1->setCellValue("AK8", !is_null($data->edad) ? $data->edad : '');
                /* SETEAR RELIGION */
                $cellReligion = "";
                switch ($data->religion) {
                    case 'CATÓLICA':
                        $cellReligion = "AN8";
                        break;
                    case 'EVANGÉLICA':
                        $cellReligion = "AO8";
                        break;
                    case 'TESTIGOS DE JEHOVÁ':
                        $cellReligion = "AP8";
                        break;
                    case 'MORMONA':
                        $cellReligion = "AQ8";
                        break;
                    case 'OTRAS':
                        $cellReligion = "AR8";
                        break;
                    default:
                        $cellReligion = "";
                        break;
                }
                if ($cellReligion != "")
                    $sheet_1->setCellValue($cellReligion, "X");
                /* FIN RELIGION */
                $sheet_1->setCellValue("AS8", !is_null($data->tipo_sangre) ? $data->tipo_sangre : '');
                $sheet_1->setCellValue("AY8", !is_null($data->lateralidad) ? $data->lateralidad : '');
                /* SETEAR ORIENTACION SEXUAL */
                $cellOrientacion = "";
                switch ($data->orientacion_sexual) {
                    case 'LESBIANA':
                        $cellOrientacion = "B11";
                        break;
                    case 'GAY':
                        $cellOrientacion = "C11";
                        break;
                    case 'BISEXUAL':
                        $cellOrientacion = "D11";
                        break;
                    case 'HETEROSEXUAL':
                        $cellOrientacion = "E11";
                        break;
                    case 'NO SABE/NO RESPONDE':
                        $cellOrientacion = "F11";
                        break;
                    default:
                        $cellOrientacion = "";
                        break;
                }
                if ($cellOrientacion != "") {
                    $sheet_1->setCellValue($cellOrientacion, "X");
                }
                /* SETEAR IDENTIDAD DE GENERO */
                $cellIdentidad = "";
                switch ($data->orientacion_sexual) {
                    case 'FEMENINO':
                        $cellIdentidad = "G11";
                        break;
                    case 'MASCULINO':
                        $cellIdentidad = "H11";
                        break;
                    case 'TRANS-FEMENINO':
                        $cellIdentidad = "I11";
                        break;
                    case 'TRANS-MASCULINO':
                        $cellIdentidad = "J11";
                        break;
                    case 'NO SABE /NO RESPONDE':
                        $cellIdentidad = "K11";
                        break;
                    default:
                        $cellIdentidad = "";
                        break;
                }
                if ($cellIdentidad != "")
                    $sheet_1->setCellValue($cellIdentidad, "X");
                /* SETEAR DISCAPACIDAD */
                $nombre_ = "";
                $porcentaje_ = "";
                if (count($discapacidad) == 0) {
                    $sheet_1->setCellValue("M11", "X");
                } else {
                    $sheet_1->setCellValue("L11", "X");
                    foreach ($discapacidad as $value) {
                        foreach ($value as $key => $value_) {
                            if ($key == "nombre") $nombre_ .= $value_ . " \n";
                            if ($key == "porcentaje") $porcentaje_ .= $value_ . " \n";
                        }
                    }
                    $sheet_1->setCellValue("N11", $nombre_);
                    $sheet_1->setCellValue("T11", $porcentaje_);
                }
                $sheet_1->setCellValue("W11", !is_null($data->fecha_ingreso) ? $data->fecha_ingreso : ''); //SETEAR FECHA INGRESO
                $sheet_1->setCellValue("Z11", !is_null($data->cargo) ? $data->cargo : ''); //SETEAR PUESTO DE TRABAJO CIUO
                $sheet_1->setCellValue("AD11", !is_null($data->area) ? $data->area : ''); //SETEAR AREA DE TRABAJO
                $sheet_1->setCellValue("AJ11", !is_null($data->actividad_relevante) ? $data->actividad_relevante : ''); //SETEAR ACTIVIDADES
                $sheet_1->setCellValue("B15", !is_null($data->motivo_consulta) ? $data->motivo_consulta : ''); //MOTIVO CONSULTA
                break;
        }
    }

    private function antecedentesPersonales($dataClinicos, $sheet_1, $tipo)
    {
        switch ($tipo) {
            case 'INGRESO':
                $personales = "PERSONALES: ";
                $quirurgicos = "QUIRURGICOS: ";
                $habilitaAntecedente = false;
                foreach ($dataClinicos as $value) {
                    if ($value['tipo'] == "PERSONALES") $personales .= $value['descripcion'] . " \n";
                    if ($value['tipo'] == "QUIRURGICOS") $quirurgicos .= $value['descripcion'] . " \n";
                    $habilitaAntecedente = true;
                }
                if (!$habilitaAntecedente) {
                    $personales = "";
                    $quirurgicos = "";
                }
                $sheet_1->setCellValue("B20", $personales);
                $sheet_1->setCellValue("B21", $quirurgicos);
                break;
            case 'RETIRO':
                $clinicos = "PERSONALES: ";
                $quirurjicos = "QUIRURGICOS: ";
                $habilitaAntecedente = false;
                foreach ($dataClinicos as $value) {
                    if ($value['tipo'] == "PERSONALES") $clinicos .= $value['descripcion'] . " \n";
                    if ($value['tipo'] == "QUIRURGICOS") $quirurjicos .= $value['descripcion'] . " \n";
                    $habilitaAntecedente = true;
                }
                if (!$habilitaAntecedente) {
                    $clinicos = "";
                    $quirurjicos = "";
                }
                $sheet_1->setCellValue("B16", $clinicos);
                $sheet_1->setCellValue("B17", $quirurjicos);
                break;
            default:
                $clinicos = "PERSONALES: ";
                $quirurjicos = "QUIRURGICOS: ";
                $habilitaAntecedente = false;
                foreach ($dataClinicos as $value) {
                    if ($value['tipo'] == "PERSONALES") $clinicos .= $value['descripcion'] . " \n";
                    if ($value['tipo'] == "QUIRURGICOS") $quirurjicos .= $value['descripcion'] . " \n";
                    $habilitaAntecedente = true;
                }
                if (!$habilitaAntecedente) {
                    $clinicos = "";
                    $quirurjicos = "";
                }
                $sheet_1->setCellValue("B20", $clinicos);
                $sheet_1->setCellValue("B21", $quirurjicos);
                break;
        }
    }

    private function antecedentesGineco($dataAntecedenteGineco, $sheet_1, $tipo)
    {
        if (!is_null($dataAntecedenteGineco)) {
            switch ($tipo) {
                case 'INGRESO':
                    $dataAntecedenteGineco->menarquia != null ? $sheet_1->setCellValue("B25", $dataAntecedenteGineco->menarquia) : $sheet_1->setCellValue("B25", "");
                    //$sheet_1->setCellValue("B25", $dataAntecedenteGineco->menarquia);
                    $sheet_1->setCellValue("L25", $dataAntecedenteGineco->ciclos);
                    $sheet_1->setCellValue("P25", $dataAntecedenteGineco->menstruacion);
                    $sheet_1->setCellValue("V25", $dataAntecedenteGineco->gestas);
                    $sheet_1->setCellValue("Z25", $dataAntecedenteGineco->partos);
                    $sheet_1->setCellValue("AC25", $dataAntecedenteGineco->cesareas);
                    $sheet_1->setCellValue("AF25", $dataAntecedenteGineco->abortos);
                    $sheet_1->setCellValue("AJ25", $dataAntecedenteGineco->hijos_vivos);
                    $sheet_1->setCellValue("AL25", $dataAntecedenteGineco->hijos_muertos);
                    $cellVidaSexual = $dataAntecedenteGineco->vida_sexual ? "AO25" : "AQ25";
                    $sheet_1->setCellValue($cellVidaSexual, "X");
                    $cellPlanificacion = $dataAntecedenteGineco->planificacion_familiar ? "AS25" : "AV25";
                    $sheet_1->setCellValue($cellPlanificacion, "X");
                    $sheet_1->setCellValue("AY25", $dataAntecedenteGineco->tipo_planificacion_familiar);
                    break;
                default:
                    $sheet_1->setCellValue("B25", $dataAntecedenteGineco->menarquia);
                    $sheet_1->setCellValue("L25", $dataAntecedenteGineco->ciclos);
                    $sheet_1->setCellValue("P25", $dataAntecedenteGineco->menstruacion);
                    $sheet_1->setCellValue("V25", $dataAntecedenteGineco->gestas);
                    $sheet_1->setCellValue("Z25", $dataAntecedenteGineco->partos);
                    $sheet_1->setCellValue("AC25", $dataAntecedenteGineco->cesareas);
                    $sheet_1->setCellValue("AF25", $dataAntecedenteGineco->abortos);
                    $sheet_1->setCellValue("AJ25", $dataAntecedenteGineco->hijos_vivos);
                    $sheet_1->setCellValue("AL25", $dataAntecedenteGineco->hijos_muertos);
                    $cellVidaSexual = $dataAntecedenteGineco->vida_sexual ? "AO25" : "AQ25";
                    $sheet_1->setCellValue($cellVidaSexual, "X");
                    $cellPlanificacion = $dataAntecedenteGineco->planificacion_familiar ? "AS25" : "AV25";
                    $sheet_1->setCellValue($cellPlanificacion, "X");
                    $sheet_1->setCellValue("AY25", $dataAntecedenteGineco->tipo_planificacion_familiar);
                    break;
            }
        }
    }

    private function examenesGineco($data, $sheet_1, $tipo)
    {

        $reg = count($data);
        switch ($tipo) {
            case 'INGRESO':
                if ($reg != 0) {
                    foreach ($data as $value) {
                        switch ($value['tipo_examen']) {
                            case 'PAPANICOLAOU':
                                if ($value['realizo_examen'] == true) $sheet_1->setCellValue("L25", "X");
                                else $sheet_1->setCellValue("M27", "X");
                                $sheet_1->setCellValue("N27", $value['tiempo']);
                                $sheet_1->setCellValue("T27", $value['resultado']);
                                break;
                            case 'COLPOSCOPIA':
                                if ($value['realizo_examen'] == true) $sheet_1->setCellValue("L28", "X");
                                else $sheet_1->setCellValue("M28", "X");
                                $sheet_1->setCellValue("N28", $value['tiempo']);
                                $sheet_1->setCellValue("T28", $value['resultado']);
                                break;
                            case 'ECO MAMARIO':
                                if ($value['realizo_examen'] == true) $sheet_1->setCellValue("AI27", "X");
                                else $sheet_1->setCellValue("AJ27", "X");
                                $sheet_1->setCellValue("AK27", $value['tiempo']);
                                $sheet_1->setCellValue("AQ27", $value['resultado']);
                                break;
                            case 'MAMOGRAFÍA':
                                if ($value['realizo_examen'] == true) $sheet_1->setCellValue("AI28", "X");
                                else $sheet_1->setCellValue("AJ28", "X");
                                $sheet_1->setCellValue("AK28", $value['tiempo']);
                                $sheet_1->setCellValue("AQ28", $value['resultado']);
                                break;
                        }
                    }
                }
                break;

            default:
                if ($reg != 0) {
                    foreach ($data as $value) {
                        switch ($value['tipo_examen']) {
                            case 'PAPANICOLAOU':
                                if ($value['realizo_examen'] == true) $sheet_1->setCellValue("L27", "X");
                                else $sheet_1->setCellValue("M27", "X");
                                $sheet_1->setCellValue("N27", $value['tiempo']);
                                $sheet_1->setCellValue("T27", $value['resultado']);
                                break;
                            case 'COLPOSCOPIA':
                                if ($value['realizo_examen'] == true) $sheet_1->setCellValue("L28", "X");
                                else $sheet_1->setCellValue("M28", "X");
                                $sheet_1->setCellValue("N28", $value['tiempo']);
                                $sheet_1->setCellValue("T28", $value['resultado']);
                                break;
                            case 'ECO MAMARIO':
                                if ($value['realizo_examen'] == true) $sheet_1->setCellValue("AI27", "X");
                                else $sheet_1->setCellValue("AJ27", "X");
                                $sheet_1->setCellValue("AK27", $value['tiempo']);
                                $sheet_1->setCellValue("AQ27", $value['resultado']);
                                break;
                            case 'MAMOGRAFÍA':
                                if ($value['realizo_examen'] == true) $sheet_1->setCellValue("AI28", "X");
                                else $sheet_1->setCellValue("AJ28", "X");
                                $sheet_1->setCellValue("AK28", $value['tiempo']);
                                $sheet_1->setCellValue("AQ28", $value['resultado']);
                                break;
                        }
                    }
                }
                break;
        }
    }

    private function antecedenteReproMasculino($dataAntecedenteReproMasculino, $sheet_1, $tipo)
    {
        if (!is_null($dataAntecedenteReproMasculino)) {
            switch ($tipo) {
                case 'INGRESO':
                    $numFila = 32;

                    foreach ($dataAntecedenteReproMasculino as $value) {

                        $cellPlanificacion_ = $dataAntecedenteReproMasculino->planificacion_familiar ? "AJ" . $numFila : "AK" . $numFila;
                        $sheet_1->setCellValue($cellPlanificacion_, "X");
                        $sheet_1->setCellValue("AL" . $numFila, $dataAntecedenteReproMasculino->tipo_planificacion_familiar);
                        $sheet_1->setCellValue("AZ" . $numFila, $dataAntecedenteReproMasculino->hijos_vivos);
                        $sheet_1->setCellValue("BC" . $numFila, $dataAntecedenteReproMasculino->hijos_muertos);

                        $numFila++;
                    }


                    break;

                default:

                    $numFila = 32;

                    foreach ($dataAntecedenteReproMasculino as $value) {

                        $cellPlanificacion_ = $dataAntecedenteReproMasculino->planificacion_familiar ? "AJ" . $numFila : "AK" . $numFila;
                        $sheet_1->setCellValue($cellPlanificacion_, "X");
                        $sheet_1->setCellValue("AL" . $numFila, $dataAntecedenteReproMasculino->tipo_planificacion_familiar);
                        $sheet_1->setCellValue("AZ" . $numFila, $dataAntecedenteReproMasculino->hijos_vivos);
                        $sheet_1->setCellValue("BC" . $numFila, $dataAntecedenteReproMasculino->hijos_muertos);

                        $numFila++;
                    }


                    break;
            }
        }
    }

    private function examenesReproMasculino($data, $sheet_1, $tipo)
    {

        switch ($tipo) {
            case 'INGRESO':
                $reg = count($data);
                if ($reg != 0) {
                    foreach ($data as $value) {
                        switch ($value['tipo_examen']) {
                            case 'ECO PROSTÁTICO':
                                $sheet_1->setCellValue("B33", $value['tipo_examen']);
                                if ($value['realizo_examen'] == true) $sheet_1->setCellValue("L33", "X");
                                else $sheet_1->setCellValue("M33", "X");
                                $sheet_1->setCellValue("N33", $value['tiempo']);
                                $sheet_1->setCellValue("T33", $value['resultado']);
                                break;
                            case 'ANTIGENO PROSTÁTICO':
                                $sheet_1->setCellValue("B32", $value['tipo_examen']);
                                if ($value['realizo_examen'] == true) $sheet_1->setCellValue("L32", "X");
                                else $sheet_1->setCellValue("M32", "X");
                                $sheet_1->setCellValue("N32", $value['tiempo']);
                                $sheet_1->setCellValue("T32", $value['resultado']);
                                break;
                        }
                    }
                }
                break;

            default:
                $reg = count($data);
                if ($reg != 0) {
                    foreach ($data as $value) {
                        switch ($value['tipo_examen']) {
                            case 'ECO PROSTÁTICO':
                                $sheet_1->setCellValue("B33", $value['tipo_examen']);
                                if ($value['realizo_examen'] == true) $sheet_1->setCellValue("L33", "X");
                                else $sheet_1->setCellValue("M33", "X");
                                $sheet_1->setCellValue("N33", $value['tiempo']);
                                $sheet_1->setCellValue("T33", $value['resultado']);
                                break;
                            case 'ANTIGENO PROSTÁTICO':
                                $sheet_1->setCellValue("B32", $value['tipo_examen']);
                                if ($value['realizo_examen'] == true) $sheet_1->setCellValue("L32", "X");
                                else $sheet_1->setCellValue("M32", "X");
                                $sheet_1->setCellValue("N32", $value['tiempo']);
                                $sheet_1->setCellValue("T32", $value['resultado']);
                                break;
                        }
                    }
                }
                break;
        }
    }

    private function habitosToxicos($data, $sheet_1, $tipo)
    {
        switch ($tipo) {
            case 'INGRESO':
                $reg = count($data);
                if ($reg != 0) {
                    $cell = 36;
                    foreach ($data as $value) {
                        $sheet_1->setCellValue("B" . $cell, $value['descripcion']);
                        if ($value['valor'] == true) $sheet_1->setCellValue("L" . $cell, "X");
                        else $sheet_1->setCellValue("M" . $cell, "X");
                        $sheet_1->setCellValue("N" . $cell, $value['tiempo_consumo']); //tiempo consumo
                        $sheet_1->setCellValue("S" . $cell, $value['cantidad']);
                        $ex_consumidor = $value['ex_consumidor'] ? "SI" : "NO";
                        $sheet_1->setCellValue("W" . $cell, $ex_consumidor);
                        $sheet_1->setCellValue("AA" . $cell, $value['tiempo_abstinencia']);
                        $cell++;
                    }
                }
                break;

            default:
                $reg = count($data);
                if ($reg != 0) {
                    $cell = 36;
                    foreach ($data as $value) {
                        $sheet_1->setCellValue("B" . $cell, $value['descripcion']);
                        if ($value['valor'] == true) $sheet_1->setCellValue("L" . $cell, "X");
                        else $sheet_1->setCellValue("M" . $cell, "X");
                        $sheet_1->setCellValue("N" . $cell, $value['tiempo_consumo']); //tiempo consumo
                        $sheet_1->setCellValue("S" . $cell, $value['cantidad']);
                        $ex_consumidor = $value['ex_consumidor'] ? "SI" : "NO";
                        $sheet_1->setCellValue("W" . $cell, $ex_consumidor);
                        $sheet_1->setCellValue("AA" . $cell, $value['tiempo_abstinencia']);
                        $cell++;
                    }
                }
                break;
        }
    }

    private function estiloVida($data, $sheet_1, $tipo)
    {

        $reg = count($data);
        switch ($tipo) {
            case 'INGRESO':
                if ($reg != 0) {
                    $cell = 37;
                    foreach ($data as $value) {
                        switch ($value['descripcion']) {
                            case 'ACTIVIDAD FÍSICA':
                                $sheet_1->setCellValue("AD36", $value['descripcion']);
                                if ($value['valor'] == true) $sheet_1->setCellValue("AH36", "X");
                                else $sheet_1->setCellValue("AI36", "X");
                                $sheet_1->setCellValue("AJ36", $value['tipo_estilo_vida']);
                                $sheet_1->setCellValue("AY36", $value['tiempo_cantidad']);
                                break;
                            case 'MEDICACIÓN HABITUAL':
                                if ($cell == 37) {
                                    $sheet_1->setCellValue("AD37", $value['descripcion']);
                                    if ($value['valor'] == true) $sheet_1->setCellValue("AH37", "X");
                                    else $sheet_1->setCellValue("AI37", "X");
                                }
                                $sheet_1->setCellValue("AJ" . $cell, $value['tipo_estilo_vida']);
                                $sheet_1->setCellValue("AY" . $cell, $value['tiempo_cantidad']);
                                $cell++;
                                break;
                        }
                    }
                }
                break;

            default:
                if ($reg != 0) {
                    $cell = 37;
                    foreach ($data as $value) {
                        switch ($value['descripcion']) {
                            case 'ACTIVIDAD FÍSICA':
                                $sheet_1->setCellValue("AD36", $value['descripcion']);
                                if ($value['valor'] == true) $sheet_1->setCellValue("AH36", "X");
                                else $sheet_1->setCellValue("AI36", "X");
                                $sheet_1->setCellValue("AJ36", $value['tipo_estilo_vida']);
                                $sheet_1->setCellValue("AY36", $value['tiempo_cantidad']);
                                break;
                            case 'MEDICACIÓN HABITUAL':
                                if ($cell == 37) {
                                    $sheet_1->setCellValue("AD37", $value['descripcion']);
                                    if ($value['valor'] == true) $sheet_1->setCellValue("AH37", "X");
                                    else $sheet_1->setCellValue("AI37", "X");
                                }
                                $sheet_1->setCellValue("AJ" . $cell, $value['tipo_estilo_vida']);
                                $sheet_1->setCellValue("AY" . $cell, $value['tiempo_cantidad']);
                                $cell++;
                                break;
                        }
                    }
                }
                break;
        }
    }

    private function antecedentesFamiliares($dataAntecedentes, $sheet, $tipo)
    {

        switch ($tipo) {
            case 'INGRESO':
                $descripcion = "Descripcion: " . " \n";
                foreach ($dataAntecedentes as $value) {

                    if ($value['detalle'] == "ENFERMEDAD CARDIO-VASCULAR") {
                        $sheet->setCellValue("H4", "X");
                        $descripcion .= "1 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == "ENFERMEDAD METABÓLICA") {
                        $sheet->setCellValue("N4", "X");
                        $descripcion .= "2 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == "ENFERMEDAD NEUROLÓGICA") {
                        $sheet->setCellValue("V4", "X");
                        $descripcion .= "3 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == 'ENFERMEDAD ONCOLÓGICA') {
                        $sheet->setCellValue("AC4", "X");
                        $descripcion .= "4 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == "ENFERMEDAD INFECCIOSA") {
                        $sheet->setCellValue("AK4", "X");
                        $descripcion .= "5 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == "ENFERMEDAD HEREDITARIA / CONGÉNITA") {
                        $sheet->setCellValue("AS4", "X");
                        $descripcion .= "6 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == "DISCAPACIDADES") {
                        $sheet->setCellValue("BA4", "X");
                        $descripcion .= "7- " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == "OTROS") {
                        $sheet->setCellValue("BG4", "X");
                        $descripcion .= "8 - " . $value['descripcion'] . " \n";
                    }
                }
                $sheet->setCellValue("B5", $descripcion);
                break;

            default:
                $descripcion = "Descripcion: " . " \n";
                foreach ($dataAntecedentes as $value) {

                    if ($value['detalle'] == "ENFERMEDAD CARDIO-VASCULAR") {
                        $sheet->setCellValue("H4", "X");
                        $descripcion .= "1 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == "ENFERMEDAD METABÓLICA") {
                        $sheet->setCellValue("N4", "X");
                        $descripcion .= "2 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == "ENFERMEDAD NEUROLÓGICA") {
                        $sheet->setCellValue("V4", "X");
                        $descripcion .= "3 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == 'ENFERMEDAD ONCOLÓGICA') {
                        $sheet->setCellValue("AC4", "X");
                        $descripcion .= "4 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == "ENFERMEDAD INFECCIOSA") {
                        $sheet->setCellValue("AK4", "X");
                        $descripcion .= "5 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == "ENFERMEDAD HEREDITARIA / CONGÉNITA") {
                        $sheet->setCellValue("AS4", "X");
                        $descripcion .= "6 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == "DISCAPACIDADES") {
                        $sheet->setCellValue("BA4", "X");
                        $descripcion .= "7- " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == "OTROS") {
                        $sheet->setCellValue("BG4", "X");
                        $descripcion .= "8 - " . $value['descripcion'] . " \n";
                    }
                }
                $sheet->setCellValue("B5", $descripcion);
                break;
        }
    }

    public function factoresRiesgosPuestos($dataFactoresRiesgosPuestos, $sheet, $tipo)
    {
        switch ($tipo) {
            case 'INGRESO':
                $numFila = 19;
                $numFila_ = 31;

                foreach ($dataFactoresRiesgosPuestos as $value) {

                    $arrayDetalles = explode(",", $value['detalles']);

                    $sheet->setCellValue("D" . $numFila, $value['puesto_trabajo']);
                    $sheet->setCellValue("M" . $numFila, $value['actividades']);

                    foreach ($arrayDetalles as $arrDetalle) {

                        $descripcion = parametro::select('descripcion')->where('id', $arrDetalle)->first()->descripcion;

                        if ($arrDetalle == "74") $sheet->setCellValue("Z" . $numFila, "X");
                        if ($arrDetalle == "75") $sheet->setCellValue("AA" . $numFila, "X");
                        if ($arrDetalle == "76") $sheet->setCellValue("AB" . $numFila, "X");
                        if ($arrDetalle == "77") $sheet->setCellValue("AC" . $numFila, "X");
                        if ($arrDetalle == "78") $sheet->setCellValue("AD" . $numFila, "X");
                        if ($arrDetalle == "79") $sheet->setCellValue("AE" . $numFila, "X");
                        if ($arrDetalle == "80") $sheet->setCellValue("AF" . $numFila, "X");
                        if ($arrDetalle == "81") $sheet->setCellValue("AG" . $numFila, "X");
                        if ($arrDetalle == "82") $sheet->setCellValue("AH" . $numFila, "X");
                        if ($arrDetalle == "83") $sheet->setCellValue("AI" . $numFila, "X");
                        if ($arrDetalle == "84") $sheet->setCellValue("AJ" . $numFila, "X");
                        if ($arrDetalle == "85") $sheet->setCellValue("AK" . $numFila, "X");
                        if ($arrDetalle == "86") $sheet->setCellValue("AL" . $numFila, "X");
                        if ($arrDetalle == "87") $sheet->setCellValue("AM" . $numFila, "X");
                        if ($arrDetalle == "88") $sheet->setCellValue("AN" . $numFila, "X");
                        if ($arrDetalle == "89") $sheet->setCellValue("AO" . $numFila, "X");
                        if ($arrDetalle == "90") $sheet->setCellValue("AP" . $numFila, "X");
                        if ($arrDetalle == "91") $sheet->setCellValue("AQ" . $numFila, "X");
                        if ($arrDetalle == "92") $sheet->setCellValue("AR" . $numFila, "X");
                        if ($arrDetalle == "93") $sheet->setCellValue("AS" . $numFila, "X");
                        if ($arrDetalle == "94") $sheet->setCellValue("AT" . $numFila, "X");
                        if ($arrDetalle == "95") $sheet->setCellValue("AU" . $numFila, "X");
                        if ($arrDetalle == "96") $sheet->setCellValue("AV" . $numFila, "X");
                        if ($arrDetalle == "97") $sheet->setCellValue("AW" . $numFila, "X");
                        if ($arrDetalle == "98") $sheet->setCellValue("AX" . $numFila, "X");
                        if ($arrDetalle == "99") $sheet->setCellValue("AY" . $numFila, "X");
                        if ($arrDetalle == "100") $sheet->setCellValue("AZ" . $numFila, "X");
                        if ($arrDetalle == "101") $sheet->setCellValue("BA" . $numFila, "X");
                        if ($arrDetalle == "102") $sheet->setCellValue("BB" . $numFila, "X");
                        if ($arrDetalle == "103") $sheet->setCellValue("BC" . $numFila, "X");
                        if ($arrDetalle == "104") $sheet->setCellValue("BD" . $numFila, "X");
                        if ($arrDetalle == "105") $sheet->setCellValue("BE" . $numFila, "X");
                        if ($arrDetalle == "106") $sheet->setCellValue("BF" . $numFila, "X");
                        if ($arrDetalle == "107") $sheet->setCellValue("BG" . $numFila, "X");
                    }

                    $sheet->setCellValue("D" . $numFila_, $value['puesto_trabajo']);
                    $sheet->setCellValue("K" . $numFila_, $value['actividades']);
                    $sheet->setCellValue("AS" . $numFila_, $value['medidas_preventivas']);

                    foreach ($arrayDetalles as $arrDetalle) {
                        if ($arrDetalle == "108") $sheet->setCellValue("T" . $numFila_, "X");
                        if ($arrDetalle == "109") $sheet->setCellValue("U" . $numFila_, "X");
                        if ($arrDetalle == "110") $sheet->setCellValue("V" . $numFila_, "X");
                        if ($arrDetalle == "111") $sheet->setCellValue("W" . $numFila_, "X");
                        if ($arrDetalle == "112") $sheet->setCellValue("X" . $numFila_, "X");
                        if ($arrDetalle == "113") $sheet->setCellValue("Y" . $numFila_, "X");
                        if ($arrDetalle == "114") $sheet->setCellValue("Z" . $numFila_, "X");
                        if ($arrDetalle == "115") $sheet->setCellValue("AA" . $numFila_, "X");
                        if ($arrDetalle == "116") $sheet->setCellValue("AB" . $numFila_, "X");
                        if ($arrDetalle == "117") $sheet->setCellValue("AC" . $numFila_, "X");
                        if ($arrDetalle == "118") $sheet->setCellValue("AD" . $numFila_, "X");
                        if ($arrDetalle == "119") $sheet->setCellValue("AE" . $numFila_, "X");
                        if ($arrDetalle == "120") $sheet->setCellValue("AF" . $numFila_, "X");
                        if ($arrDetalle == "121") $sheet->setCellValue("AG" . $numFila_, "X");
                        if ($arrDetalle == "122") $sheet->setCellValue("AH" . $numFila_, "X");
                        if ($arrDetalle == "123") $sheet->setCellValue("AI" . $numFila_, "X");
                        if ($arrDetalle == "124") $sheet->setCellValue("AJ" . $numFila_, "X");
                        if ($arrDetalle == "125") $sheet->setCellValue("AK" . $numFila_, "X");
                        if ($arrDetalle == "126") $sheet->setCellValue("AL" . $numFila_, "X");
                        if ($arrDetalle == "127") $sheet->setCellValue("AM" . $numFila_, "X");
                        if ($arrDetalle == "128") $sheet->setCellValue("AN" . $numFila_, "X");
                        if ($arrDetalle == "129") $sheet->setCellValue("AO" . $numFila_, "X");
                        if ($arrDetalle == "130") $sheet->setCellValue("AP" . $numFila_, "X");
                        if ($arrDetalle == "131") $sheet->setCellValue("AQ" . $numFila_, "X");
                        if ($arrDetalle == "132") $sheet->setCellValue("AR" . $numFila_, "X");
                    }

                    $numFila++;
                    $numFila_++;
                }
                break;
            default:
                $numFila = 19;
                $numFila_ = 31;

                foreach ($dataFactoresRiesgosPuestos as $value) {

                    $arrayDetalles = explode(",", $value['detalles']);

                    $sheet->setCellValue("D" . $numFila, $value['puesto_trabajo']);
                    $sheet->setCellValue("M" . $numFila, $value['actividades']);

                    foreach ($arrayDetalles as $arrDetalle) {

                        $descripcion = parametro::select('descripcion')->where('id', $arrDetalle)->first()->descripcion;

                        if ($arrDetalle == "74") $sheet->setCellValue("Z" . $numFila, "X");
                        if ($arrDetalle == "75") $sheet->setCellValue("AA" . $numFila, "X");
                        if ($arrDetalle == "76") $sheet->setCellValue("AB" . $numFila, "X");
                        if ($arrDetalle == "77") $sheet->setCellValue("AC" . $numFila, "X");
                        if ($arrDetalle == "78") $sheet->setCellValue("AD" . $numFila, "X");
                        if ($arrDetalle == "79") $sheet->setCellValue("AE" . $numFila, "X");
                        if ($arrDetalle == "80") $sheet->setCellValue("AF" . $numFila, "X");
                        if ($arrDetalle == "81") $sheet->setCellValue("AG" . $numFila, "X");
                        if ($arrDetalle == "82") $sheet->setCellValue("AH" . $numFila, "X");
                        if ($arrDetalle == "83") $sheet->setCellValue("AI" . $numFila, "X");
                        if ($arrDetalle == "84") $sheet->setCellValue("AJ" . $numFila, "X");
                        if ($arrDetalle == "85") $sheet->setCellValue("AK" . $numFila, "X");
                        if ($arrDetalle == "86") $sheet->setCellValue("AL" . $numFila, "X");
                        if ($arrDetalle == "87") $sheet->setCellValue("AM" . $numFila, "X");
                        if ($arrDetalle == "88") $sheet->setCellValue("AN" . $numFila, "X");
                        if ($arrDetalle == "89") $sheet->setCellValue("AO" . $numFila, "X");
                        if ($arrDetalle == "90") $sheet->setCellValue("AP" . $numFila, "X");
                        if ($arrDetalle == "91") $sheet->setCellValue("AQ" . $numFila, "X");
                        if ($arrDetalle == "92") $sheet->setCellValue("AR" . $numFila, "X");
                        if ($arrDetalle == "93") $sheet->setCellValue("AS" . $numFila, "X");
                        if ($arrDetalle == "94") $sheet->setCellValue("AT" . $numFila, "X");
                        if ($arrDetalle == "95") $sheet->setCellValue("AU" . $numFila, "X");
                        if ($arrDetalle == "96") $sheet->setCellValue("AV" . $numFila, "X");
                        if ($arrDetalle == "97") $sheet->setCellValue("AW" . $numFila, "X");
                        if ($arrDetalle == "98") $sheet->setCellValue("AX" . $numFila, "X");
                        if ($arrDetalle == "99") $sheet->setCellValue("AY" . $numFila, "X");
                        if ($arrDetalle == "100") $sheet->setCellValue("AZ" . $numFila, "X");
                        if ($arrDetalle == "101") $sheet->setCellValue("BA" . $numFila, "X");
                        if ($arrDetalle == "102") $sheet->setCellValue("BB" . $numFila, "X");
                        if ($arrDetalle == "103") $sheet->setCellValue("BC" . $numFila, "X");
                        if ($arrDetalle == "104") $sheet->setCellValue("BD" . $numFila, "X");
                        if ($arrDetalle == "105") $sheet->setCellValue("BE" . $numFila, "X");
                        if ($arrDetalle == "106") $sheet->setCellValue("BF" . $numFila, "X");
                        if ($arrDetalle == "107") $sheet->setCellValue("BG" . $numFila, "X");
                    }

                    $sheet->setCellValue("D" . $numFila_, $value['puesto_trabajo']);
                    $sheet->setCellValue("K" . $numFila_, $value['actividades']);
                    $sheet->setCellValue("AS" . $numFila_, $value['medidas_preventivas']);

                    foreach ($arrayDetalles as $arrDetalle) {
                        if ($arrDetalle == "108") $sheet->setCellValue("T" . $numFila_, "X");
                        if ($arrDetalle == "109") $sheet->setCellValue("U" . $numFila_, "X");
                        if ($arrDetalle == "110") $sheet->setCellValue("V" . $numFila_, "X");
                        if ($arrDetalle == "111") $sheet->setCellValue("W" . $numFila_, "X");
                        if ($arrDetalle == "112") $sheet->setCellValue("X" . $numFila_, "X");
                        if ($arrDetalle == "113") $sheet->setCellValue("Y" . $numFila_, "X");
                        if ($arrDetalle == "114") $sheet->setCellValue("Z" . $numFila_, "X");
                        if ($arrDetalle == "115") $sheet->setCellValue("AA" . $numFila_, "X");
                        if ($arrDetalle == "116") $sheet->setCellValue("AB" . $numFila_, "X");
                        if ($arrDetalle == "117") $sheet->setCellValue("AC" . $numFila_, "X");
                        if ($arrDetalle == "118") $sheet->setCellValue("AD" . $numFila_, "X");
                        if ($arrDetalle == "119") $sheet->setCellValue("AE" . $numFila_, "X");
                        if ($arrDetalle == "120") $sheet->setCellValue("AF" . $numFila_, "X");
                        if ($arrDetalle == "121") $sheet->setCellValue("AG" . $numFila_, "X");
                        if ($arrDetalle == "122") $sheet->setCellValue("AH" . $numFila_, "X");
                        if ($arrDetalle == "123") $sheet->setCellValue("AI" . $numFila_, "X");
                        if ($arrDetalle == "124") $sheet->setCellValue("AJ" . $numFila_, "X");
                        if ($arrDetalle == "125") $sheet->setCellValue("AK" . $numFila_, "X");
                        if ($arrDetalle == "126") $sheet->setCellValue("AL" . $numFila_, "X");
                        if ($arrDetalle == "127") $sheet->setCellValue("AM" . $numFila_, "X");
                        if ($arrDetalle == "128") $sheet->setCellValue("AN" . $numFila_, "X");
                        if ($arrDetalle == "129") $sheet->setCellValue("AO" . $numFila_, "X");
                        if ($arrDetalle == "130") $sheet->setCellValue("AP" . $numFila_, "X");
                        if ($arrDetalle == "131") $sheet->setCellValue("AQ" . $numFila_, "X");
                        if ($arrDetalle == "132") $sheet->setCellValue("AR" . $numFila_, "X");
                    }

                    $numFila++;
                    $numFila_++;
                }
                break;
        }
    }

    public function actividadesExtraLaborales($dataActividadesExtraLaborales, $sheet, $tipo)
    {

        switch ($tipo) {
            case 'INGRESO':
                $descripcion = "Descripción: " . " \n";
                foreach ($dataActividadesExtraLaborales as $value) {
                    $descripcion .= $value['descripcion'] . ", ";
                }
                $sheet->setCellValue("B37", $descripcion);
                break;
            default:
                $descripcion = "Descripción: " . " \n";
                foreach ($dataActividadesExtraLaborales as $value) {
                    $descripcion .= $value['descripcion'] . ", ";
                }
                $sheet->setCellValue("B37", $descripcion);
                break;
        }
    }

    public function enfermedadActual($enfermedadActual, $sheet, $tipo)
    {
        switch ($tipo) {
            case 'INGRESO':
                $descripcion = "Descripción: " . " \n";
                foreach ($enfermedadActual as $value) {
                    $descripcion .= $value['descripcion'] . ", ";
                }
                $sheet->setCellValue("B42", $descripcion);
                break;
            case 'REINGRESO':
                $descripcion = "Descripción: " . " \n";
                foreach ($enfermedadActual as $value) {
                    $descripcion .= $value['descripcion'] . ", ";
                }
                $sheet->setCellValue("B14", $descripcion);
                break;
            case 'ATENCIONES DIARIAS':
                $descripcion = "Descripción: ";
                foreach ($enfermedadActual as $value) {
                    $descripcion .= $value['descripcion'] . ", ";
                }
                $sheet->setCellValue("B6", $descripcion);
                break;
            default:
                $descripcion = "Descripción: " . " \n";
                foreach ($enfermedadActual as $value) {
                    $descripcion .= $value['descripcion'] . ", ";
                }
                $sheet->setCellValue("B42", $descripcion);
                break;
        }
    }

    private function antecedenteTrabajo($data, $sheet_1, $tipo)
    {
        switch ($tipo) {
            case 'INGRESO':
                $cell = 45;
                foreach ($data as $value) {
                    $sheet_1->setCellValue("B" . $cell, $value['empresa']);
                    $sheet_1->setCellValue("L" . $cell, $value['puesto_trabajo']);
                    $sheet_1->setCellValue("T" . $cell, $value['actividades_desempenadas']);
                    $sheet_1->setCellValue("AG" . $cell, $value['tiempo_trabajo']);
                    $array = explode(",", $value['descripcion']);
                    foreach ($array as $id) {
                        $descripcion = parametro::select('descripcion')->where('id', $id)->first()->descripcion;
                        switch ($descripcion) {
                            case 'FISICO':
                                $sheet_1->setCellValue("AK" . $cell, "X");
                                break;
                            case 'MECANICO':
                                $sheet_1->setCellValue("AL" . $cell, "X");
                                break;
                            case 'QUIMICO':
                                $sheet_1->setCellValue("AM" . $cell, "X");
                                break;
                            case 'BIOLÓGICO':
                                $sheet_1->setCellValue("AN" . $cell, "X");
                                break;
                            case 'ERGONOMICO':
                                $sheet_1->setCellValue("AO" . $cell, "X");
                                break;
                            case 'PSICOSOCIAL':
                                $sheet_1->setCellValue("AP" . $cell, "X");
                                break;
                        }
                    }
                    $sheet_1->setCellValue("AQ" . $cell, $value['observaciones']);
                    $cell++;
                }
                break;

            default:
                $cell = 45;
                foreach ($data as $value) {
                    $sheet_1->setCellValue("B" . $cell, $value['empresa']);
                    $sheet_1->setCellValue("L" . $cell, $value['puesto_trabajo']);
                    $sheet_1->setCellValue("T" . $cell, $value['actividades_desempenadas']);
                    $sheet_1->setCellValue("AG" . $cell, $value['tiempo_trabajo']);
                    $array = explode(",", $value['descripcion']);
                    foreach ($array as $id) {
                        $descripcion = parametro::select('descripcion')->where('id', $id)->first()->descripcion;
                        switch ($descripcion) {
                            case 'FISICO':
                                $sheet_1->setCellValue("AK" . $cell, "X");
                                break;
                            case 'MECANICO':
                                $sheet_1->setCellValue("AL" . $cell, "X");
                                break;
                            case 'QUIMICO':
                                $sheet_1->setCellValue("AM" . $cell, "X");
                                break;
                            case 'BIOLÓGICO':
                                $sheet_1->setCellValue("AN" . $cell, "X");
                                break;
                            case 'ERGONOMICO':
                                $sheet_1->setCellValue("AO" . $cell, "X");
                                break;
                            case 'PSICOSOCIAL':
                                $sheet_1->setCellValue("AP" . $cell, "X");
                                break;
                        }
                    }
                    $sheet_1->setCellValue("AQ" . $cell, $value['observaciones']);
                    $cell++;
                }
                break;
        }
    }

    public function antecedenteAccidente($dataAntecedenteAccidenteTrabajo, $sheet, $tipo)
    {
        switch ($tipo) {
            case 'INGRESO':
                $descripcion = "Descripción: " . " \n";
                foreach ($dataAntecedenteAccidenteTrabajo as $value) {

                    $arrayFecha = explode("-", $value['fecha_accidente']);

                    $value['calificado_accidente'] == true ? $sheet->setCellValue("AA51", "X") : $sheet->setCellValue("AO51", "X");
                    $sheet->setCellValue("AG51", !is_null($value['especificar_accidente']) ? $value['especificar_accidente'] : '');


                    $sheet->setCellValue("AY51", !is_null($value['fecha_accidente']) ? $arrayFecha[0] : '');
                    $sheet->setCellValue("BA51", !is_null($value['fecha_accidente']) ? $arrayFecha[1] : '');
                    $sheet->setCellValue("BC51", !is_null($value['fecha_accidente']) ? $arrayFecha[2] : '');


                    $descripcion .= $value['observaciones_accidente'];
                }
                $sheet->setCellValue("B53", $descripcion);
                break;
            case 'RETIRO':
                $descripcion = "Descripción: " . " \n";
                foreach ($dataAntecedenteAccidenteTrabajo as $value) {

                    $arrayFecha = explode("-", $value['fecha_accidente']);

                    $value['calificado_accidente'] == true ? $sheet->setCellValue("AA20", "X") : $sheet->setCellValue("AQ20", "X");
                    $sheet->setCellValue("AJ20", !is_null($value['especificar_accidente']) ? $value['especificar_accidente'] : '');


                    $sheet->setCellValue("BA20", !is_null($value['fecha_accidente']) ? $arrayFecha[0] : '');
                    $sheet->setCellValue("BC20", !is_null($value['fecha_accidente']) ? $arrayFecha[1] : '');
                    $sheet->setCellValue("BE20", !is_null($value['fecha_accidente']) ? $arrayFecha[2] : '');


                    $descripcion .= $value['observaciones_accidente'];
                }
                $sheet->setCellValue("B22", $descripcion);
                break;
            default:
                $observaciones = "Observaciones: " . " \n";
                foreach ($dataAntecedenteAccidenteTrabajo as $value) {

                    $arrayFecha = explode("-", $value['fecha_accidente']);

                    $value['calificado_accidente'] == true ? $sheet->setCellValue("AA51", "X") : $sheet->setCellValue("AO51", "X");
                    $sheet->setCellValue("AG51", !is_null($value['especificar_accidente']) ? $value['especificar_accidente'] : '');


                    $sheet->setCellValue("AY51", !is_null($value['fecha_accidente']) ? $arrayFecha[0] : '');
                    $sheet->setCellValue("BA51", !is_null($value['fecha_accidente']) ? $arrayFecha[1] : '');
                    $sheet->setCellValue("BC51", !is_null($value['fecha_accidente']) ? $arrayFecha[2] : '');


                    $observaciones .= $value['observaciones_accidente'];
                }
                $sheet->setCellValue("B53", $observaciones);
                break;
        }
    }

    public function antecedenteEnfermedad($dataAntecedenteEnfermedadProfesionales, $sheet, $tipo)
    {
        switch ($tipo) {
            case 'INGRESO':
                $descripcion = "Descripción: " . " \n";
                foreach ($dataAntecedenteEnfermedadProfesionales as $value) {

                    $arrayFecha = explode("-", $value['fecha_enfermedad']);

                    $value['calificado_enfermedad'] == true ? $sheet->setCellValue("AA57", "X") : $sheet->setCellValue("AO57", "X");
                    $sheet->setCellValue("AG57", !is_null($value['especificar_enfermedad']) ? $value['especificar_enfermedad'] : '');


                    $sheet->setCellValue("AY57", !is_null($value['fecha_enfermedad']) ? $arrayFecha[0] : '');
                    $sheet->setCellValue("BA57", !is_null($value['fecha_enfermedad']) ? $arrayFecha[1] : '');
                    $sheet->setCellValue("BC57", !is_null($value['fecha_enfermedad']) ? $arrayFecha[2] : '');


                    $descripcion .= $value['observaciones_enfermedad'];
                }
                $sheet->setCellValue("B59", $descripcion);
                break;
            case 'RETIRO':
                $descripcion = "Descripción: " . " \n";
                foreach ($dataAntecedenteEnfermedadProfesionales as $value) {

                    $arrayFecha = explode("-", $value['fecha_enfermedad']);

                    $value['calificado_enfermedad'] == true ? $sheet->setCellValue("AC29", "X") : $sheet->setCellValue("AR29", "X");
                    $sheet->setCellValue("AJ29", !is_null($value['especificar_enfermedad']) ? $value['especificar_enfermedad'] : '');


                    $sheet->setCellValue("BA29", !is_null($value['fecha_enfermedad']) ? $arrayFecha[0] : '');
                    $sheet->setCellValue("BC29", !is_null($value['fecha_enfermedad']) ? $arrayFecha[1] : '');
                    $sheet->setCellValue("BE29", !is_null($value['fecha_enfermedad']) ? $arrayFecha[2] : '');


                    $descripcion .= $value['observaciones_enfermedad'];
                }
                $sheet->setCellValue("B31", $descripcion);
                break;
            default:
                $observaciones = "Observaciones: " . " \n";
                foreach ($dataAntecedenteEnfermedadProfesionales as $value) {

                    $arrayFecha = explode("-", $value['fecha_enfermedad']);

                    $value['calificado_enfermedad'] == true ? $sheet->setCellValue("AA57", "X") : $sheet->setCellValue("AO57", "X");
                    $sheet->setCellValue("AG57", !is_null($value['especificar_enfermedad']) ? $value['especificar_enfermedad'] : '');


                    $sheet->setCellValue("AY57", !is_null($value['fecha_enfermedad']) ? $arrayFecha[0] : '');
                    $sheet->setCellValue("BA57", !is_null($value['fecha_enfermedad']) ? $arrayFecha[1] : '');
                    $sheet->setCellValue("BC57", !is_null($value['fecha_enfermedad']) ? $arrayFecha[2] : '');


                    $observaciones .= $value['observaciones_enfermedad'];
                }
                $sheet->setCellValue("B59", $observaciones);
                break;
        }
    }

    public function revisionActual($dataRevisionActual, $sheet, $tipo)
    {

        switch ($tipo) {
            case 'INGRESO':
                $descripcion = "Descripcion: " . " \n";
                foreach ($dataRevisionActual as $value) {

                    if ($value['detalle'] == 'PIEL - ANEXOS') {
                        $sheet->setCellValue("L47", "X");
                        $descripcion .= "1 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == 'ÓRGANOS DE LOS SENTIDOS') {
                        $sheet->setCellValue("L48", "X");
                        $descripcion .= "2 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == 'RESPIRATORIO') {
                        $sheet->setCellValue("W47", "X");
                        $descripcion .= "3 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == 'CARDIO-VASCULAR') {
                        $sheet->setCellValue("W48", "X");
                        $descripcion .= "4 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == 'DIGESTIVO') {
                        $sheet->setCellValue("AH47", "X");
                        $descripcion .= "5 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == 'GENITO - URINARIO') {
                        $sheet->setCellValue("AH48", "X");
                        $descripcion .= "6 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == 'MÚSCULO ESQUELÉTICO') {
                        $sheet->setCellValue("AR47", "X");
                        $descripcion .= "7 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == 'ENDOCRINO') {
                        $sheet->setCellValue("AR48", "X");
                        $descripcion .= "8 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == 'HEMO LINFÁTICO') {
                        $sheet->setCellValue("BG47", "X");
                        $descripcion .= "9 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == 'NERVIOSO') {
                        $sheet->setCellValue("BG48", "X");
                        $descripcion .= "10 - " . $value['descripcion'] . " \n";
                    }
                }
                $sheet->setCellValue("B49", $descripcion);
                break;
            default:
                $descripcion = "Descripcion: " . " \n";
                foreach ($dataRevisionActual as $value) {

                    if ($value['detalle'] == 'PIEL - ANEXOS') {
                        $sheet->setCellValue("L47", "X");
                        $descripcion .= "1 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == 'ÓRGANOS DE LOS SENTIDOS') {
                        $sheet->setCellValue("L48", "X");
                        $descripcion .= "2 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == 'RESPIRATORIO') {
                        $sheet->setCellValue("W47", "X");
                        $descripcion .= "3 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == 'CARDIO-VASCULAR') {
                        $sheet->setCellValue("W48", "X");
                        $descripcion .= "4 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == 'DIGESTIVO') {
                        $sheet->setCellValue("AH47", "X");
                        $descripcion .= "5 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == 'GENITO - URINARIO') {
                        $sheet->setCellValue("AH48", "X");
                        $descripcion .= "6 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == 'MÚSCULO ESQUELÉTICO') {
                        $sheet->setCellValue("AR47", "X");
                        $descripcion .= "7 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == 'ENDOCRINO') {
                        $sheet->setCellValue("AR48", "X");
                        $descripcion .= "8 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == 'HEMO LINFÁTICO') {
                        $sheet->setCellValue("BG47", "X");
                        $descripcion .= "9 - " . $value['descripcion'] . " \n";
                    }
                    if ($value['detalle'] == 'NERVIOSO') {
                        $sheet->setCellValue("BG48", "X");
                        $descripcion .= "10 - " . $value['descripcion'] . " \n";
                    }
                }
                $sheet->setCellValue("B49", $descripcion);
                break;
        }
    }

    public function constantesVitales($dataConstantesVitales, $sheet, $tipo)
    {
        switch ($tipo) {
            case 'INGRESO':
                foreach ($dataConstantesVitales as $value) {
                    $sheet->setCellValue("B57", !is_null($value['presion_arterial']) ? $value['presion_arterial'] : '');
                    $sheet->setCellValue("I57", !is_null($value['temperatura']) ? $value['temperatura'] : '');
                    $sheet->setCellValue("P57", !is_null($value['frecuencia_cardiaca']) ? $value['frecuencia_cardiaca'] : '');
                    $sheet->setCellValue("W57", !is_null($value['saturacion_oxigeno']) ? $value['saturacion_oxigeno'] : '');
                    $sheet->setCellValue("AC57", !is_null($value['frecuencia_respiratoria']) ? $value['frecuencia_respiratoria'] : '');
                    $sheet->setCellValue("AI57", !is_null($value['peso']) ? $value['peso'] : '');
                    $sheet->setCellValue("AN57", !is_null($value['talla']) ? $value['talla'] : '');
                    $sheet->setCellValue("AT57", !is_null($value['indice_masa_corporal']) ? $value['indice_masa_corporal'] : '');
                    $sheet->setCellValue("BA57", !is_null($value['perimetro_abdominal']) ? $value['perimetro_abdominal'] : '');
                }
                break;
            case 'RETIRO':
                foreach ($dataConstantesVitales as $value) {
                    $sheet->setCellValue("B39", !is_null($value['presion_arterial']) ? $value['presion_arterial'] : '');
                    $sheet->setCellValue("I39", !is_null($value['temperatura']) ? $value['temperatura'] : '');
                    $sheet->setCellValue("P39", !is_null($value['frecuencia_cardiaca']) ? $value['frecuencia_cardiaca'] : '');
                    $sheet->setCellValue("W39", !is_null($value['saturacion_oxigeno']) ? $value['saturacion_oxigeno'] : '');
                    $sheet->setCellValue("AC39", !is_null($value['frecuencia_respiratoria']) ? $value['frecuencia_respiratoria'] : '');
                    $sheet->setCellValue("AI39", !is_null($value['peso']) ? $value['peso'] : '');
                    $sheet->setCellValue("AO39", !is_null($value['talla']) ? $value['talla'] : '');
                    $sheet->setCellValue("AU39", !is_null($value['indice_masa_corporal']) ? $value['indice_masa_corporal'] : '');
                    $sheet->setCellValue("BB39", !is_null($value['perimetro_abdominal']) ? $value['perimetro_abdominal'] : '');
                }
                break;
            case 'REINGRESO':
                foreach ($dataConstantesVitales as $value) {
                    $sheet->setCellValue("B19", !is_null($value['presion_arterial']) ? $value['presion_arterial'] : '');
                    $sheet->setCellValue("I19", !is_null($value['temperatura']) ? $value['temperatura'] : '');
                    $sheet->setCellValue("P19", !is_null($value['frecuencia_cardiaca']) ? $value['frecuencia_cardiaca'] : '');
                    $sheet->setCellValue("W19", !is_null($value['saturacion_oxigeno']) ? $value['saturacion_oxigeno'] : '');
                    $sheet->setCellValue("AC19", !is_null($value['frecuencia_respiratoria']) ? $value['frecuencia_respiratoria'] : '');
                    $sheet->setCellValue("AI19", !is_null($value['peso']) ? $value['peso'] : '');
                    $sheet->setCellValue("AN19", !is_null($value['talla']) ? $value['talla'] : '');
                    $sheet->setCellValue("AT19", !is_null($value['indice_masa_corporal']) ? $value['indice_masa_corporal'] : '');
                    $sheet->setCellValue("BA19", !is_null($value['perimetro_abdominal']) ? $value['perimetro_abdominal'] : '');
                }
                break;
            default:
                foreach ($dataConstantesVitales as $value) {
                    $sheet->setCellValue("B57", !is_null($value['presion_arterial']) ? $value['presion_arterial'] : '');
                    $sheet->setCellValue("I57", !is_null($value['temperatura']) ? $value['temperatura'] : '');
                    $sheet->setCellValue("P57", !is_null($value['frecuencia_cardiaca']) ? $value['frecuencia_cardiaca'] : '');
                    $sheet->setCellValue("W57", !is_null($value['saturacion_oxigeno']) ? $value['saturacion_oxigeno'] : '');
                    $sheet->setCellValue("AC57", !is_null($value['frecuencia_respiratoria']) ? $value['frecuencia_respiratoria'] : '');
                    $sheet->setCellValue("AI57", !is_null($value['peso']) ? $value['peso'] : '');
                    $sheet->setCellValue("AN57", !is_null($value['talla']) ? $value['talla'] : '');
                    $sheet->setCellValue("AT57", !is_null($value['indice_masa_corporal']) ? $value['indice_masa_corporal'] : '');
                    $sheet->setCellValue("BA57", !is_null($value['perimetro_abdominal']) ? $value['perimetro_abdominal'] : '');
                }
                break;
        }
    }

    public function examenFisicoRegional($dataExamenenFisicioRegional, $sheet, $tipo)
    {
        switch ($tipo) {
            case 'INGRESO':
                $descripciones = "Observaciones: " . " \n";
                foreach ($dataExamenenFisicioRegional as $value) {

                    $arrayDetalles = explode(",", $value['detalles']);

                    foreach ($arrayDetalles as $id) {
                        $descripcion = parametro::select('descripcion')->where('id', $id)->first()->descripcion;
                        switch ($descripcion) {
                            case 'CICATRICES':
                                $sheet->setCellValue("K5", "X");
                                break;
                            case 'TATUAJES':
                                $sheet->setCellValue("K6", "X");
                                break;
                            case 'PIEL y FANERAS':
                                $sheet->setCellValue("K7", "X");
                                break;
                            case 'PARPADOS':
                                $sheet->setCellValue("K8", "X");
                                break;
                            case 'CONJUNTIVAS':
                                $sheet->setCellValue("K9", "X");
                                break;
                            case 'PUPILAS':
                                $sheet->setCellValue("K10", "X");
                                break;
                            case 'CORNEA':
                                $sheet->setCellValue("K11", "X");
                                break;
                            case 'MOTILIDAD':
                                $sheet->setCellValue("K12", "X");
                                break;
                            case 'AUDITIVO EXTERNO':
                                $sheet->setCellValue("V5", "X");
                                break;
                            case 'PABELLON':
                                $sheet->setCellValue("V6", "X");
                                break;
                            case 'TIMPANOS':
                                $sheet->setCellValue("V7", "X");
                                break;
                            case 'LABIOS':
                                $sheet->setCellValue("V8", "X");
                                break;
                            case 'LENGUA':
                                $sheet->setCellValue("V9", "X");
                                break;
                            case 'FARINGE':
                                $sheet->setCellValue("V10", "X");
                                break;
                            case 'AMIGDALAS':
                                $sheet->setCellValue("V11", "X");
                                break;
                            case 'DENTADURA':
                                $sheet->setCellValue("V12", "X");
                                break;
                            case 'TABIQUE':
                                $sheet->setCellValue("AI5", "X");
                                break;
                            case 'CORNETES':
                                $sheet->setCellValue("AI6", "X");
                                break;
                            case 'MUCOSAS':
                                $sheet->setCellValue("AI7", "X");
                                break;
                            case 'SENOS PARANASALES':
                                $sheet->setCellValue("AI8", "X");
                                break;
                            case 'TIROIDES/MASAS':
                                $sheet->setCellValue("AI9", "X");
                                break;
                            case 'MOVILIDAD':
                                $sheet->setCellValue("AI10", "X");
                                break;
                            case 'MAMAS':
                                $sheet->setCellValue("AI11", "X");
                                break;
                            case 'CORAZON':
                                $sheet->setCellValue("AI12", "X");
                                break;
                            case 'PULMONES':
                                $sheet->setCellValue("AT5", "X");
                                break;
                            case 'PARRILLA COSTAL':
                                $sheet->setCellValue("AT6", "X");
                                break;
                            case 'VISCERAS':
                                $sheet->setCellValue("AT7", "X");
                                break;
                            case 'PARED ABDOMINAL':
                                $sheet->setCellValue("AT8", "X");
                                break;
                            case 'FLEXIBILIDAD':
                                $sheet->setCellValue("AT9", "X");
                                break;
                            case 'DESVIACION':
                                $sheet->setCellValue("AT10", "X");
                                break;
                            case 'DOLOR':
                                $sheet->setCellValue("AT12", "X");
                                break;
                            case 'PELVIS':
                                $sheet->setCellValue("BF5", "X");
                                break;
                            case 'GENITALES':
                                $sheet->setCellValue("BF6", "X");
                                break;
                            case 'VASCULAR':
                                $sheet->setCellValue("BF7", "X");
                                break;
                            case 'MIEMBROS SUPERIORES':
                                $sheet->setCellValue("BF8", "X");
                                break;
                            case 'MIEMBROS INFERIORES':
                                $sheet->setCellValue("BF9", "X");
                                break;
                            case 'FUERZA':
                                $sheet->setCellValue("BF10", "X");
                                break;
                            case 'SENSIBILIDAD':
                                $sheet->setCellValue("BF11", "X");
                                break;
                            case 'MARCHA':
                                $sheet->setCellValue("BF12", "X");
                                break;
                            case 'REFLEJOS':
                                $sheet->setCellValue("BF13", "X");
                                break;
                        }
                    }
                    $descripciones .= $value['descripcion'];
                }
                $sheet->setCellValue("B14", $descripciones);
                break;
            case 'RETIRO':
                $descripciones = "Descripción: " . " \n";
                foreach ($dataExamenenFisicioRegional as $value) {

                    $arrayDetalles = explode(",", $value['detalles']);

                    foreach ($arrayDetalles as $id) {
                        $descripcion = parametro::select('descripcion')->where('id', $id)->first()->descripcion;
                        switch ($descripcion) {
                            case 'CICATRICES':
                                $sheet->setCellValue("K43", "X");
                                break;
                            case 'TATUAJES':
                                $sheet->setCellValue("K44", "X");
                                break;
                            case 'PIEL y FANERAS':
                                $sheet->setCellValue("K45", "X");
                                break;
                            case 'PARPADOS':
                                $sheet->setCellValue("K46", "X");
                                break;
                            case 'CONJUNTIVAS':
                                $sheet->setCellValue("K47", "X");
                                break;
                            case 'PUPILAS':
                                $sheet->setCellValue("K48", "X");
                                break;
                            case 'CORNEA':
                                $sheet->setCellValue("K49", "X");
                                break;
                            case 'MOTILIDAD':
                                $sheet->setCellValue("K50", "X");
                                break;
                            case 'AUDITIVO EXTERNO':
                                $sheet->setCellValue("V43", "X");
                                break;
                            case 'PABELLON':
                                $sheet->setCellValue("V44", "X");
                                break;
                            case 'TIMPANOS':
                                $sheet->setCellValue("V45", "X");
                                break;
                            case 'LABIOS':
                                $sheet->setCellValue("V46", "X");
                                break;
                            case 'LENGUA':
                                $sheet->setCellValue("V47", "X");
                                break;
                            case 'FARINGE':
                                $sheet->setCellValue("V48", "X");
                                break;
                            case 'AMIGDALAS':
                                $sheet->setCellValue("V49", "X");
                                break;
                            case 'DENTADURA':
                                $sheet->setCellValue("V50", "X");
                                break;
                            case 'TABIQUE':
                                $sheet->setCellValue("AI43", "X");
                                break;
                            case 'CORNETES':
                                $sheet->setCellValue("AI44", "X");
                                break;
                            case 'MUCOSAS':
                                $sheet->setCellValue("AI45", "X");
                                break;
                            case 'SENOS PARANASALES':
                                $sheet->setCellValue("AI46", "X");
                                break;
                            case 'TIROIDES/MASAS':
                                $sheet->setCellValue("AI47", "X");
                                break;
                            case 'MOVILIDAD':
                                $sheet->setCellValue("AI48", "X");
                                break;
                            case 'MAMAS':
                                $sheet->setCellValue("AI49", "X");
                                break;
                            case 'CORAZON':
                                $sheet->setCellValue("AI50", "X");
                                break;
                            case 'PULMONES':
                                $sheet->setCellValue("AU43", "X");
                                break;
                            case 'PARRILLA COSTAL':
                                $sheet->setCellValue("AU44", "X");
                                break;
                            case 'VISCERAS':
                                $sheet->setCellValue("AU45", "X");
                                break;
                            case 'PARED ABDOMINAL':
                                $sheet->setCellValue("AU46", "X");
                                break;
                            case 'FLEXIBILIDAD':
                                $sheet->setCellValue("AU47", "X");
                                break;
                            case 'DESVIACION':
                                $sheet->setCellValue("AU48", "X");
                                break;
                            case 'DOLOR':
                                $sheet->setCellValue("AU50", "X");
                                break;
                            case 'PELVIS':
                                $sheet->setCellValue("BG43", "X");
                                break;
                            case 'GENITALES':
                                $sheet->setCellValue("BG44", "X");
                                break;
                            case 'VASCULAR':
                                $sheet->setCellValue("BG45", "X");
                                break;
                            case 'MIEMBROS SUPERIORES':
                                $sheet->setCellValue("BG46", "X");
                                break;
                            case 'MIEMBROS INFERIORES':
                                $sheet->setCellValue("BG47", "X");
                                break;
                            case 'FUERZA':
                                $sheet->setCellValue("BG48", "X");
                                break;
                            case 'SENSIBILIDAD':
                                $sheet->setCellValue("BG49", "X");
                                break;
                            case 'MARCHA':
                                $sheet->setCellValue("BG50", "X");
                                break;
                            case 'REFLEJOS':
                                $sheet->setCellValue("BG51", "X");
                                break;
                        }
                    }
                    $descripciones .= $value['descripcion'];
                }
                $sheet->setCellValue("B50", $descripciones);
                break;
            case 'REINGRESO':
                $descripciones = "Descripción: " . " \n";
                foreach ($dataExamenenFisicioRegional as $value) {

                    $arrayDetalles = explode(",", $value['detalles']);

                    foreach ($arrayDetalles as $id) {
                        $descripcion = parametro::select('descripcion')->where('id', $id)->first()->descripcion;
                        switch ($descripcion) {
                            case 'CICATRICES':
                                $sheet->setCellValue("M23", "X");
                                break;
                            case 'TATUAJES':
                                $sheet->setCellValue("M24", "X");
                                break;
                            case 'PIEL y FANERAS':
                                $sheet->setCellValue("M25", "X");
                                break;
                            case 'PARPADOS':
                                $sheet->setCellValue("M26", "X");
                                break;
                            case 'CONJUNTIVAS':
                                $sheet->setCellValue("M27", "X");
                                break;
                            case 'PUPILAS':
                                $sheet->setCellValue("M28", "X");
                                break;
                            case 'CORNEA':
                                $sheet->setCellValue("M29", "X");
                                break;
                            case 'MOTILIDAD':
                                $sheet->setCellValue("M30", "X");
                                break;
                            case 'AUDITIVO EXTERNO':
                                $sheet->setCellValue("Y23", "X");
                                break;
                            case 'PABELLON':
                                $sheet->setCellValue("Y24", "X");
                                break;
                            case 'TIMPANOS':
                                $sheet->setCellValue("Y25", "X");
                                break;
                            case 'LABIOS':
                                $sheet->setCellValue("Y26", "X");
                                break;
                            case 'LENGUA':
                                $sheet->setCellValue("Y27", "X");
                                break;
                            case 'FARINGE':
                                $sheet->setCellValue("Y28", "X");
                                break;
                            case 'AMIGDALAS':
                                $sheet->setCellValue("Y29", "X");
                                break;
                            case 'DENTADURA':
                                $sheet->setCellValue("Y30", "X");
                                break;
                            case 'TABIQUE':
                                $sheet->setCellValue("AI23", "X");
                                break;
                            case 'CORNETES':
                                $sheet->setCellValue("AI24", "X");
                                break;
                            case 'MUCOSAS':
                                $sheet->setCellValue("AI25", "X");
                                break;
                            case 'SENOS PARANASALES':
                                $sheet->setCellValue("AI26", "X");
                                break;
                            case 'TIROIDES/MASAS':
                                $sheet->setCellValue("AI27", "X");
                                break;
                            case 'MOVILIDAD':
                                $sheet->setCellValue("AI28", "X");
                                break;
                            case 'MAMAS':
                                $sheet->setCellValue("AI29", "X");
                                break;
                            case 'CORAZON':
                                $sheet->setCellValue("AI30", "X");
                                break;
                            case 'PULMONES':
                                $sheet->setCellValue("AU23", "X");
                                break;
                            case 'PARRILLA COSTAL':
                                $sheet->setCellValue("AU24", "X");
                                break;
                            case 'VISCERAS':
                                $sheet->setCellValue("AU25", "X");
                                break;
                            case 'PARED ABDOMINAL':
                                $sheet->setCellValue("AU26", "X");
                                break;
                            case 'FLEXIBILIDAD':
                                $sheet->setCellValue("AU27", "X");
                                break;
                            case 'DESVIACION':
                                $sheet->setCellValue("AU28", "X");
                                break;
                            case 'DOLOR':
                                $sheet->setCellValue("AU30", "X");
                                break;
                            case 'PELVIS':
                                $sheet->setCellValue("BF23", "X");
                                break;
                            case 'GENITALES':
                                $sheet->setCellValue("BF24", "X");
                                break;
                            case 'VASCULAR':
                                $sheet->setCellValue("BF25", "X");
                                break;
                            case 'MIEMBROS SUPERIORES':
                                $sheet->setCellValue("BF26", "X");
                                break;
                            case 'MIEMBROS INFERIORES':
                                $sheet->setCellValue("BF27", "X");
                                break;
                            case 'FUERZA':
                                $sheet->setCellValue("BF28", "X");
                                break;
                            case 'SENSIBILIDAD':
                                $sheet->setCellValue("BF29", "X");
                                break;
                            case 'MARCHA':
                                $sheet->setCellValue("BF30", "X");
                                break;
                            case 'REFLEJOS':
                                $sheet->setCellValue("BF31", "X");
                                break;
                        }
                    }
                    $descripciones .= $value['descripcion'];
                }
                $sheet->setCellValue("B32", $descripciones);
                break;
            case 'ATENCIONES DIARIAS':
                //dd(1);
                $descripcion = "";
                foreach ($dataExamenenFisicioRegional as $value) {
                    $descripcion = $value['descripcion'];
                }
                $sheet->setCellValue("B8", $descripcion);
                break;
            default:
                $descripciones = "Observaciones: " . " \n";
                foreach ($dataExamenenFisicioRegional as $value) {

                    $arrayDetalles = explode(",", $value['detalles']);

                    foreach ($arrayDetalles as $id) {
                        $descripcion = parametro::select('descripcion')->where('id', $id)->first()->descripcion;
                        switch ($descripcion) {
                            case 'CICATRICES':
                                $sheet->setCellValue("K5", "X");
                                break;
                            case 'TATUAJES':
                                $sheet->setCellValue("K6", "X");
                                break;
                            case 'PIEL y FANERAS':
                                $sheet->setCellValue("K7", "X");
                                break;
                            case 'PARPADOS':
                                $sheet->setCellValue("K8", "X");
                                break;
                            case 'CONJUNTIVAS':
                                $sheet->setCellValue("K9", "X");
                                break;
                            case 'PUPILAS':
                                $sheet->setCellValue("K10", "X");
                                break;
                            case 'CORNEA':
                                $sheet->setCellValue("K11", "X");
                                break;
                            case 'MOTILIDAD':
                                $sheet->setCellValue("K12", "X");
                                break;
                            case 'AUDITIVO EXTERNO':
                                $sheet->setCellValue("V5", "X");
                                break;
                            case 'PABELLON':
                                $sheet->setCellValue("V6", "X");
                                break;
                            case 'TIMPANOS':
                                $sheet->setCellValue("V7", "X");
                                break;
                            case 'LABIOS':
                                $sheet->setCellValue("V8", "X");
                                break;
                            case 'LENGUA':
                                $sheet->setCellValue("V9", "X");
                                break;
                            case 'FARINGE':
                                $sheet->setCellValue("V10", "X");
                                break;
                            case 'AMIGDALAS':
                                $sheet->setCellValue("V11", "X");
                                break;
                            case 'DENTADURA':
                                $sheet->setCellValue("V12", "X");
                                break;
                            case 'TABIQUE':
                                $sheet->setCellValue("AI5", "X");
                                break;
                            case 'CORNETES':
                                $sheet->setCellValue("AI6", "X");
                                break;
                            case 'MUCOSAS':
                                $sheet->setCellValue("AI7", "X");
                                break;
                            case 'SENOS PARANASALES':
                                $sheet->setCellValue("AI8", "X");
                                break;
                            case 'TIROIDES/MASAS':
                                $sheet->setCellValue("AI9", "X");
                                break;
                            case 'MOVILIDAD':
                                $sheet->setCellValue("AI10", "X");
                                break;
                            case 'MAMAS':
                                $sheet->setCellValue("AI11", "X");
                                break;
                            case 'CORAZON':
                                $sheet->setCellValue("AI12", "X");
                                break;
                            case 'PULMONES':
                                $sheet->setCellValue("AT5", "X");
                                break;
                            case 'PARRILLA COSTAL':
                                $sheet->setCellValue("AT6", "X");
                                break;
                            case 'VISCERAS':
                                $sheet->setCellValue("AT7", "X");
                                break;
                            case 'PARED ABDOMINAL':
                                $sheet->setCellValue("AT8", "X");
                                break;
                            case 'FLEXIBILIDAD':
                                $sheet->setCellValue("AT9", "X");
                                break;
                            case 'DESVIACION':
                                $sheet->setCellValue("AT10", "X");
                                break;
                            case 'DOLOR':
                                $sheet->setCellValue("AT12", "X");
                                break;
                            case 'PELVIS':
                                $sheet->setCellValue("BF5", "X");
                                break;
                            case 'GENITALES':
                                $sheet->setCellValue("BF6", "X");
                                break;
                            case 'VASCULAR':
                                $sheet->setCellValue("BF7", "X");
                                break;
                            case 'MIEMBROS SUPERIORES':
                                $sheet->setCellValue("BF8", "X");
                                break;
                            case 'MIEMBROS INFERIORES':
                                $sheet->setCellValue("BF9", "X");
                                break;
                            case 'FUERZA':
                                $sheet->setCellValue("BF10", "X");
                                break;
                            case 'SENSIBILIDAD':
                                $sheet->setCellValue("BF11", "X");
                                break;
                            case 'MARCHA':
                                $sheet->setCellValue("BF12", "X");
                                break;
                            case 'REFLEJOS':
                                $sheet->setCellValue("BF13", "X");
                                break;
                        }
                    }
                    $descripciones .= $value['descripcion'];
                }
                $sheet->setCellValue("B14", $descripciones);
                break;
        }
    }

    public function examenGeneralEspecifico($dataExamenenGeneralEspecifico, $sheet, $tipo)
    {
        switch ($tipo) {
            case 'INGRESO':
                $numFila = 20;

                foreach ($dataExamenenGeneralEspecifico as $value) {
                    $sheet->setCellValue("B" . $numFila, $value['descripcion']);
                    $sheet->setCellValue("M" . $numFila, $value['fecha']);
                    $sheet->setCellValue("T" . $numFila, $value['resultados']);

                    $numFila++;
                }
                break;
            case 'RETIRO':
                $numFila = 5;

                foreach ($dataExamenenGeneralEspecifico as $value) {
                    $sheet->setCellValue("B" . $numFila, $value['descripcion']);
                    $sheet->setCellValue("M" . $numFila, $value['fecha']);
                    $sheet->setCellValue("T" . $numFila, $value['resultados']);

                    $numFila++;
                }
                break;
            case 'REINGRESO':
                $numFila = 39;

                foreach ($dataExamenenGeneralEspecifico as $value) {
                    $sheet->setCellValue("B" . $numFila, $value['descripcion']);
                    $sheet->setCellValue("P" . $numFila, $value['fecha']);
                    $sheet->setCellValue("W" . $numFila, $value['resultados']);

                    $numFila++;
                }
                break;
            default:
                $numFila = 20;

                foreach ($dataExamenenGeneralEspecifico as $value) {
                    $sheet->setCellValue("B" . $numFila, $value['descripcion']);
                    $sheet->setCellValue("M" . $numFila, $value['fecha']);
                    $sheet->setCellValue("T" . $numFila, $value['resultados']);

                    $numFila++;
                }
                break;
        }
    }

    public function diagnostico($dataDiagnostico, $sheet, $tipo)
    {
        switch ($tipo) {
            case 'INGRESO':
                $numFila = 27;
                foreach ($dataDiagnostico as $value) {

                    $dx = CodigoCie::select('codigo')->where('id', $value['codigo_cie_id'])->first()->codigo;
                    $sheet->setCellValue("D" . $numFila, $value['descripcion']);
                    $sheet->setCellValue("AV" . $numFila, $dx);
                    $value['tipo'] == "PRESUNTIVO" ? $sheet->setCellValue("BD" . $numFila, "X") : $sheet->setCellValue("BF" . $numFila, "X");

                    $numFila++;
                }
                break;
            case 'RETIRO':
                $numFila = 14;
                foreach ($dataDiagnostico as $value) {

                    $dx = CodigoCie::select('codigo')->where('id', $value['codigo_cie_id'])->first()->codigo;
                    $sheet->setCellValue("D" . $numFila, $value['descripcion']);
                    $sheet->setCellValue("AT" . $numFila, $dx);
                    $value['tipo'] == "PRESUNTIVO" ? $sheet->setCellValue("BD" . $numFila, "X") : $sheet->setCellValue("BF" . $numFila, "X");

                    $numFila++;
                }
                break;
            case 'REINGRESO':
                $numFila = 45;
                foreach ($dataDiagnostico as $value) {

                    $dx = CodigoCie::select('codigo')->where('id', $value['codigo_cie_id'])->first()->codigo;
                    $sheet->setCellValue("D" . $numFila, $value['descripcion']);
                    $sheet->setCellValue("AV" . $numFila, $dx);
                    $value['tipo'] == "PRESUNTIVO" ? $sheet->setCellValue("BD" . $numFila, "X") : $sheet->setCellValue("BF" . $numFila, "X");

                    $numFila++;
                }
                break;
            case 'ATENCIONES DIARIAS':
                $numFila = 10;
                $cdgt = count($dataDiagnostico);
                if ($cdgt > 2) {
                    $numInsertRow = $cdgt - 2; //4
                    $numFilaEva = 11;
                    $i = 0;
                    $sheet->insertNewRowBefore($numFilaEva, $numInsertRow);
                    while ($i <= $numInsertRow) :
                        $sheet->mergeCells("B" . $numFilaEva . ":AH" . $numFilaEva);
                        $sheet->mergeCells("AI" . $numFilaEva . ":AL" . $numFilaEva);
                        $sheet->mergeCells("AM" . $numFilaEva . ":AQ" . $numFilaEva);
                        $sheet->mergeCells("AR" . $numFilaEva . ":BE" . $numFilaEva);
                        $numFilaEva++;
                        $i++;
                    endwhile;
                }
                foreach ($dataDiagnostico as $value) {

                    $dx = CodigoCie::select('codigo')->where('id', $value['codigo_cie_id'])->first()->codigo;
                    $sheet->setCellValue("B" . $numFila, $value['descripcion']);
                    $sheet->setCellValue("AR" . $numFila, $dx);
                    $value['tipo'] == "PRESUNTIVO" ? $sheet->setCellValue("AI" . $numFila, "X") : $sheet->setCellValue("AM" . $numFila, "X");

                    $numFila++;
                }
                break;
            case 'CERTIFICADO':

                $realizoEvaluacionMedica = count($dataDiagnostico);

                foreach ($dataDiagnostico as $value) {
                    $value['tipo'] == "PRESUNTIVO" ? $sheet->setCellValue("AA28", "X") : $sheet->setCellValue("AG28", "X");
                }
                $realizoEvaluacionMedica == 0 ? $sheet->setCellValue("AL28", "X") : '';

                break;
            default:
                $numFila = 27;
                foreach ($dataDiagnostico as $value) {

                    $dx = CodigoCie::select('codigo')->where('id', $value['codigo_cie_id'])->first()->codigo;
                    $sheet->setCellValue("D" . $numFila, $value['descripcion']);
                    $sheet->setCellValue("AV" . $numFila, $dx);
                    $value['tipo'] == "PRESUNTIVO" ? $sheet->setCellValue("BD" . $numFila, "X") : $sheet->setCellValue("BF" . $numFila, "X");

                    $numFila++;
                }
                break;
        }
    }

    public function evaluacionMedicaRetiro($dataEvaluacionMedicaRetiro, $sheet, $tipo)
    {
        switch ($tipo) {
            case 'RETIRO':
                $observaciones = "Observaciones: " . " \n";
                foreach ($dataEvaluacionMedicaRetiro as $value) {
                    $value['evaluacion_realizada'] == true ? $sheet->setCellValue("M19", "X") : $sheet->setCellValue("T19", "X");
                    $observaciones .= $value['observaciones'];
                }
                $sheet->setCellValue("B20", $observaciones);
                break;
            default:
                foreach ($dataEvaluacionMedicaRetiro as $value) {
                    $value['evaluacion_realizada'] == false ? $sheet->setCellValue("AG18", "X") : $sheet->setCellValue("AA18", "X");
                    $value['condicion_diagnostico'] == 'presuntiva' ? $sheet->setCellValue("AA20", "X") : ($value['condicion_diagnostico'] == 'definitiva' ? $sheet->setCellValue("AG20", "X") : $sheet->setCellValue("AL20", "X"));
                    $value['salud_relacionada'] == 'true' ? $sheet->setCellValue("AA22", "X") : ($value['salud_relacionada'] == 'false' ? $sheet->setCellValue("AG22", "X") : $sheet->setCellValue("AL22", "X"));
                }
                break;
        }
    }

    public function aptitudMedica($dataAptitudMedica, $sheet, $tipo)
    {
        switch ($tipo) {
            case 'INGRESO':
                foreach ($dataAptitudMedica as $value) {

                    $sheet->setCellValue("O33", $value['observacion']);
                    $sheet->setCellValue("O34", $value['limitacion']);

                    switch ($value['aptitud']) {
                        case 'APTO':
                            $sheet->setCellValue("O32", "X");
                            break;
                        case 'APTO EN OBSERVACIÓN':
                            $sheet->setCellValue("AD32", "X");
                            break;
                        case 'APTO CON LIMITACIONES':
                            $sheet->setCellValue("AR32", "X");
                            break;
                        case 'NO APTO':
                            $sheet->setCellValue("BE32", "X");
                            break;
                    }
                }
                break;
            case 'REINGRESO':
                foreach ($dataAptitudMedica as $value) {

                    $sheet->setCellValue("M51", $value['observacion']);
                    $sheet->setCellValue("M52", $value['limitacion']);
                    //$sheet->setCellValue("M53", $value['reubicacion']);

                    switch ($value['aptitud']) {
                        case 'APTO':
                            $sheet->setCellValue("M50", "X");
                            break;
                        case 'APTO EN OBSERVACIÓN':
                            $sheet->setCellValue("Z50", "X");
                            break;
                        case 'APTO CON LIMITACIONES':
                            $sheet->setCellValue("AP50", "X");
                            break;
                        case 'NO APTO':
                            $sheet->setCellValue("BE50", "X");
                            break;
                    }
                }
                break;
            case  'CERTIFICADO':
                $numFila = 20;
                foreach ($dataAptitudMedica as $value) {

                    $sheet->setCellValue("B" . $numFila, $value['observacion']);
                    //$sheet->setCellValue("M52", $value['limitacion']);
                    //$sheet->setCellValue("M53", $value['reubicacion']);

                    switch ($value['aptitud']) {
                        case 'APTO':
                            $sheet->setCellValue("J18", "X");
                            break;
                        case 'APTO EN OBSERVACIÓN':
                            $sheet->setCellValue("T18", "X");
                            break;
                        case 'APTO CON LIMITACIONES':
                            $sheet->setCellValue("AD18", "X");
                            break;
                        case 'NO APTO':
                            $sheet->setCellValue("AL18", "X");
                            break;
                    }
                }
                break;
            default:
                foreach ($dataAptitudMedica as $value) {

                    $sheet->setCellValue("O33", $value['observacion']);
                    $sheet->setCellValue("O34", $value['limitacion']);

                    switch ($value['aptitud']) {
                        case 'APTO':
                            $sheet->setCellValue("O32", "X");
                            break;
                        case 'APTO EN OBSERVACIÓN':
                            $sheet->setCellValue("AD32", "X");
                            break;
                        case 'APTO CON LIMITACIONES':
                            $sheet->setCellValue("AR32", "X");
                            break;
                        case 'NO APTO':
                            $sheet->setCellValue("BE32", "X");
                            break;
                    }
                }
                break;
        }
    }

    public function recomendacion($dataRecomendacion, $sheet, $tipo)
    {
        switch ($tipo) {
            case 'INGRESO':
                $recomendacion = "Descripción: " . " \n";

                foreach ($dataRecomendacion as $value) {
                    $recomendacion .= $value['recomendacion'];
                }
                $sheet->setCellValue("B37", $recomendacion);
                break;
            case 'RETIRO':
                $recomendacion = "Descripción: " . " \n";

                foreach ($dataRecomendacion as $value) {
                    $recomendacion .= $value['recomendacion'];
                }
                $sheet->setCellValue("B25", $recomendacion);
                break;
            case 'REINGRESO':
                $recomendacion = "Descripción: " . " \n";

                foreach ($dataRecomendacion as $value) {
                    $recomendacion .= $value['recomendacion'];
                }
                $sheet->setCellValue("B56", $recomendacion);
                break;
            case 'ATENCIONES DIARIAS':

                foreach ($dataRecomendacion as $value) {
                    $recomendacion = $value['recomendacion'];
                }
                $sheet->setCellValue("AI13", $recomendacion);
                break;
            case 'CERTIFICADO':
                $recomendacion = "Descripción: " . " \n";

                foreach ($dataRecomendacion as $value) {
                    $recomendacion .= $value['recomendacion'];
                }
                $sheet->setCellValue("B34", $recomendacion);
                break;
            default:
                $recomendacion = "Descripción: " . " \n";

                foreach ($dataRecomendacion as $value) {
                    $recomendacion .= $value['recomendacion'];
                }
                $sheet->setCellValue("B37", $recomendacion);
                break;
        }
    }

    public function recomendacionRetiro($dataRecomendacion, $sheet, $tipo)
    {
        $recomendacion = "Descripción: " . " \n";

        foreach ($dataRecomendacion as $value) {
            $recomendacion .= $value['recomendacion'];
        }
        $sheet->setCellValue("B26", $recomendacion);
    }

    public function datosProfesionalRetiro($data, $sheet, $tipo)
    {
        $nombres_apellidos = Auth::user()->nombreCompleto;
        $codigo = $data->codigo;

        $sheet->setCellValue("F35", $nombres_apellidos);
        $sheet->setCellValue("P35", $codigo);
    }

    public function datosProfesional($data, $sheet, $tipo)
    {
        switch ($tipo) {
            case 'INGRESO':
                $fecha = date("Y/m/d");
                $hora = date("H:i:s");
                $nombres_apellidos = Auth::user()->nombreCompleto;
                $codigo = $data->codigo;

                $sheet->setCellValue("E45", $fecha);
                $sheet->setCellValue("K45", $hora);
                $sheet->setCellValue("R45", $nombres_apellidos);
                $sheet->setCellValue("AC45", $codigo);
                $sheet->setCellValue("AL1", "CODIGO: " . $codigo);

                break;
            case 'RETIRO':
                $fecha = date("Y/m/d");
                $hora = date("H:i:s");
                $nombres_apellidos = Auth::user()->nombreCompleto;
                $codigo = $data->codigo;

                $sheet->setCellValue("E31", $fecha);
                $sheet->setCellValue("K31", $hora);
                $sheet->setCellValue("S31", $nombres_apellidos);
                $sheet->setCellValue("AB31", $codigo);
                break;
            case 'REINGRESO':
                $fecha = date("Y/m/d");
                $hora = date("H:i:s");
                $nombres_apellidos = Auth::user()->nombreCompleto;
                $codigo = $data->codigo;

                $sheet->setCellValue("I63", $fecha);
                $sheet->setCellValue("N63", $hora);
                $sheet->setCellValue("W63", $nombres_apellidos);
                $sheet->setCellValue("AD63", $codigo);
                break;
            case 'CERTIFICADO':

                switch ($data->tipo_evaluacion) {
                    case 'RETIRO':
                        $nombres_apellidos = Auth::user()->nombreCompleto;
                        $codigo = $data->codigo;

                        $sheet->setCellValue("F35", $nombres_apellidos);
                        $sheet->setCellValue("P35", $codigo);
                        break;
                    default:
                        $nombres_apellidos = Auth::user()->nombreCompleto;
                        $codigo = $data->codigo;

                        $sheet->setCellValue("F43", $nombres_apellidos);
                        $sheet->setCellValue("P43", $codigo);
                        break;
                }

                break;
            default:
                $fecha = date("Y/m/d");
                $hora = date("H:i:s");
                $nombres_apellidos = Auth::user()->nombreCompleto;
                $codigo = $data->codigo;

                $sheet->setCellValue("E45", $fecha);
                $sheet->setCellValue("K45", $hora);
                $sheet->setCellValue("R45", $nombres_apellidos);
                $sheet->setCellValue("AC45", $codigo);
                break;
        }
    }


    public function descargar($carpeta, $carpeta2, $archivo, $local)
    {
        $path = "/" . $carpeta . '/' . $carpeta2 . '/' . $archivo;
        ob_end_clean();
        if (Storage::disk('local_produccion')->exists($path)) {
            return Storage::disk('local_produccion')->download($path);
        }
        abort(404);
    }
}
