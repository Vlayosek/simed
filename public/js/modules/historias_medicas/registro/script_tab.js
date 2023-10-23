$(function () {
    /// cargarDatatable();
});
$("#paciente_tab").on("click", function () {
    app_paciente.consultaCombosRegistro();
});
$("#discapacidad_tab").on("click", function () {
    cargarDatatableDiscapacidad();
    app_discapacidad.limpiarDiscapacidad();
    app_discapacidad.consultaCombosRegistro();
});
$("#antecedentes_medicos_tab").on("click", function () {
    cargarDatatablAntecedentesPersonales();
    cargarDatatablAntecedentesQuirurgicos();
});
$("#antecedentes_familiares_tab").on("click", function () {
    cargarDatatableAntecedentesFamiliar();
    app_antecedentes_familiares.consultaCombosRegistroAntecedenteFamiliar();
});
$("#revision_organos_tab").on("click", function () {
    cargarDatatableRevisionOrganos();
    app_revision_organos.consultaCombosRegistroRevisionOrganos();
});

$("#actividades_extras_tab").on("click", function () {
    app_actividades_extras_enfermedades_actual.habilitaActividad = true;
    app_actividades_extras_enfermedades_actual.formCrear_externos.actividades_extras.descripcion =
        "";
    cargarDatatableExtraLaborales();
});

$("#enfermedades_actual_tab").on("click", function () {
    app_actividades_extras_enfermedades_actual.habilitaActividad = false;
    app_actividades_extras_enfermedades_actual.formCrear_externos.enfermedad_actual.descripcion =
        "";
    cargarDatatableEnfermedadActual();
});
$("#factores_riesgos_puesto_tab").on("click", function () {
    cargarDatatableFactoresRiesgosos();
    app_factores_riesgosos.consultaCombosRegistroFactoresRiegososPuestos();
});

$("#habitos_toxicos_tab").on("click", function () {
    cargarDatatableHabitos();
    app_habitos.limpiarHabitos();
    app_habitos.consultaComboHabitos();
});
$("#estilo_vida_tab").on("click", function () {
    cargarDatatableEstiloVida();
    app_estilo_vida.limpiarEstiloVida();
    app_estilo_vida.consultaComboEstiloVida();
});

$("#antecedentes_trabajo_tab").on("click", function () {
    cargarDatatableAntecedentesTrabajo();
    app_antecedentes_trabajo.limpiarAntecedentesTrabajo();
    app_antecedentes_trabajo.consultaCombosAntecedentesTrabajo();
    cargarDatatableAntecedentesAccidentesTrabajo();
    cargarDatatableAntecedentesEnfermedadesProfesionales();
});

$("#examen_fisico_regional_tab").on("click", function () {
    cargarDatatableExamenFisicoRegional();
    app_examen_fisico_regional.limpiarExamenFisicoRegional();
    app_examen_fisico_regional.consultaComboExamenFisicoRegional();
});

$("#examen_general_especifico_tab").on("click", function () {
    cargarDatatableExamenGeneralEspecifico();
    app_examen_general_especifico.limpiarExamenGeneralEspecifico();
    app_examen_general_especifico.consultaComboExamenGeneralEspecifico();
});

$("#antecedentes_gineco_tab").on("click", function () {
    cargarDatatableAntecedentesGineco();
});
$("#examenes_gineco_tab").on("click", function () {
    cargarDatatableExamenesGineco();
});
$("#antecedentes_reproductivos_tab").on("click", function () {
    cargarDatatableAntecedentesReproductivosMasculinos();
});
$("#examenes_reproductivos_tab").on("click", function () {
    cargarDatatableExamenesReproductivosMasculinos();
});
$("#diagnostico_tab").on("click", function () {
    cargarDatatableDiagnosticos();
});
$("#aptitud_medica_tab").on("click", function () {
    cargarDatatableAptitudesMedicas();
});
$("#recomendacion_tab").on("click", function () {
    cargarDatatableRecomendaciones();
});
$("#constantes_vitales_tab").on("click", function () {
    cargarDatatableConstantesVitales();
});
$("#motivo_reintegro_tab").on("click", function () {
    cargarDatatableMotivosReintegros();
});

$("#evaluacion_medica_retiro_tab").on("click", function () {
    cargarDatatableEvaluacionMedicaRetiro();
});
