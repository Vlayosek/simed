var app_examen_general_especifico = new Vue({
    el: "#main_examen_general_especifico",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,
            formCrear_examen_general_especifico: {
                id: "0",
                descripcion: "",
                identificacion: "",
                fecha: "",
                resultados: "",
                codigo: "",
                tipo_evaluacion: "",
                imagen: null,
            },
            consultaDatosExamenGeneralEspecifico: [],
            editar: true,
            file: null,
            formAnexoExamen: true,
            formDescargaAnexoExamen: false,
        };
    },
    created: function () {
        // this.limpiarExamenGeneralEspecifico();
        //this.consultaComboExamenGeneralEspecifico();
    },
    methods: {
        limpiarExamenGeneralEspecifico: function () {
            this.formCrear_examen_general_especifico.id = "0";
            this.formCrear_examen_general_especifico.descripcion = "";
            this.formCrear_examen_general_especifico.identificacion = "";
            this.formCrear_examen_general_especifico.fecha = "";
            this.formCrear_examen_general_especifico.resultados = "";
            this.file = null;
            this.formAnexoExamen = true;
            this.formDescargaAnexoExamen = false;
            this.consultaDatos = false;
            $(".erroresInput").addClass("hidden");
            this.limpiarSubidaArchivo();
            // this.consultaComboExamenGeneralEspecifico();
        },
        limpiarSubidaArchivo: function () {
            $("#fileExamen").val(null);
        },
        async editarExamenGeneralEspecifico(id) {
            this.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/editarExamenGeneralEspecifico";
            var fill = {
                id: id,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    console.log(response);
                    this.cargando = false;
                    if (response.data.status == 200) {
                        this.formCrear_examen_general_especifico =
                            response.data.datos;
                        this.consultaDatos = true;
                        if (
                            this.formCrear_examen_general_especifico.imagen !=
                            null
                        ) {
                            this.formDescargaAnexoExamen = true;
                            this.formAnexoExamen = false;
                        } else {
                            this.formDescargaAnexoExamen = false;
                            this.formAnexoExamen = true;
                        }
                    } else {
                        toastr["error"]("Imposible cargar");
                    }
                })
                .catch((error) => {
                    this.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async eliminarExamenGeneralEspecifico(id) {
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
                            "/historias/eliminarExamenGeneralEspecifico";
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                if (response.data.status != "200")
                                    toastr["error"](response.data.message);
                                else {
                                    cargarDatatableExamenGeneralEspecifico();
                                    toastr["success"](response.data.message);
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

        async guardarExamenGeneralEspecifico() {
            var error = buscarErroresInput("examen_general_especifico");
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
                            "/historias/guardarExamenGeneralEspecifico";
                        this.formCrear_examen_general_especifico.identificacion =
                            app.formCrear.identificacion;
                        if (
                            this.formCrear_examen_general_especifico.codigo ==
                            ""
                        )
                            this.formCrear_examen_general_especifico.codigo =
                                app_datos.formCrear.codigo;
                        this.formCrear_examen_general_especifico.tipo_evaluacion =
                            app_datos.formCrear.tipo_evaluacion;

                        axios
                            .post(
                                urlKeeps,
                                this.formCrear_examen_general_especifico
                            )
                            .then((response) => {
                                if (response.data.status != "200")
                                    toastr["error"](response.data.message);
                                else {
                                    toastr["success"](response.data.message);
                                    this.formAnexoExamen = true;
                                    this.formDescargaAnexoExamen = false;
                                    this.limpiarExamenGeneralEspecifico();
                                    cargarDatatableExamenGeneralEspecifico();
                                    this.consultaDatos = false;
                                    document
                                        .querySelector(
                                            "#cerrar_modal_examen_general_especifico"
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

        async consultaComboExamenGeneralEspecifico() {
            app.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/consultaComboExamenGeneralEspecifico";
            //    var urlKeeps = document.querySelector("#inicializacion").value +"/historias/consultaComboExamenGeneralEspecifico";
            var fill = {};
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false;
                    if (response.data.status == 200) {
                        this.consultaDatosExamenGeneralEspecifico =
                            response.data.examen_general_especifico;
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },

        async descargarPdf64Examen() {
            this.descargarPdf64(
                this.formCrear_examen_general_especifico.id,
                "ENFERMEDAD"
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
