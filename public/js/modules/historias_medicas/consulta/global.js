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
var arregloDatos = [
   
    {
        data: 'codigo',
        "width": "7%",
        "searchable": true,
    },

    {
        data: 'tipo_evaluacion',
        "width": "7%",
        "searchable": true,
    },

    {
        data: 'area',
        "width": "10%",
        "searchable": true,
        "render": function (data, type, row) {
            return data != null && data != '' && data != '0' ? data.toUpperCase() : '--';
        }
    },
    {
        data: 'identificacion',
        "width": "10%",
        "searchable": true,
        "render": function (data, type, row) {
            return data != null && data != '' && data != '0' ? data.toUpperCase() : '--';
        }
    },
    {
        data: 'funcionario',
        "width": "20%",
        "searchable": true,
        "render": function (data, type, row) {
            return data != null && data != '' && data != '0' ? data.toUpperCase() : '--';
        }
    },
    {
        data: 'motivo_consulta',
        "width": "30%",
        "searchable": true,
        "render": function (data, type, row) {
            return data != null && data != '' && data != '0' ? data.toUpperCase() : '--';
        }
    },
    {
        data: 'diagnosticos_',
        "width": "30%",
        "searchable": true,
        "render": function (data, type, row) {
            return data != null && data != '' && data != '0' ? (data.toUpperCase().replace('|,','<br/><br/>')) : '--';
        }
    },
    {
        data: '',
        "width": "10%",
        "searchable": true,

    },
];
