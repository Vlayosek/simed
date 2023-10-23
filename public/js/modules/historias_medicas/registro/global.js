function returnData(data) {
    if (typeof data === "number") return data;
    return data != null && data != "" && data != "0"
        ? data.toUpperCase()
        : "--";
}
function formarDatatable(id, ruta, arreglo, dome = "lfrtip") {
    $("#" + id + "").dataTable({
        dom: dome,
        destroy: true,
        serverSide: true,
        ajax:
            document.querySelector("#inicializacion").value +
            "/historias/consultaDatosBase/" +
            ruta +
            "/" +
            app.formCrear.identificacion +
            "/" +
            app_datos.formCrear.id,
        //   stateSave:true,
        responsive: true,
        processing: true,
        lengthMenu: [
            [4, 10, 20],
            [4, 10, 20],
        ],
        lengthChange: true,
        searching: true,
        language: languaje,
        order: [[0, "desc"]],
        columns: arreglo,
    });
}
const languaje = {
    search: "Buscar",
    lengthMenu: "Mostrar _MENU_",
    zeroRecords: "Lo sentimos, no encontramos lo que estas buscando",
    info: "Motrar página _PAGE_ de _PAGES_ (_TOTAL_)",
    infoEmpty: "Registros no encontrados",
    oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "Siguiente",
        sPrevious: "Anterior",
    },
    infoFiltered: "(Filtrado _TOTAL_  de _MAX_ registros totales)",
};
var dataDescripcion = [
    {
        data: "descripcion",
        width: "80%",
        searchable: true,
        render: function (data, type, row) {
            return returnData(data);
        },
    },

    {
        data: "",
        width: "20%",
        searchable: true,
    },
];
var dataDescripcionDetalle = [
    {
        data: "descripcion",
        width: "30%",
        searchable: true,
        render: function (data, type, row) {
            return returnData(data);
        },
    },
    {
        data: "detalle",
        width: "40%",
        searchable: true,
        render: function (data, type, row) {
            return returnData(data);
        },
    },
    {
        data: "",
        width: "30%",
        searchable: true,
    },
];
var arregloDatosPersonalesQuirurgicos = dataDescripcion;
var arregloDatosAntecedentesFamiliares = dataDescripcionDetalle;
var arregloDatosRevisionOrganos = dataDescripcionDetalle;
var arregloDatosActividadesExtras = dataDescripcion;
var arregloDatosEnfermedadesActuales = dataDescripcion;
var arregloDatosMotivosReintegro = dataDescripcion;

var arregloDatos = [
    {
        data: "id",
        width: "10%",
        searchable: true,
    },
    {
        data: "descripcion",
        width: "50%",
        searchable: true,
        render: function (data, type, row) {
            return returnData(data);
        },
    },
    {
        data: "delegado",
        width: "20%",
        searchable: true,
        render: function (data, type, row) {
            return returnData(data);
        },
    },
    {
        data: "responsable_",
        width: "20%",
        searchable: true,
        render: function (data, type, row) {
            return returnData(data);
        },
    },

    {
        data: "",
        width: "10%",
        searchable: true,
    },
];
var arregloDatosDiscapacidad = [
    {
        data: "nombre",
        width: "30%",
        searchable: true,
        render: function (data, type, row) {
            return returnData(data);
        },
    },
    {
        data: "porcentaje",
        width: "20%",
        searchable: true,
        render: function (data, type, row) {
            return returnData(data);
        },
    },
    {
        data: "numero_carnet",
        width: "30%",
        searchable: true,
        render: function (data, type, row) {
            return returnData(data);
        },
    },
    {
        data: "",
        width: "25%",
        searchable: true,
    },
];

var arregloDatosHabitos = [
    {
        data: "descripcion",
        width: "20%",
        searchable: true,

        render: function (data, type, row) {
            return returnData(data);
        },
    },
    {
        data: "valor",
        width: "10%",
        searchable: true,
        render: function (data, type, row) {
            return row.valor == false
                ? '<span class="badge bg-danger">NO</span>'
                : '<span class="badge bg-primary">SI</span>';
        },
    },
    {
        data: "tiempo_consumo",
        width: "15%",
        searchable: true,
        render: function (data, type, row) {
            var a =
                row.tiempo_consumo == 1
                    ? row.tiempo_consumo + " (mes)"
                    : row.tiempo_consumo + " (meses)";
            return a;
        },
    },
    {
        data: "cantidad",
        width: "10%",
        searchable: true,
        /*render: function (data, type, row) {
            var a =
                row.cantidad == 1
                    ? row.cantidad + " (vez)"
                    : row.cantidad + " (veces)";
            return a;
        },*/
    },
    {
        data: "ex_consumidor",
        width: "15%",
        searchable: true,
        render: function (data, type, row) {
            return row.ex_consumidor == false
                ? '<span class="badge bg-danger">NO</span>'
                : '<span class="badge bg-primary">SI</span>';
        },
    },
    {
        data: "tiempo_abstinencia",
        width: "15%",
        searchable: true,
        render: function (data, type, row) {
            var a =
                row.tiempo_abstinencia == 1
                    ? row.tiempo_abstinencia + " (mes)"
                    : row.tiempo_abstinencia + " (meses)";
            return a;
        },
    },
    {
        data: "",
        width: "15%",
        searchable: true,
    },
];

var arregloDatosEstiloVida = [
    {
        data: "descripcion",
        width: "20%",
        searchable: true,
    },
    {
        data: "valor",
        width: "5%",
        searchable: true,
        render: function (data, type, row) {
            return row.valor == false
                ? '<span class="badge bg-danger">NO</span>'
                : '<span class="badge bg-primary">SI</span>';
        },
    },
    {
        data: "tipo_estilo_vida",
        width: "40%",
        searchable: true,
    },
    {
        data: "tiempo_cantidad",
        width: "10%",
        searchable: true,
        /*render: function (data, type, row) {
            var a =
                row.descripcion == "ACTIVIDAD FÍSICA"
                    ? row.tiempo_cantidad == 1
                        ? row.tiempo_cantidad + " (dia)"
                        : row.tiempo_cantidad + " (dias)"
                    : row.tiempo_cantidad == 1
                    ? row.tiempo_cantidad + " (unidad)"
                    : row.tiempo_cantidad + " (unidades)";
            return a;
        },*/
    },
    {
        data: "",
        width: "20%",
        searchable: true,
    },
];

var arregloDatosExamenGeneralEspecifico = [
    {
        data: "descripcion",
        width: "20%",
        searchable: true,
    },
    {
        data: "fecha",
        width: "15%",
        searchable: true,
    },
    {
        data: "resultados",
        width: "50%",
        searchable: true,
    },
    {
        data: "",
        width: "15%",
        searchable: true,
    },
];

var arregloDatosExamenFisicoRegional = [
    {
        data: "descripcion",
        width: "70%",
        searchable: true,
    },
    /* {
        data: "detalle",
        width: "5%",
        searchable: true,
    }, */
    {
        data: "",
        width: "30%",
        searchable: true,
    },
];

var arregloDatosAntecedentesFamiliares = [
    {
        data: "descripcion",
        width: "30%",
        searchable: true,
        render: function (data, type, row) {
            return data != null && data != "" && data != "0"
                ? data.toUpperCase()
                : "--";
        },
    },
    {
        data: "detalle",
        width: "40%",
        searchable: true,
        render: function (data, type, row) {
            return data != null && data != "" && data != "0"
                ? data.toUpperCase()
                : "--";
        },
    },
    {
        data: "",
        width: "30%",
        searchable: true,
    },
];
var arregloDatosFactoresRiesgosos = [
    {
        data: "puesto_trabajo",
        width: "50%",
        searchable: true,
        render: function (data, type, row) {
            return returnData(data);
        },
    },
    {
        data: "actividades",
        width: "15%",
        searchable: true,
        render: function (data, type, row) {
            return returnData(data);
        },
    },
    {
        data: "descripcion",
        width: "15%",
        searchable: true,
        render: function (data, type, row) {
            return returnData(data);
        },
    },

    {
        data: "",
        width: "20%",
        searchable: true,
    },
];

/* ANTECEDENTES GINECO OBSTETRICO */
var arregloDatosAntecedentesGineco = [
    {
        data: "menarquia",
        width: "3%",
        searchable: true,
    },
    {
        data: "ciclos",
        width: "1%",
        searchable: true,
    },
    {
        data: "menstruacion",
        width: "3%",
        searchable: true,
    },
    {
        data: "gestas",
        width: "1%",
        searchable: true,
    },
    {
        data: "partos",
        width: "1%",
        searchable: true,
    },
    {
        data: "cesareas",
        width: "1%",
        searchable: true,
    },
    {
        data: "abortos",
        width: "1%",
        searchable: true,
    },
    {
        data: "hijos",
        width: "4%",
        searchable: true,
        render: function (data, type, row) {
            var lista = row.hijos.split("|");
            var html_ = "";
            $.each(lista, function (_key, _value) {
                html_ += _value + "<br>";
            });
            return html_;
        },
    },
    {
        data: "vida_sexual_",
        width: "2%",
        searchable: true,
    },
    {
        data: "planificacion_familiar_",
        width: "5%",
        searchable: true,
        render: function (data, type, row) {
            var lista = row.planificacion_familiar_.split("|");
            var html_ = "";
            $.each(lista, function (_key, _value) {
                html_ += _value + "<br>";
            });
            return html_;
        },
    },

    {
        data: "",
        width: "2%",
        searchable: true,
    },
];
/* EXAMENES GINECO OBSTETRICO */
var arregloDatosExamenesGineco = [
    {
        data: "tipo_examen",
        width: "2%",
        searchable: true,
    },
    {
        data: "realizo_examen_",
        width: "1%",
        searchable: true,
    },
    {
        data: "tiempo",
        width: "1%",
        searchable: true,
    },
    {
        data: "resultado",
        width: "5%",
        searchable: true,
    },
    {
        data: "",
        width: "1%",
        searchable: true,
    },
];
/* ANTECEDENTES REPRODUCTIVOS MASCULINOS */
var arregloDatosAntecedentesReproductivosMasculinos = [
    {
        data: "planificacion_familiar_",
        width: "5%",
        searchable: true,
        render: function (data, type, row) {
            var lista = row.planificacion_familiar_.split("|");
            var html_ = "";
            $.each(lista, function (_key, _value) {
                html_ += _value + "<br>";
            });
            return html_;
        },
    },
    {
        data: "hijos",
        width: "4%",
        searchable: true,
        render: function (data, type, row) {
            var lista = row.hijos.split("|");
            var html_ = "";
            $.each(lista, function (_key, _value) {
                html_ += _value + "<br>";
            });
            return html_;
        },
    },
    {
        data: "",
        width: "2%",
        searchable: true,
    },
];
/* EXAMENES REPRODUCTIVOS MASCULINOS */
var arregloDatosExamenesReproductivosMasculinos = [
    {
        data: "tipo_examen",
        width: "2%",
        searchable: true,
    },
    {
        data: "realizo_examen_",
        width: "1%",
        searchable: true,
    },
    {
        data: "tiempo",
        width: "1%",
        searchable: true,
    },
    {
        data: "resultado",
        width: "5%",
        searchable: true,
    },
    {
        data: "",
        width: "1%",
        searchable: true,
    },
];
/* DIAGNOSTICO */
var arregloDatosDiagnosticos = [
    {
        data: "descripcion",
        width: "8%",
        searchable: true,
    },
    {
        data: "cie",
        width: "1%",
        searchable: true,
    },
    {
        data: "tipo",
        width: "1%",
        searchable: true,
    },
    {
        data: "",
        width: "1%",
        searchable: true,
    },
];

/* APTITUDES MEDICAS */
var arregloDatosAptitudesMedicas = [
    {
        data: "aptitud",
        width: "1%",
        searchable: true,
    },
    {
        data: "observacion",
        width: "8%",
        searchable: true,
    },
    {
        data: "limitacion",
        width: "8%",
        searchable: true,
    },
    {
        data: "",
        width: "1%",
        searchable: true,
    },
];
/* RECOMENDACIONES */
var arregloDatosRecomendaciones = [
    {
        data: "recomendacion",
        width: "10%",
        searchable: true,
    },
    {
        data: "",
        width: "1%",
        searchable: true,
    },
];

/* CONSTANTES VITALES */
var arregloDatosConstantesVitales = [
    {
        data: "presion_arterial",
        width: "1%",
        searchable: true,
    },
    {
        data: "temperatura",
        width: "1%",
        searchable: true,
    },
    {
        data: "frecuencia_cardiaca",
        width: "1%",
        searchable: true,
    },
    {
        data: "saturacion_oxigeno",
        width: "1%",
        searchable: true,
    },
    {
        data: "frecuencia_respiratoria",
        width: "1%",
        searchable: true,
    },
    {
        data: "peso",
        width: "1%",
        searchable: true,
    },
    {
        data: "talla",
        width: "1%",
        searchable: true,
    },
    {
        data: "indice_masa_corporal",
        width: "1%",
        searchable: true,
    },
    {
        data: "perimetro_abdominal",
        width: "1%",
        searchable: true,
    },
    {
        data: "",
        width: "1%",
        searchable: true,
    },
];

var arregloDatosAntecedentesTrabajo = [
    {
        data: "empresa",
        width: "15%",
        searchable: true,
    },
    {
        data: "puesto_trabajo",
        width: "15%",
        searchable: true,
    },
    {
        data: "actividades_desempenadas",
        width: "15%",
        searchable: true,
    },
    {
        data: "tiempo_trabajo",
        width: "15%",
        searchable: true,
        render: function (data, type, row) {
            var a =
                row.tiempo_trabajo == 1
                    ? row.tiempo_trabajo + " (mes)"
                    : row.tiempo_trabajo + " (meses)";
            return a;
        },
    },
    /* {
        data: "riesgos",
        width: "30%",
        searchable: true,
    }, */
    {
        data: "observaciones",
        width: "20%",
        searchable: true,
    },
    {
        data: "",
        width: "25%",
        searchable: true,
    },
];

var arregloDatosAntecedentesAccidentesTrabajo = [
    {
        data: "calificado_accidente",
        width: "15%",
        searchable: true,
        render: function (data, type, row) {
            var a =
                row.calificado_accidente == true
                    ? "<span class='badge bg-info'>SI</span>"
                    : "<span class='badge bg-danger'>NO</span>";
            return a;
        },
    },
    {
        data: "especificar_accidente",
        width: "15%",
        searchable: true,
        render: function (data, type, row) {
            var a =
                row.especificar_accidente == "" ||
                row.especificar_accidente == null
                    ? "--"
                    : row.especificar_accidente;
            return a;
        },
    },
    {
        data: "fecha_accidente",
        width: "15%",
        searchable: true,
    },
    {
        data: "observaciones_accidente",
        width: "15%",
        searchable: true,
    },
    {
        data: "",
        width: "25%",
        searchable: true,
    },
];

var arregloDatosAntecedentesEnfermedadesProfesionales = [
    {
        data: "calificado_enfermedad",
        width: "15%",
        searchable: true,
        render: function (data, type, row) {
            var a =
                row.calificado_enfermedad == true
                    ? "<span class='badge bg-info'>SI</span>"
                    : "<span class='badge bg-danger'>NO</span>";
            return a;
        },
    },
    {
        data: "especificar_enfermedad",
        width: "15%",
        searchable: true,
        render: function (data, type, row) {
            var a =
                row.especificar_enfermedad == "" ||
                row.especificar_enfermedad == null
                    ? "--"
                    : row.especificar_enfermedad;
            return a;
        },
    },
    {
        data: "fecha_enfermedad",
        width: "15%",
        searchable: true,
    },
    {
        data: "observaciones_enfermedad",
        width: "15%",
        searchable: true,
    },
    {
        data: "",
        width: "25%",
        searchable: true,
    },
];

var arregloDatosEvaluacionMedicaRetiro = [
    {
        data: "evaluacion_realizada",
        width: "15%",
        searchable: true,
        render: function (data, type, row) {
            var a =
                row.evaluacion_realizada == true
                    ? "<span class='badge bg-info'>SI</span>"
                    : "<span class='badge bg-danger'>NO</span>";
            return a;
        },
    },
    {
        data: "observaciones",
        width: "50%",
        searchable: true,
        render: function (data, type, row) {
            var a =
                row.observaciones == "" || row.observaciones == null
                    ? "--"
                    : row.observaciones;
            return a;
        },
    },
    {
        data: "condicion_diagnostico",
        width: "50%",
        searchable: true,
        render: function (data, type, row) {
            if (row.condicion_diagnostico == "presuntiva") var a = "PRESUNTIVA";
            if (row.condicion_diagnostico == "definitiva") var a = "DEFINITIVA";
            if (row.condicion_diagnostico == "no_aplica") var a = "NO APLICA";
            return a;
        },
    },
    {
        data: "salud_relacionada",
        width: "50%",
        searchable: true,
        render: function (data, type, row) {
            if (row.salud_relacionada == "true") var a = "SI";
            if (row.salud_relacionada == "false") var a = "NO";
            if (row.salud_relacionada == "no_aplica") var a = "NO APLICA";
            return a;
        },
    },
    {
        data: "",
        width: "25%",
        searchable: true,
    },
];
