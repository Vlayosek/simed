var app = new Vue({
    el: "#main",
    data() {
        return {
            currentTab: 1,
            cargando: false,
            filtro_activo: false,
        };
    },
    created: function () {},
    methods: {
        cambiarEstadoFiltro: function () {
            if (this.filtro_activo) {
                $("#paciente_id").val(null).change();
                this.filtro_activo = false;
                cargarDatatable();
            } else this.filtro_activo = true;
        },
        filtrarDatos: function () {
            document.querySelector("#cerrar_filtrado").click();
            this.cambiarEstadoFiltro();
            cargarDatatable();
        },
        async consultaExportar(id) {
            app.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/consultas/exportar";
            var fill = {
                id: id,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false;
                    if (response.data.status == 200) {
                        var url_inicia =
                            document.querySelector("#inicializacion").value +
                            "/storage/" +
                            response.data.path;
                        let a = document.createElement("a");
                        a.download = response.data.nombre_archivo;
                        a.href = url_inicia;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        delete a;
                    } else {
                        alertToast("Error al descargar el reporte", 3500);
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", "error");
                });
        },
        async consultaCertificado(id) {
            app.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/consultas/exportarCertificado";
            var fill = {
                id: id,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false;
                    if (response.data.status == 200) {
                        var url_inicia =
                            document.querySelector("#inicializacion").value +
                            "/storage/" +
                            response.data.path;
                        let a = document.createElement("a");
                        a.download = response.data.nombre_archivo;
                        a.href = url_inicia;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        delete a;
                    } else {
                        alertToast("Error al descargar el reporte", 3500);
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", "error");
                });
        },

        async eliminarHistoria(id) {
            await swal
                .fire({
                    title: "Estás seguro de realizar esta acción",
                    text: "Al confirmar se grabaran los datos exitosamente",
                    icon: "warning",
                    showDenyButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Si!",
                    denyButtonColor: "#d33",
                    denyButtonText: "No",
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        var urlKeeps = "eliminarHistoria";
                        var fill = { id: id };
                        app.cargando = true;
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                if (response.data.status == 200) {
                                    alertToastSuccess(
                                        response.data.message,
                                        3500
                                    );

                                    cargarDatatable();
                                    app.cargando = false;
                                } else alertToast(response.data.message, 3500);
                            })
                            .catch((error) => {
                                app.cargando = false;
                                alertToast("Error, recargue la página", 3500);
                            });
                    } else if (result.isDenied) {
                        swal.fire(
                            "Cancelado!",
                            "No se registraron cambios...",
                            "error"
                        );
                        return false;
                    }
                });
        },
    },
});
