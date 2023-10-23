var app_estilo_vida = new Vue({
    el: "#main_estilo_vida",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,
            formCrear_estilo_vida: {
                id: 0,
                descripcion: "",
                valor: true,
                tiempo_cantidad: "",
                tipo_estilo_vida: "",
                codigo: "",
            },
            consultaDatosEstiloVida: [],
            editar: true,
            tiempo_cantidad: "",
        };
    },
    created: function () {
        //this.limpiarEstiloVida();
        //this.consultaComboEstiloVida();
    },
    methods: {
        limpiarEstiloVida: function () {
            this.formCrear_estilo_vida.id = 0;
            this.formCrear_estilo_vida.descripcion = "";
            this.formCrear_estilo_vida.valor = true;
            this.formCrear_estilo_vida.tiempo_cantidad = "";
            this.formCrear_estilo_vida.tipo_estilo_vida = "";
            this.consultaDatos = false;
            this.tiempo_cantidad = "";
            $(".erroresInput").addClass("hidden");
            //  this.consultaComboEstiloVida();
        },
        async editarEstiloVida(id) {
            app.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/editarEstiloVida";
            var fill = {
                id: id,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false;
                    if (response.data.status == 200) {
                        this.formCrear_estilo_vida = response.data.datos;
                        this.formCrear_estilo_vida.descripcion =
                            response.data.datos.descripcion;
                        this.tiempo_cantidad =
                            this.formCrear_estilo_vida.descripcion;
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
        async eliminarEstiloVida(id) {
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
                            "/historias/eliminarEstiloVida";
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                if (response.data.status != "200")
                                    toastr["error"](response.data.message);
                                else {
                                    cargarDatatableEstiloVida();
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

        async guardarEstiloVida() {
            var error = buscarErroresInput("estilo_vida");
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
                            "/historias/guardarEstiloVida";
                        this.formCrear_estilo_vida.identificacion =
                            app.formCrear.identificacion;
                        if (this.formCrear_estilo_vida.codigo == "")
                            this.formCrear_estilo_vida.codigo =
                                app_datos.formCrear.codigo;
                        axios
                            .post(urlKeeps, this.formCrear_estilo_vida)
                            .then((response) => {
                                if (response.data.status != "200")
                                    toastr["error"](response.data.message);
                                else {
                                    toastr["success"](response.data.message);
                                    this.limpiarEstiloVida();
                                    cargarDatatableEstiloVida();
                                    this.consultaDatos = false;
                                    document
                                        .querySelector(
                                            "#cerrar_modal_estilo_vida"
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

        async consultaComboEstiloVida() {
            app.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/consultaComboEstiloVida";
            //var urlKeeps = document.querySelector("#inicializacion").value +"/historias/consultaComboEstiloVida";
            var fill = {};
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false;
                    if (response.data.status == 200) {
                        this.consultaDatosEstiloVida =
                            response.data.estilo_vida;
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", "error");
                });
        },
    },
});
