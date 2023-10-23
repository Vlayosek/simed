var app_examen_fisico_regional = new Vue({
    el: "#main_examen_fisico_regional",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,
            formCrear_examen_fisico_regional: {
                id: 0,
                descripcion: "",
                identificacion: "",
                detalles: "",
                codigo: "",
            },
            cargando: false,
            consultaDatosExamenFisicoRegional: [],
            editar: true,
        };
    },
    created: function () {
        // this.limpiarExamenFisicoRegional();
        //this.consultaComboExamenFisicoRegional();
    },
    methods: {
        limpiarExamenFisicoRegional: function () {
            this.formCrear_examen_fisico_regional.id = 0;
            this.formCrear_examen_fisico_regional.descripcion = "";
            this.formCrear_examen_fisico_regional.detalles = "";
            this.formCrear_examen_fisico_regional.identificacion = "";
            $("input[name='examenes_fisicos']").prop("checked", false);
            $(".erroresInput").addClass("hidden");
            this.consultaDatos = false;
        },
        async editarExamenFisicoRegional(id) {
            this.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/editarExamenFisicoRegional";
            var fill = {
                id: id,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargando = false;
                    if (response.data.status == 200) {
                        this.formCrear_examen_fisico_regional =
                            response.data.datos;
                        this.consultaDatos = true;
                    } else {
                        toastr["error"]("Imposible cargar");
                    }
                })
                .catch((error) => {
                    this.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async eliminarExamenFisicoRegional(id) {
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
                            "/historias/eliminarExamenFisicoRegional";
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                if (response.data.status != "200")
                                    toastr["error"](response.data.message);
                                else {
                                    cargarDatatableExamenFisicoRegional();
                                    toastr["success"](response.data.message);
                                }
                            })
                            .catch((error) => {
                                this.cargando = false;
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

        async guardarExamenFisicoRegional() {
            var list = $("input[name='examenes_fisicos']:checked")
                .map(function () {
                    return this.value;
                })
                .get()
                .join();
            if (list == "" || list == null) {
                toastr["error"](
                    "Debe grabar por lo menos un elemento de la lista"
                );
                return false;
            }
            var error = buscarErroresInput("examen_fisico_regional");
            if (error) return false;

            this.formCrear_examen_fisico_regional.detalles = list;

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
                            "/historias/guardarExamenFisicoRegional";
                        this.formCrear_examen_fisico_regional.identificacion =
                            app.formCrear.identificacion;

                        if (this.formCrear_examen_fisico_regional.codigo == "")
                            this.formCrear_examen_fisico_regional.codigo =
                                app_datos.formCrear.codigo;

                        axios
                            .post(
                                urlKeeps,
                                this.formCrear_examen_fisico_regional
                            )
                            .then((response) => {
                                if (response.data.status != "200")
                                    toastr["error"](response.data.message);
                                else {
                                    toastr["success"](response.data.message);
                                    this.limpiarExamenFisicoRegional();
                                    cargarDatatableExamenFisicoRegional();
                                    document
                                        .querySelector(
                                            "#cerrar_modal_examen_fisico_regional"
                                        )
                                        .click();
                                }
                            })
                            .catch((error) => {
                                this.cargando = false;
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

        async consultaComboExamenFisicoRegional() {
            this.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/consultaComboExamenFisicoRegional";

            //  var urlKeeps = document.querySelector("#inicializacion").value +"/historias/consultaComboExamenFisicoRegional";
            var fill = {};
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargando = false;
                    if (response.data.status == 200)
                        this.consultaDatosExamenFisicoRegional =
                            response.data.datos;
                })
                .catch((error) => {
                    this.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
    },
});
