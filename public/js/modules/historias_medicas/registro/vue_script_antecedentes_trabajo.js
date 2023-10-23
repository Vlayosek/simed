var app_antecedentes_trabajo = new Vue({
    el: "#main_antecedentes_trabajo",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,
            consultaDatosAccidentes: false,
            consultaDatosEnfermedades: false,
            formCrear: {
                antecedentes_trabajo: {
                    id: 0,
                    empresa: "",
                    puesto_trabajo: "",
                    tiempo_trabajo: "",
                    actividades_desempenadas: "",
                    descripcion: "",
                    identificacion: "",
                    observaciones: "",
                    codigo: "",
                },
                accidentes_trabajo: {
                    id: 0,
                    id: 0,
                    identificacion: "",
                    calificado_accidente: false,
                    especificar_accidente: "",
                    fecha_accidente: "",
                    observaciones_accidente: "",
                    codigo: "",
                },
                enfermedades_profesionales: {
                    id: 0,
                    identificacion: "",
                    calificado_enfermedad: false,
                    especificar_enfermedad: "",
                    fecha_enfermedad: "",
                    observaciones_enfermedad: "",
                    codigo: "",
                },
            },
            cargando: false,
            consultaDatosAntecedentesTrabajo: [],
            consultaDatosAreas: [],
        };
    },
    created: function () {},
    methods: {
        limpiarAntecedentesTrabajo: function () {
            this.formCrear.antecedentes_trabajo.id = 0;
            this.formCrear.antecedentes_trabajo.descripcion = "";
            this.formCrear.antecedentes_trabajo.identificacion = "";
            this.formCrear.antecedentes_trabajo.empresa = "";
            this.formCrear.antecedentes_trabajo.puesto_trabajo = "";
            this.formCrear.antecedentes_trabajo.actividades_desempenadas = "";
            this.formCrear.antecedentes_trabajo.tiempo_trabajo = "";
            this.formCrear.antecedentes_trabajo.observaciones = "";
            this.formCrear.antecedentes_trabajo.codigo = "";
            $("input[name='antecedentes_trabajo']").prop("checked", false);
            $(".collapse").removeClass("show");
            $("#collapseOne").addClass("show");
            $(".erroresInput").addClass("hidden");
            this.consultaDatos = false;
            //this.getAreas();
        },

        limpiarAccidentesTrabajo: function () {
            this.formCrear.accidentes_trabajo.calificado_accidente = false;
            this.formCrear.accidentes_trabajo.especificar_accidente = "";
            this.formCrear.accidentes_trabajo.fecha_accidente = "";
            this.formCrear.accidentes_trabajo.observaciones_accidente = "";
            this.formCrear.accidentes_trabajo.codigo = "";
            this.formCrear.accidentes_trabajo.id = 0;
            this.formCrear.accidentes_trabajo.identificacion = "";
            $("input[name='calificado_accidente']").prop("checked", false);
            $(".erroresInput").addClass("hidden");
            $(".collapse").removeClass("show");
            $("#collapseTwo").addClass("show");
            this.consultaDatosAccidentes = false;
        },

        limpiarEnfermedadesProfesionales: function () {
            this.formCrear.enfermedades_profesionales.calificado_enfermedad = false;
            this.formCrear.enfermedades_profesionales.especificar_enfermedad =
                "";
            this.formCrear.enfermedades_profesionales.fecha_enfermedad = "";
            this.formCrear.enfermedades_profesionales.observaciones_enfermedad =
                "";
            this.formCrear.enfermedades_profesionales.codigo = "";
            this.formCrear.enfermedades_profesionales.id = 0;
            this.formCrear.enfermedades_profesionales.identificacion = "";
            $("input[name='calificado_enfermedad']").prop("checked", false);
            $(".erroresInput").addClass("hidden");
            $(".collapse").removeClass("show");
            $("#collapseThree").addClass("show");
            this.consultaDatosEnfermedades = false;
        },

        async editarAntecedentesTrabajo(id) {
            this.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/editarAntecedentesTrabajo";
            var fill = {
                id: id,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    console.log(response.data);
                    this.cargando = false;
                    if (response.data.status == "200") {
                        this.formCrear.antecedentes_trabajo =
                            response.data.datos;
                        this.consultaDatos = true;
                    } else {
                        toastr["error"]("Imposible cargar");
                    }
                })
                .catch((error) => {
                    this.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", "error");
                });
        },
        async getAreas() {
            app.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/consultaCombosAreas";
            var fill = {};
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false;
                    if (response.data.status == 200) {
                        this.consultaDatosAreas = response.data.areas;
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async eliminarAntecedentesTrabajo(id) {
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
                            "/historias/eliminarAntecedentesTrabajo";
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                console.log(response);
                                if (response.data.status != "200")
                                    alertToast(response.data.message, 3500);
                                else {
                                    cargarDatatableAntecedentesTrabajo();
                                    alertToastSuccess(
                                        response.data.message,
                                        3500
                                    );
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

        async consultaCombosAntecedentesTrabajo() {
            app.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/consultaCombosAntecedentesTrabajo";
            var fill = {};
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false;
                    if (response.data.status == 200) {
                        this.consultaDatosAntecedentesTrabajo =
                            response.data.antecedentes_trabajo;
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },

        async guardarAntecedentesTrabajo() {
            var list = $("input[name='antecedentes_trabajo']:checked")
                .map(function () {
                    return this.value;
                })
                .get()
                .join();

            var error = buscarErroresInput("antecedentes_trabajo");
            if (error) return false;

            this.formCrear.antecedentes_trabajo.descripcion = list;

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
                            "/historias/guardarAntecedentesTrabajo";
                        this.formCrear.antecedentes_trabajo.identificacion =
                            app.formCrear.identificacion;
                        if (this.formCrear.antecedentes_trabajo.codigo == "")
                            this.formCrear.antecedentes_trabajo.codigo =
                                app_datos.formCrear.codigo;

                        axios
                            .post(urlKeeps, this.formCrear.antecedentes_trabajo)
                            .then((response) => {
                                if (response.data.status != "200")
                                    alertToast(response.data.message, 3500);
                                else {
                                    alertToastSuccess(
                                        response.data.message,
                                        3500
                                    );
                                    if (this.consultaDatos === true)
                                        document
                                            .querySelector(
                                                "#cerrar_modal_antecedentes_trabajo"
                                            )
                                            .click();
                                    this.limpiarAntecedentesTrabajo();
                                    cargarDatatableAntecedentesTrabajo();
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

        async guardarAntecedenteAccidentesTrabajo() {
            var error = buscarErroresInput("accidentes_trabajo");
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
                            "/historias/guardarAntecedenteAccidentesTrabajo";
                        this.formCrear.accidentes_trabajo.identificacion =
                            app.formCrear.identificacion;
                        if (this.formCrear.accidentes_trabajo.codigo == "")
                            this.formCrear.accidentes_trabajo.codigo =
                                app_datos.formCrear.codigo;

                        axios
                            .post(urlKeeps, this.formCrear.accidentes_trabajo)
                            .then((response) => {
                                if (response.data.status != "200")
                                    alertToast(response.data.message, 3500);
                                else {
                                    alertToastSuccess(
                                        response.data.message,
                                        3500
                                    );

                                    if (this.consultaDatosAccidentes == true)
                                        document
                                            .querySelector(
                                                "#cerrar_modal_antecedentes_accidentes"
                                            )
                                            .click();

                                    this.limpiarAccidentesTrabajo();
                                    cargarDatatableAntecedentesAccidentesTrabajo();
                                }
                            })
                            .catch((error) => {
                                app.cargando = false;
                                swal.fire(
                                    "Cancelado!",
                                    "Error al grabar...",
                                    'error'
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

        async guardarAntecedentesEnfermedadesProfesionales() {
            var error = buscarErroresInput("enfermedades_profesionales");
            if (error) return false;

            const otherCheckbox = document.querySelector(
                "#calificado_enfermedad"
            );

            if (!otherCheckbox.checked) {
                this.formCrear.enfermedades_profesionales.especificar_enfermedad =
                    "";
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
                            "/historias/guardarAntecedentesEnfermedadesProfesionales";
                        this.formCrear.enfermedades_profesionales.identificacion =
                            app.formCrear.identificacion;
                        if (
                            this.formCrear.enfermedades_profesionales.codigo ==
                            ""
                        )
                            this.formCrear.enfermedades_profesionales.codigo =
                                app_datos.formCrear.codigo;

                        axios
                            .post(
                                urlKeeps,
                                this.formCrear.enfermedades_profesionales
                            )
                            .then((response) => {
                                if (response.data.status != "200")
                                    alertToast(response.data.message, 3500);
                                else {
                                    alertToastSuccess(
                                        response.data.message,
                                        3500
                                    );

                                    if (this.consultaDatosEnfermedades == true)
                                        document
                                            .querySelector(
                                                "#cerrar_modal_enfermedades_profesionales"
                                            )
                                            .click();

                                    this.limpiarEnfermedadesProfesionales();
                                    cargarDatatableAntecedentesEnfermedadesProfesionales();
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

        async editarAntecedentesAccidentesTrabajo(id) {
            app.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/editarAntecedenteAccidentesTrabajo";
            var fill = {
                id: id,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false;
                    if (response.data.status == 200) {
                        this.formCrear.accidentes_trabajo = response.data.datos;
                        this.consultaDatosAccidentes = true;
                    } else {
                        toastr["error"]("Imposible cargar");
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", "error");
                });
        },

        async editarAntecedentesEnfermedadesProfesionales(id) {
            app.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/editarAntecedentesEnfermedadesProfesionales";
            var fill = {
                id: id,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false;
                    if (response.data.status == 200) {
                        this.formCrear.enfermedades_profesionales =
                            response.data.datos;
                        this.consultaDatosEnfermedades = true;
                    } else {
                        toastr["error"]("Imposible cargar");
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", "error");
                });
        },

        async eliminarAntecedenteAccidentesTrabajo(id) {
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
                            "/historias/eliminarAntecedenteAccidentesTrabajo";
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                if (response.data.status != "200")
                                    alertToast(response.data.message, 3500);
                                else {
                                    cargarDatatableAntecedentesAccidentesTrabajo();
                                    alertToastSuccess(
                                        response.data.message,
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

        async eliminarAntecedentesEnfermedadesProfesionales(id) {
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
                            "/historias/eliminarAntecedentesEnfermedadesProfesionales";
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                if (response.data.status != "200")
                                    alertToast(response.data.message, 3500);
                                else {
                                    cargarDatatableAntecedentesEnfermedadesProfesionales();
                                    alertToastSuccess(
                                        response.data.message,
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
    },
});
