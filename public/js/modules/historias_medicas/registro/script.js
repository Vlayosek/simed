$(function () {
    //bsCustomFileInput.init();
    //app.buscarSeccionesTiposEvaluaciones("INGRESO");
});
//VALIDAR CÉDULA
function reseteoConsulta() {
    app.consultaDatos = false;
    $(".erroresInput").addClass("hidden");
    app.cambioDatoConsultaDatos();
    //$("#paciente_tab").trigger("click");
    app.cedula_valida = false;
    if ($("[name='errorCedula']").text() == "Cedula válida")
        app.cedula_valida = true;
    app.consultaDatos = false;
}
$("#buscar_identificacion").on("keyup", function () {
    reseteoConsulta();
});
$("#tipo_evaluacion_id").on("change", function () {
    $(".tabs_seccion").addClass("hidden");
    if ($(this).val() != null && $(this).val() != "")
        app.buscarSeccionesTiposEvaluaciones();
});
function cargarDatatablAntecedentesPersonales() {
    let id = "dtAntecedentesPersonales";
    let ruta = "datatablAntecedentesPersonales";
    let arreglo = arregloDatosPersonalesQuirurgicos;
    formarDatatable(id, ruta, arreglo, "rti");
}
function cargarDatatablAntecedentesQuirurgicos() {
    let id = "dtAntecedentesQuirurgicos";
    let ruta = "datatablAntecedentesQuirurgicos";
    let arreglo = arregloDatosPersonalesQuirurgicos;
    formarDatatable(id, ruta, arreglo, "rti");
}
function cargarDatatableDiscapacidad() {
    let id = "dtDiscapacidad";
    let ruta = "datatableDiscapacidad";
    let arreglo = arregloDatosDiscapacidad;
    formarDatatable(id, ruta, arreglo);
}

function cargarDatatableHabitos() {
    let id = "dtHabitos";
    let ruta = "datatableHabitos";
    let arreglo = arregloDatosHabitos;
    formarDatatable(id, ruta, arreglo);
}

function cargarDatatableEstiloVida() {
    let id = "dtEstiloVida";
    let ruta = "datatableEstiloVida";
    let arreglo = arregloDatosEstiloVida;
    formarDatatable(id, ruta, arreglo);
}

$("#tipo_estilo").on("change", function () {
    var nom_tipo_estilo = $("#tipo_estilo option:selected").text();
    if (nom_tipo_estilo == "ACTIVIDAD FÍSICA") {
        app_estilo_vida.tiempo_cantidad = "Tiempo (día)";
    } else if (nom_tipo_estilo == "MEDICACIÓN HABITUAL") {
        app_estilo_vida.tiempo_cantidad = "Cantidad (unidad)";
    } else {
        app_estilo_vida.tiempo_cantidad = "";
    }
});

function cargarDatatableExamenFisicoRegional() {
    let id = "dtExamenFisicoRegional";
    let ruta = "datatableExamenFisicoRegional";
    let arreglo = arregloDatosExamenFisicoRegional;
    formarDatatable(id, ruta, arreglo);
}

function cargarDatatableExamenGeneralEspecifico() {
    let id = "dtExamenGeneralEspecifico";
    let ruta = "datatableExamenGeneralEspecifico";
    let arreglo = arregloDatosExamenGeneralEspecifico;
    formarDatatable(id, ruta, arreglo);
}

function cargarDatatableAntecedentesFamiliar() {
    let id = "dt_antecedentes_familiares";
    let ruta = "datatableAntecedentesFamiliar";
    let arreglo = arregloDatosAntecedentesFamiliares;
    formarDatatable(id, ruta, arreglo);
}
function cargarDatatableFactoresRiesgosos() {
    let id = "dt_factores_riesgosos";
    let ruta = "datatableFactoresRiesgosos";
    let arreglo = arregloDatosFactoresRiesgosos;
    formarDatatable(id, ruta, arreglo);
}
function cargarDatatableRevisionOrganos() {
    let id = "dt_revision_organos";
    let ruta = "cargarDatatableRevisionOrganos";
    let arreglo = arregloDatosRevisionOrganos;
    formarDatatable(id, ruta, arreglo);
}
function cargarDatatableExtraLaborales() {
    let id = "dt_actividades_extras";
    let ruta = "cargarDatatableExtraLaborales";
    let arreglo = arregloDatosActividadesExtras;
    formarDatatable(id, ruta, arreglo, "lfrtip");
}
function cargarDatatableEnfermedadActual() {
    let id = "dt_enfermedad_actual";
    let ruta = "cargarDatatableEnfermedadActual";
    let arreglo = arregloDatosEnfermedadesActuales;
    formarDatatable(id, ruta, arreglo, "lfrtip");
}
/* ANTECEDENTES GINECO OBSTETRICO */
function cargarDatatableAntecedentesGineco() {
    let id = "dt_antecedentes_gineco";
    let ruta = "datatableAntecedentesGineco";
    let arreglo = arregloDatosAntecedentesGineco;
    formarDatatable(id, ruta, arreglo);
}
$("input:radio[name=vida_sexual]").on("change", function () {
    if ($("input:radio[name=vida_sexual]:checked").val() == "true")
        app_antecedentes_gineco.formAntecedenteGineco.vida_sexual = true;
    if ($("input:radio[name=vida_sexual]:checked").val() == "false")
        app_antecedentes_gineco.formAntecedenteGineco.vida_sexual = false;
});

$("input:radio[name=planificacion_familiar]").on("change", function () {
    if ($("input:radio[name=planificacion_familiar]:checked").val() == "true") {
        app_antecedentes_gineco.formAntecedenteGineco.planificacion_familiar = true;
        app_antecedentes_gineco.formAntecedenteGineco.tipo_planificacion_familiar =
            "";
    }
    if (
        $("input:radio[name=planificacion_familiar]:checked").val() == "false"
    ) {
        app_antecedentes_gineco.formAntecedenteGineco.planificacion_familiar = false;
        app_antecedentes_gineco.formAntecedenteGineco.tipo_planificacion_familiar =
            "No aplica";
    }
});

/* EXAMENES GINECO OBSTETRICO */
function cargarDatatableExamenesGineco() {
    let id = "dt_examenes_gineco";
    let ruta = "datatableExamenesGineco";
    let arreglo = arregloDatosExamenesGineco;
    formarDatatable(id, ruta, arreglo);
}
$("input:radio[name=examen_gineco]").on("change", function () {
    if ($("input:radio[name=examen_gineco]:checked").val() == "true") {
        app_examenes_gineco.formExamenGineco.realizo_examen = true;
        app_examenes_gineco.formExamenGineco.tiempo = "";
        app_examenes_gineco.formExamenGineco.resultado = "";
    }
    if ($("input:radio[name=examen_gineco]:checked").val() == "false") {
        app_examenes_gineco.formExamenGineco.realizo_examen = false;
        app_examenes_gineco.formExamenGineco.tiempo = 0;
        app_examenes_gineco.formExamenGineco.resultado = "No aplica";
    }
});

$("input:radio[name=evaluacion_medica]").on("change", function () {
    if ($("input:radio[name=evaluacion_medica]:checked").val() == "true") {
        app_evaluacion_medica_retiro.formEvaluacionMedicaRetiro.evaluacion_realizada = true;
        app_evaluacion_medica_retiro.formEvaluacionMedicaRetiro.observaciones =
            "";
    }
    if ($("input:radio[name=evaluacion_medica]:checked").val() == "false") {
        app_evaluacion_medica_retiro.formEvaluacionMedicaRetiro.evaluacion_realizada = false;
        app_evaluacion_medica_retiro.formEvaluacionMedicaRetiro.observaciones =
            "";
    }
});

$("input:radio[name=condicion_diagnostico]").on("change", function () {
    if (
        $("input:radio[name=condicion_diagnostico]:checked").val() ==
        "presuntiva"
    ) {
        app_evaluacion_medica_retiro.formEvaluacionMedicaRetiro.condicion_diagnostico =
            "presuntiva";
    }
    if (
        $("input:radio[name=condicion_diagnostico]:checked").val() ==
        "definitiva"
    ) {
        app_evaluacion_medica_retiro.formEvaluacionMedicaRetiro.condicion_diagnostico =
            "definitiva";
    }
    if (
        $("input:radio[name=condicion_diagnostico]:checked").val() ==
        "no_aplica"
    ) {
        app_evaluacion_medica_retiro.formEvaluacionMedicaRetiro.condicion_diagnostico =
            "no_aplica";
    }
});

$("input:radio[name=salud_relacionada]").on("change", function () {
    if ($("input:radio[name=salud_relacionada]:checked").val() == "true") {
        app_evaluacion_medica_retiro.formEvaluacionMedicaRetiro.salud_relacionada =
            "true";
    }
    if ($("input:radio[name=salud_relacionada]:checked").val() == "false") {
        app_evaluacion_medica_retiro.formEvaluacionMedicaRetiro.salud_relacionada =
            "false";
    }
    if ($("input:radio[name=salud_relacionada]:checked").val() == "no_aplica") {
        app_evaluacion_medica_retiro.formEvaluacionMedicaRetiro.salud_relacionada =
            "no_aplica";
    }
});

/* $("input:radio[name=examen_gineco]").on("change", function () {
    if ($("input:radio[name=examen_gineco]:checked").val() == "true") {
        app_examenes_gineco.formExamenGineco.realizo_examen = true;
        app_examenes_gineco.formExamenGineco.tiempo = "";
        app_examenes_gineco.formExamenGineco.resultado = "";
    }
    if ($("input:radio[name=examen_gineco]:checked").val() == "false") {
        app_examenes_gineco.formExamenGineco.realizo_examen = false;
        app_examenes_gineco.formExamenGineco.tiempo = 0;
        app_examenes_gineco.formExamenGineco.resultado = "No aplica";
    }
}); */

/* ANTECEDENTES REPRODUCTIVOS MASCULINOS */
function cargarDatatableAntecedentesReproductivosMasculinos() {
    let id = "dt_antecedentes_reproductivos";
    let ruta = "datatableAntecedentesReproductivosMasculinos";
    let arreglo = arregloDatosAntecedentesReproductivosMasculinos;
    formarDatatable(id, ruta, arreglo);
}

$("input:radio[name=planificacion_familiar_reproductivo]").on(
    "change",
    function () {
        if (
            $(
                "input:radio[name=planificacion_familiar_reproductivo]:checked"
            ).val() == "true"
        ) {
            app_antecedentes_reproductivos_masculinos.formAntecedenteReproductivo.planificacion_familiar = true;
            app_antecedentes_reproductivos_masculinos.formAntecedenteReproductivo.tipo_planificacion_familiar =
                "";
        }
        if (
            $(
                "input:radio[name=planificacion_familiar_reproductivo]:checked"
            ).val() == "false"
        ) {
            app_antecedentes_reproductivos_masculinos.formAntecedenteReproductivo.planificacion_familiar = false;
            app_antecedentes_reproductivos_masculinos.formAntecedenteReproductivo.tipo_planificacion_familiar =
                "No aplica";
        }
    }
);
/* EXAMENES REPRODUCTIVOS MASCULINOS*/
function cargarDatatableExamenesReproductivosMasculinos() {
    let id = "dt_examenes_reproductivos";
    let ruta = "datatableExamenesReproductivosMasculinos";
    let arreglo = arregloDatosExamenesReproductivosMasculinos;
    formarDatatable(id, ruta, arreglo);
}
$("input:radio[name=examen_reproductivo]").on("change", function () {
    if ($("input:radio[name=examen_reproductivo]:checked").val() == "true") {
        app_examenes_reproductivos_masculinos.formExamenReproductivo.realizo_examen = true;
        app_examenes_reproductivos_masculinos.formExamenReproductivo.tiempo =
            "";
        app_examenes_reproductivos_masculinos.formExamenReproductivo.resultado =
            "";
    }
    if ($("input:radio[name=examen_reproductivo]:checked").val() == "false") {
        app_examenes_reproductivos_masculinos.formExamenReproductivo.realizo_examen = false;
        app_examenes_reproductivos_masculinos.formExamenReproductivo.tiempo = 0;
        app_examenes_reproductivos_masculinos.formExamenReproductivo.resultado =
            "No aplica";
    }
});

/* DIAGNOSTICO */
function cargarDatatableDiagnosticos() {
    let id = "dt_diagnosticos";
    let ruta = "datatableDiagnosticos";
    let arreglo = arregloDatosDiagnosticos;
    formarDatatable(id, ruta, arreglo);
}

$("input:radio[name=tipo_diagnostico]").on("change", function () {
    if ($("input:radio[name=tipo_diagnostico]:checked").val() == "presuntivo")
        app_diagnostico.formDiagnostico.tipo = "PRESUNTIVO";
    if ($("input:radio[name=tipo_diagnostico]:checked").val() == "definitivo")
        app_diagnostico.formDiagnostico.tipo = "DEFINITIVO";
});
$("#codigo_cie").on("change", function () {
    app_diagnostico.formDiagnostico.codigo_cie_id =
        $(this).val() == null ? "" : $(this).val();
    app_diagnostico.formDiagnostico.cie_descripcion = $(
        "#codigo_cie option:selected"
    ).text();
});

$("#codigo_ciuo").on("change", function () {
    app_paciente.formCrear.paciente.puesto_trabajo_id =
        $(this).val() == null ? "" : $(this).val();
    app_paciente.formCrear.paciente.puesto_trabajo = $(
        "#codigo_ciuo option:selected"
    ).text();
});

$("#codigo_ciiu").on("change", function () {
    app_datos.formCrear.ciiu = $(this).val() == null ? "" : $(this).val();
    app_datos.formCrear.ciiu_descripcion = $(
        "#codigo_ciiu option:selected"
    ).text();
});

/* APTITUDES MEDICAS */
function cargarDatatableAptitudesMedicas() {
    let id = "dt_aptitudes_medicas";
    let ruta = "datatableAptitudesMedicas";
    let arreglo = arregloDatosAptitudesMedicas;
    formarDatatable(id, ruta, arreglo);
}
/* RECOMENDACIONES */
function cargarDatatableRecomendaciones() {
    let id = "dt_recomendaciones";
    let ruta = "datatableRecomendaciones";
    let arreglo = arregloDatosRecomendaciones;
    formarDatatable(id, ruta, arreglo);
}
/* CONSTANTES VITALES */
function cargarDatatableConstantesVitales() {
    let id = "dt_constantes_vitales";
    let ruta = "datatableConstantesVitales";
    let arreglo = arregloDatosConstantesVitales;
    formarDatatable(id, ruta, arreglo);
}
/* Motivos reintegro */
function cargarDatatableMotivosReintegros() {
    app_motivo_reintegro.limpiarReintegro();
    let id = "dt_motivos_reintegros";
    let ruta = "datatableMotivosReintegros";
    let arreglo = arregloDatosMotivosReintegro;
    formarDatatable(id, ruta, arreglo);
}

function cargarDatatableAntecedentesTrabajo() {
    let id = "dtAntecedentesTrabajo";
    let ruta = "datatableAntecedentesTrabajo";
    let arreglo = arregloDatosAntecedentesTrabajo;
    formarDatatable(id, ruta, arreglo);
}

function cargarDatatableAntecedentesAccidentesTrabajo() {
    let id = "dtAntecedentesAccidentesTrabajo";
    let ruta = "datatableAntecedentesAccidentesTrabajo";
    let arreglo = arregloDatosAntecedentesAccidentesTrabajo;
    let dome = "rtip";
    formarDatatable(id, ruta, arreglo, dome);
}

function cargarDatatableAntecedentesEnfermedadesProfesionales() {
    let id = "dtAntecedentesEnfermedadesProfesionales";
    let ruta = "datatableAntecedentesEnfermedadesProfesionales";
    let arreglo = arregloDatosAntecedentesEnfermedadesProfesionales;
    let dome = "rtip";
    formarDatatable(id, ruta, arreglo, dome);
}

function cargarDatatableEvaluacionMedicaRetiro() {
    let id = "dtEvaluacionMedicaRetiro";
    let ruta = "datatableEvaluacionMedicaRetiro";
    let arreglo = arregloDatosEvaluacionMedicaRetiro;
    formarDatatable(id, ruta, arreglo);
}

$("#fileDiscapacidad").on("change", function () {
    if ($(this).val() != null && $(this).val() != null) {
        $("#fileDiscapacidad").each(function (a, array) {
            if (array.files.length > 0) {
                $.each(array.files, function (k, file) {
                    app_discapacidad.cargando = true;
                    getBase64(file).then((data) => {
                        app_discapacidad.file = data;
                        app_discapacidad.formCrear_externos.discapacidad.imagen =
                            app_discapacidad.file;
                        app_discapacidad.cargando = false;
                    });
                });
            }
        });
    }
});

$(".discapacidad").on("change", function () {
    $(".erroresInput").addClass("hidden");
    var valor = $(this).val();

    if (valor == "NINGUNA") {
        app_discapacidad.flagDiscapacidad = true;
        $("#carnetConadis").removeClass("discapacidad");
        $("#porcentajeDiscapacidad").removeClass("discapacidad");
        $("#fileDiscapacidad").removeClass("discapacidad");
        app_discapacidad.formCrear_externos.discapacidad.numero_carnet = "";
        app_discapacidad.formCrear_externos.discapacidad.porcentaje = "";
    } else {
        app_discapacidad.flagDiscapacidad = false;
        $("#carnetConadis").addClass("discapacidad");
        $("#porcentajeDiscapacidad").addClass("discapacidad");
        $("#fileDiscapacidad").addClass("discapacidad");
    }
    /* app_diagnostico.formDiagnostico.codigo_cie_id =
        $(this).val() == null ? "" : $(this).val();
    app_diagnostico.formDiagnostico.cie_descripcion = $(
        "#codigo_cie option:selected"
    ).text(); */
});

function getBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = (error) => reject(error);
    });
}

$("#fileExamen").on("change", function () {
    if ($(this).val() != null && $(this).val() != null) {
        $("#fileExamen").each(function (a, array) {
            if (array.files.length > 0) {
                $.each(array.files, function (k, file) {
                    app_examen_general_especifico.cargando = true;
                    getBase64(file).then((data) => {
                        app_examen_general_especifico.file = data;
                        app_examen_general_especifico.formCrear_examen_general_especifico.imagen =
                            app_examen_general_especifico.file;
                        app_examen_general_especifico.cargando = false;
                    });
                });
            }
        });
    }
});

$("#masa_corporal").on("click", function () {
    var peso = app_constantes.formConstante.peso;
    var estatura = app_constantes.formConstante.talla;

    var imc = peso / Math.pow(estatura, 2); // 49
    var imcF = round(imc);

    if (peso != "" && estatura != "") {
        app_constantes.formConstante.indice_masa_corporal = imcF;
    } else {
        app_constantes.formConstante.indice_masa_corporal = 0;
    }
});

$("#talla").keyup(function (event) {
    if (event.which === 13) {
        var peso = app_constantes.formConstante.peso;
        var estatura = app_constantes.formConstante.talla;

        var imc = peso / Math.pow(estatura, 2); // 49
        var imcF = round(imc);

        if (peso != "" && estatura != "") {
            app_constantes.formConstante.indice_masa_corporal = imcF;
        } else {
            app_constantes.formConstante.indice_masa_corporal = 0;
        }
    }
});

$("#peso").keyup(function (event) {
    if (event.which === 13) {
        var peso = app_constantes.formConstante.peso;
        var estatura = app_constantes.formConstante.talla;

        var imc = peso / Math.pow(estatura, 2); // 49
        var imcF = round(imc);

        if (peso != "" && estatura != "") {
            app_constantes.formConstante.indice_masa_corporal = imcF;
        } else {
            app_constantes.formConstante.indice_masa_corporal = 0;
        }
    }
});

$("#peso").change(function (event) {
    var peso = app_constantes.formConstante.peso;
    var estatura = app_constantes.formConstante.talla;

    var imc = peso / Math.pow(estatura, 2); // 49
    var imcF = round(imc);

    if (peso != "" && estatura != "") {
        app_constantes.formConstante.indice_masa_corporal = imcF;
    } else {
        app_constantes.formConstante.indice_masa_corporal = 0;
    }
});

$("#talla").change(function (event) {
    var peso = app_constantes.formConstante.peso;
    var estatura = app_constantes.formConstante.talla;

    var imc = peso / Math.pow(estatura, 2); // 49
    var imcF = round(imc);

    if (peso != "" && estatura != "") {
        app_constantes.formConstante.indice_masa_corporal = imcF;
    } else {
        app_constantes.formConstante.indice_masa_corporal = 0;
    }
});

function round(num) {
    var m = Number((Math.abs(num) * 100).toPrecision(15));
    return (Math.round(m) / 100) * Math.sign(num);
}
