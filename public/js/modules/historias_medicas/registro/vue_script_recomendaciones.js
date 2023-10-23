var app_recomendaciones = new Vue({
    el: "#main_recomendaciones",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,
            cargando: false,
            formRecomendacion: {
                id: 0,
                identificacion: "",
                codigo: "",
                recomendacion: "",
            },
            editar: true,
        };
    },
    created: function () {},
    methods: {
        limpiarRecomendacion: function () {
            this.formRecomendacion.id = 0;
            this.formRecomendacion.identificacion = "";
            this.formRecomendacion.codigo = "";
            this.formRecomendacion.recomendacion = "";
            $(".erroresInput").addClass("hidden");
        },
        async guardar() {
            app_recomendaciones.cargando = true;
            var error = buscarErroresInput("recomendacion_");
            if (error) {
                app_recomendaciones.cargando = false;
                return false;
            }
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/recomendacion/guardar";
            this.formRecomendacion.identificacion =
                app.formCrear.identificacion;
            if (this.formRecomendacion.codigo == "")
                this.formRecomendacion.codigo = app_datos.formCrear.codigo;
            await axios
                .post(urlKeeps, this.formRecomendacion)
                .then((response) => {
                    app_recomendaciones.cargando = false;

                    if (response.data.status != "200")
                        toastr["error"](response.data.message);
                    else {
                        toastr["success"](response.data.message);
                        cargarDatatableRecomendaciones();
                        if (this.formRecomendacion.id != 0)
                            $("#cerrar_modal_recomendaciones").trigger("click");
                        this.limpiarRecomendacion();
                        document
                            .querySelector("#cerrar_modal_recomendaciones")
                            .click();
                    }
                })
                .catch((error) => {
                    app_recomendaciones.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async editarRegistro(id) {
            app_recomendaciones.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/recomendacion/editar";
            var fill = {
                id: id,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app_recomendaciones.cargando = false;
                    if (response.data.status == 200) {
                        this.limpiarRecomendacion();
                        this.formRecomendacion = response.data.datos;
                    }
                })
                .catch((error) => {
                    app_recomendaciones.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async eliminar(id) {
            app_recomendaciones.cargando = true;
            var fill = {
                id: id,
            };
            await swal
                .fire({
                    title: "Estás seguro de realizar esta acción",
                    text: "Al confirmar se eliminaran los datos exitosamente",
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
                            "/historias/recomendacion/eliminar";
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                app_recomendaciones.cargando = false;
                                if (response.data.status != "200")
                                    toastr["error"](response.data.message);
                                else {
                                    cargarDatatableRecomendaciones();
                                    toastr["success"](response.data.message);
                                }
                            })
                            .catch((error) => {
                                app_recomendaciones.cargando = false;
                                alertToast("Cancelado!", 3500);
                                //swal.fire("Cancelado!", "Error al grabar...", error);
                            });
                    } else if (result.isDenied) {
                        app_recomendaciones.cargando = false;
                        alertToast("Cancelado!", 3500);
                        //swal.fire("Cancelado!", "Error al grabar...", error);
                        return false;
                    }
                });
        },
    },
});
