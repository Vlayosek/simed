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
        data: "id",
        width: "7%",
        searchable: true,
    },

    {
        data: "descripcion",
        width: "7%",
        searchable: true,
    },

    {
        data: "seccion",
        width: "10%",
        searchable: true,
        render: function (data, type, row) {
            data =
                data != null && data != "" && data != "0"
                    ? data.toUpperCase().split(",")
                    : "--";
            if (data == "--") return data;

            html = "";
            let contador = 0;
            data.forEach(function (valor, indice, array) {
                if (valor != "" && valor != null) {
                    contador = contador + 1;
                    html += contador + ". " + valor + "<br/>";
                }
            });

            return html;
        },
    },
    {
        data: "campos",
        width: "10%",
        searchable: true,
        render: function (data, type, row) {
            data =
                data != null && data != "" && data != "0"
                    ? data.toUpperCase().split(",")
                    : "--";
            if (data == "--") return data;

            html = "";
            let contador = 0;
            data.forEach(function (valor, indice, array) {
                if (valor != "" && valor != null) {
                    contador = contador + 1;
                    html += contador + ". " + valor + "<br/>";
                }
            });

            return html;
        },
    },
    {
        data: "",
        width: "10%",
        searchable: true,
    },
];
