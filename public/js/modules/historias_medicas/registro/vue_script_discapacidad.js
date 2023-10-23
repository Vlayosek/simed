var app_discapacidad = new Vue({
    el: "#main_discapacidad",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,

            formCrear_externos: {
                discapacidad: {
                    id: "0",
                    numero_carnet: "",
                    porcentaje: "",
                    nombre: "",
                    codigo: "",
                    imagen: null,
                },
            },
            file: null,
            consultaDatosDiscapacidades: [],
            editar: true,
            formAnexoDiscapacidad: true,
            formDescargaAnexoDiscapacidad: false,
            flagDiscapacidad: false,
        };
    },
    created: function () {},
    methods: {
        limpiarDiscapacidad: function () {
            this.formCrear_externos.discapacidad.nombre = "";
            this.formCrear_externos.discapacidad.id = "0";
            this.formCrear_externos.discapacidad.porcentaje = "";
            this.formCrear_externos.discapacidad.numero_carnet = "";
            this.formCrear_externos.discapacidad.imagen = null;
            this.file = null;
            this.formAnexoDiscapacidad = true;
            this.formDescargaAnexoDiscapacidad = false;
            $(".erroresInput").addClass("hidden");
            this.flagDiscapacidad = false;
            this.limpiarSubidaArchivo();
        },
        limpiarSubidaArchivo: function () {
            $("#fileDiscapacidad").val(null);
        },
        async editarPersonaDiscapacidad(id) {
            $(".erroresInput").addClass("hidden");
            app.cargando = true;
            this.editar = false;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/editarPersonaDiscapacidad";
            var fill = {
                id: id,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    console.log(response);
                    app.cargando = false;
                    if (response.data.status == 200) {
                        this.editar = true;
                        this.formCrear_externos.discapacidad.nombre =
                            response.data.datos.nombre;
                        this.formCrear_externos.discapacidad.id = id;
                        this.formCrear_externos.discapacidad.porcentaje =
                            response.data.datos.porcentaje;
                        this.formCrear_externos.discapacidad.numero_carnet =
                            response.data.datos.numero_carnet;
                        this.formCrear_externos.discapacidad.imagen =
                            response.data.datos.imagen;

                        if (
                            this.formCrear_externos.discapacidad.nombre ==
                            "NINGUNA"
                        ) {
                            this.formCrear_externos.discapacidad.porcentaje =
                                "";
                            this.formCrear_externos.discapacidad.numero_carnet =
                                "";
                            this.flagDiscapacidad = true;
                        } else this.flagDiscapacidad = false;

                        if (
                            this.formCrear_externos.discapacidad.imagen != null
                        ) {
                            this.formDescargaAnexoDiscapacidad = true;
                            this.formAnexoDiscapacidad = false;
                        } else {
                            this.formDescargaAnexoDiscapacidad = false;
                            this.formAnexoDiscapacidad = true;
                        }
                    } else {
                        alertToast("Imposible cargar", 3500);
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    this.editar = false;
                    swal.fire("Cancelado!", "Error al grabar...", "error");
                });
        },
        async eliminarPersonaDiscapacidad(id) {
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
                            "/historias/eliminarPersonaDiscapacidad";
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                if (response.data.status != "200")
                                    alertToast(response.data.datos, 3500);
                                else {
                                    cargarDatatableDiscapacidad();
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

        async guardarDiscapacidad() {
            if (this.formDescargaAnexoDiscapacidad == true) {
                $("#fileDiscapacidad").removeClass("discapacidad");
            }

            if (this.formCrear_externos.discapacidad.nombre != "NINGUNA") {
                var error = buscarErroresInput("discapacidad");
                if (error) return false;
            }

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
                            "/historias/guardarDiscapacidad";
                        app_discapacidad.formCrear_externos.discapacidad.identificacion =
                            app.formCrear.identificacion;
                        if (this.formCrear_externos.discapacidad.codigo == "")
                            this.formCrear_externos.discapacidad.codigo =
                                app_datos.formCrear.codigo;

                        this.formCrear_externos.discapacidad.flagDiscapacidad =
                            this.flagDiscapacidad;
                        axios
                            .post(
                                urlKeeps,
                                app_discapacidad.formCrear_externos.discapacidad
                            )
                            .then((response) => {
                                if (response.data.status != "200")
                                    alertToast(response.data.datos, 3500);
                                else {
                                    alertToastSuccess(
                                        response.data.datos,
                                        3500
                                    );
                                    this.formAnexoDiscapacidad = true;
                                    this.formDescargaAnexoDiscapacidad = false;
                                    app_discapacidad.limpiarDiscapacidad();
                                    cargarDatatableDiscapacidad();
                                    document
                                        .querySelector(
                                            "#cerrar_modal_discapacidad"
                                        )
                                        .click();
                                }
                            })
                            .catch((error) => {
                                app.cargando = false;
                                swal.fire(
                                    "Cancelado!",
                                    "Error al grabar...",
                                    "error"
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

        async consultaCombosRegistro() {
            app.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/consultaCombosRegistro";
            var fill = {};
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false;
                    if (response.data.status == 200) {
                        this.consultaDatosDiscapacidades =
                            response.data.discapacidades;
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },

        async descargarPdf64Discapacidad() {
            this.descargarPdf64(
                this.formCrear_externos.discapacidad.id,
                "DISCAPACIDAD"
            );
        },

        async descargarPdf64(id, tabla) {
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/descargarPdf64";
            var fill = {
                id: id,
                tabla: tabla,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    if (response.data.status != "200")
                        alertToast(response.data.datos, 3500);
                    else {
                        downloadURI(response.data.datos, response.data.name);
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
    },
});
