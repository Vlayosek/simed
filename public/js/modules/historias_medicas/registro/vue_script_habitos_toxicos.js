var app_habitos = new Vue({
    el: "#main_habitos",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,
            formCrear_habitos: {
                id: 0,
                descripcion: "",
                valor: true,
                tiempo_consumo: "",
                cantidad: "",
                ex_consumidor: false,
                tiempo_abstinencia: "",
                codigo: "",
            },
            consultaDatosHabitos: [],
            editar: true,
        };
    },
    created: function () {},
    methods: {
        limpiarHabitos: function () {
            this.formCrear_habitos.id = 0;
            this.formCrear_habitos.descripcion = "";
            this.formCrear_habitos.valor = "";
            this.formCrear_habitos.tiempo_consumo = "";
            this.formCrear_habitos.cantidad = "";
            this.formCrear_habitos.ex_consumidor = false;
            this.formCrear_habitos.tiempo_abstinencia = "";
            this.formCrear_habitos.valor = true;
            this.formCrear_habitos.codigo = "";
        },
        async editarHabito(id) {
            app.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/editarHabito";
            var fill = {
                id: id,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false;
                    if (response.data.status == 200) {
                        this.formCrear_habitos = response.data.datos;
                        this.formCrear_habitos.descripcion =
                            response.data.datos.descripcion;
                        this.consultaDatos = true;
                    } else {
                        toastr["error"]("Imposible cargar");
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async eliminarHabito(id) {
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
                            "/historias/eliminarHabito";
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                if (response.data.status != "200")
                                    toastr["error"](response.data.message);
                                else {
                                    cargarDatatableHabitos();
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

        async guardarHabitos() {
            var error = buscarErroresInput("habito");
            if (error) return false;
            if (this.formCrear_habitos.codigo == "")
                this.formCrear_habitos.codigo = app_datos.formCrear.codigo;
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
                            "/historias/guardarHabitos";
                        app_habitos.formCrear_habitos.identificacion =
                            app.formCrear.identificacion;

                        axios
                            .post(urlKeeps, app_habitos.formCrear_habitos)
                            .then((response) => {
                                if (response.data.status != "200")
                                    toastr["error"](response.data.message);
                                else {
                                    toastr["success"](response.data.message);
                                    app_habitos.limpiarHabitos();
                                    cargarDatatableHabitos();
                                    document
                                        .querySelector("#cerrar_modal_habitos")
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

        async consultaComboHabitos() {
            app.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/consultaComboHabitos";
            var fill = {};
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false;
                    if (response.data.status == 200) {
                        this.consultaDatosHabitos = response.data.habitos;
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
    },
});
