$(function () {
    cargarDatatable();
});
function cargarDatatable() {
    let fecha_inicio =
        $("#fecha_inicio").val() == "" || $("#fecha_inicio").val() == null
            ? "null"
            : $("#fecha_inicio").val();
    let fecha_fin =
        $("#fecha_fin").val() == "" || $("#fecha_fin").val() == null
            ? "null"
            : $("#fecha_fin").val();
    let paciente_id =
        $("#paciente_id").val() == "" || $("#paciente_id").val() == null
            ? "null"
            : $("#paciente_id").val();
    let error = validarFechasEntradas(fecha_inicio, fecha_fin, 365);
    if (!error) return false;
    $("#dt").dataTable({
        dom: "lBfrtip",
        destroy: true,
        serverSide: true,
        stateSave: true,
        ajax:
            "consultaDatosBase/datatableConsultaAtenciones/" +
            fecha_inicio +
            "/" +
            fecha_fin +
            "/" +
            paciente_id,
        responsive: true,
        processing: true,
        lengthMenu: [
            [4, 10, 20, -1],
            [4, 10, 20, "TODOS"],
        ],
        buttons: [
            {
                extend: "excelHtml5",
                text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel',
                titleAttr: "Excel",
            },
            {
                extend: "pdfHtml5",
                text: '<img src="/images/icons/pdf.png" width="25px" heigh="20px">Exportar pdf',
                titleAttr: "PDF",
                orientation: "landscape",
            },
        ],
        lengthChange: true,
        searching: true,
        language: languaje,
        order: [[0, "desc"]],
        columns: arregloDatos,
    });
}
