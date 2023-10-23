var app_revision_organos = new Vue({
    el: "#main_revision_organos",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,
            cargando: false,
            formCrear_externos: {
                revision_organos: {
                    id: 0,
                    descripcion: "",
                    detalle: null,
                    codigo: "",
                },
            },
            consultaDatosRevisionOrganos: [],
            editar: true,
        };
    },
    created: function () {
        // this.limpiarRevisionOrganos();
    },
    methods: {
        limpiarRevisionOrganos: function () {
            this.formCrear_externos.revision_organos.id = 0;
            this.formCrear_externos.revision_organos.descripcion = "";
            this.formCrear_externos.revision_organos.detalle = null;
            $("#detalle_revision_organos").val(null).change();
        },

        async editarRevisionOrganos(id) {
            this.cargando = true;
            app.cargando = true;
            //  var urlKeeps = document.querySelector("#inicializacion").value +"/historias/buscarFuncionario";

            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/editarRevisionOrganos";
            var fill = { id: id };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false;
                    this.cargando = false;
                    if (response.data.status == 200) {
                        var data = response.data.datos;
                        this.formCrear_externos.revision_organos.id = data.id;
                        this.formCrear_externos.revision_organos.descripcion =
                            data.descripcion;
                        this.formCrear_externos.revision_organos.detalle =
                            data.detalle.split(",");
                        this.consultaDatos = true;
                        $("#detalle_revision_organos")
                            .val(
                                this.formCrear_externos.revision_organos.detalle
                            )
                            .change();
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    app_revision_organos.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async consultaCombosRegistroRevisionOrganos() {
            app.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/consultaCombosRegistroRevisionOrganos";
            var fill = {};
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false;
                    if (response.data.status == 200)
                        this.consultaDatosRevisionOrganos = response.data.datos;
                })
                .catch((error) => {
                    app.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },

        async eliminarRevisionOrganos(id) {
            var fill = {
                id: id,
            };
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
                        var urlKeeps =
                            document.querySelector("#inicializacion").value +
                            "/historias/eliminarRevisionOrganos";
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                if (response.data.status != "200")
                                    alertToast(response.data.datos, 3500);
                                else {
                                    cargarDatatableRevisionOrganos();
                                    alertToastSuccess(
                                        response.data.datos,
                                        3500
                                    );
                                }
                            })
                            .catch((error) => {
                                app.cargando = false;
                                swal.fire(
                                    "Cancelado!",
                                    "Error al grabar...",
                                    error
                                );
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

        async guardarRevisionOrganos() {
            var error = buscarErroresInput("revision_organos");
            if (error) return false;

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
                        var urlKeeps =
                            document.querySelector("#inicializacion").value +
                            "/historias/guardarRevisionOrganos";
                        this.formCrear_externos.revision_organos.identificacion =
                            app.formCrear.identificacion;
                        this.formCrear_externos.revision_organos.detalle =
                            $("#detalle_revision_organos").val();

                        if (
                            this.formCrear_externos.revision_organos.codigo ==
                            ""
                        )
                            this.formCrear_externos.revision_organos.codigo =
                                app_datos.formCrear.codigo;
                        axios
                            .post(
                                urlKeeps,
                                this.formCrear_externos
                                    .revision_organos
                            )
                            .then((response) => {
                                if (response.data.status != "200")
                                    alertToast(response.data.datos, 3500);
                                else {
                                    alertToastSuccess(
                                        response.data.datos,
                                        3500
                                    );
                                    this.limpiarRevisionOrganos();
                                    cargarDatatableRevisionOrganos();


                                    if (this.consultaDatos == true)
                                        document
                                            .querySelector(
                                                "#cerrar_modal_revision_organos"
                                            )
                                            .click();
                                }
                            })
                            .catch((error) => {
                                app.cargando = false;
                                swal.fire(
                                    "Cancelado!",
                                    "Error al grabar...",
                                    error
                                );
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
