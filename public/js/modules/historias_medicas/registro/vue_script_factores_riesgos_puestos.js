var app_factores_riesgosos = new Vue({
    el: "#main_factores_riesgos_puesto",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,
            cargando: false,
            formCrear_externos: {
                factores_riesgos_puesto: {
                    id: 0,
                    descripcion: "",
                    medidas_preventivas: "",
                    puesto_trabajo: "",
                    actividades: "",
                    detalles: "",
                    codigo: "",
                    identificacion: "",
                },
            },
            consultaDatosFactoresRiegososPuestos: [],
            editar: true,
        };
    },
    created: function () {
        // this.limpiarAntecedenteFamiliar();
    },
    methods: {
        limpiarFactoresRiegosos: function () {
            this.formCrear_externos.factores_riesgos_puesto.id = 0;
            this.formCrear_externos.factores_riesgos_puesto.descripcion = "";
            this.formCrear_externos.factores_riesgos_puesto.medidas_preventivas =
                "";
            this.formCrear_externos.factores_riesgos_puesto.puesto_trabajo = app_paciente.formCrear.paciente.cargo;
            this.formCrear_externos.factores_riesgos_puesto.actividades = "";
            this.formCrear_externos.factores_riesgos_puesto.detalles = "";
            $("input[name='factores_laborales']").prop("checked", false);
        },

        async editarFactorRiesgoLaboral(id) {
            app.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/editarFactorRiesgoLaboral";
            var fill = { id: id };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false;
                    if (response.data.status == 200)
                        this.formCrear_externos.factores_riesgos_puesto =
                            response.data.datos;
                })
                .catch((error) => {
                    app.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async consultaCombosRegistroFactoresRiegososPuestos() {
            app.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/consultaCombosRegistroFactoresRiegososPuestos";
            var fill = {};
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false;
                    if (response.data.status == 200)
                        this.consultaDatosFactoresRiegososPuestos =
                            response.data.datos;
                })
                .catch((error) => {
                    app.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },

        async eliminarFactorRiesgoLaboral(id) {
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
                            "/historias/eliminarFactorRiesgoLaboral";
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                if (response.data.status != "200")
                                    alertToast(response.data.datos, 3500);
                                else {
                                    cargarDatatableFactoresRiesgosos();
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

        async guardarFactoresRiesgosos() {
            var list = $("input[name='factores_laborales']:checked")
                .map(function () {
                    return this.value;
                })
                .get()
                .join();
            if (list == "" || list == null) {
                alertToast("Debe grabar por lo menos un elemento de la lista");
                return false;
            }
            var error = buscarErroresInput("factores_riesgos_puesto");
            if (error) return false;

            app_factores_riesgosos.formCrear_externos.factores_riesgos_puesto.detalles =
                list;

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
                            "/historias/guardarFactoresRiesgosos";
                        app_factores_riesgosos.formCrear_externos.factores_riesgos_puesto.identificacion =
                            app.formCrear.identificacion;

                        if (
                            this.formCrear_externos.factores_riesgos_puesto
                                .codigo == ""
                        )
                            this.formCrear_externos.factores_riesgos_puesto.codigo =
                                app_datos.formCrear.codigo;

                        axios
                            .post(
                                urlKeeps,
                                app_factores_riesgosos.formCrear_externos
                                    .factores_riesgos_puesto
                            )
                            .then((response) => {
                                console.log(response);
                                if (response.data.status != "200")
                                    alertToast(response.data.message);
                                else {
                                    alertToastSuccess(response.data.message);
                                    app_factores_riesgosos.limpiarFactoresRiegosos();
                                    cargarDatatableFactoresRiesgosos();
                                    document
                                        .querySelector(
                                            "#cerrar_modal_factores_riesgos_puesto"
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
