$(function () {
    cargarDatatable();
    cargarDatosSecciones();
});
function cargarDatosSecciones() {
    $(".tabs_seccion").each(function (a, value) {
        let valor = value.className.replace("active", "");
        valor = valor.replace("nav-item", "");
        valor = valor.replace("tabs_seccion", "");
        valor = valor.replace("hidden", "");
        let result = valor.replace(/^\s+|\s+$/gm, "");
        app.secciones.push(result);
    });

    $(".campo_evaluacion").each(function (a, value) {
        let valor = value.className.replace("col-md-4", "");
        valor = valor.replace("col-md-12", "");
        valor = valor.replace("hidden", "");
        valor = valor.replace("campo_evaluacion", "");
        let result = valor.replace(/^\s+|\s+$/gm, "");
        app.campos.push(result);
    });
}
function cargarDatatable() {
    $("#dt").dataTable({
        dom: "lfrtip",
        destroy: true,
        serverSide: true,
        stateSave: true,
        ajax: "/historias/consultaDatosBase/seccion/datatableSecciones/",
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
        columns: arregloDatos,
    });
}
