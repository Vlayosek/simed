var app_aptitudes_medicas = new Vue({
    el: "#main_aptitudes_medicas",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,
            cargando: false,
            formAptitud: {
                id: 0,
                identificacion: "",
                codigo: "",
                aptitud: "",
                observacion: "",
                limitacion: "",
            },
            editar: true,
        };
    },
    created: function () {},
    methods: {
        limpiarAptitud: function () {
            this.formAptitud.id = 0;
            this.formAptitud.identificacion = "";
            this.formAptitud.codigo = "";
            this.formAptitud.aptitud = "";
            this.formAptitud.observacion = "";
            this.formAptitud.limitacion = "";
            $(".erroresInput").addClass("hidden");
        },
        async guardar() {
            app_aptitudes_medicas.cargando = true;
            var error = buscarErroresInput("aptitud_");
            if (error) {
                app_aptitudes_medicas.cargando = false;
                return false;
            }
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/aptitud_medica/guardar";
            this.formAptitud.identificacion = app.formCrear.identificacion;
            if (this.formAptitud.codigo == "")
                this.formAptitud.codigo = app_datos.formCrear.codigo;
            await axios
                .post(urlKeeps, this.formAptitud)
                .then((response) => {
                    app_aptitudes_medicas.cargando = false;

                    if (response.data.status != "200")
                        toastr["error"](response.data.message);
                    else {
                        toastr["success"](response.data.message);
                        cargarDatatableAptitudesMedicas();
                        // if (this.formAptitud.id != 0)
                            $("#cerrar_modal_aptitud_medica").trigger("click");
                        this.limpiarAptitud();
                    }
                })
                .catch((error) => {
                    app_aptitudes_medicas.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async editarRegistro(id) {
            app_aptitudes_medicas.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/aptitud_medica/editar";
            var fill = {
                id: id,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app_aptitudes_medicas.cargando = false;
                    if (response.data.status == 200) {
                        this.limpiarAptitud();
                        this.formAptitud = response.data.datos;
                    }
                })
                .catch((error) => {
                    app_aptitudes_medicas.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async eliminar(id) {
            app_aptitudes_medicas.cargando = true;
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
                            "/historias/aptitud_medica/eliminar";
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                app_aptitudes_medicas.cargando = false;
                                if (response.data.status != "200")
                                    toastr["error"](response.data.message);
                                else {
                                    cargarDatatableAptitudesMedicas();
                                    toastr["success"](response.data.message);
                                }
                            })
                            .catch((error) => {
                                app_aptitudes_medicas.cargando = false;
                                alertToast("Cancelado!", 3500);
                                //swal.fire("Cancelado!", "Error al grabar...", error);
                            });
                    } else if (result.isDenied) {
                        app_aptitudes_medicas.cargando = false;
                        alertToast("Cancelado!", 3500);
                        //swal.fire("Cancelado!", "Error al grabar...", error);
                        return false;
                    }
                });
        },
    },
});
