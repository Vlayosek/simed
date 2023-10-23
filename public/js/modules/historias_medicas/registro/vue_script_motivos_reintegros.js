var app_motivo_reintegro = new Vue({
    el: "#main_motivos_reintegros",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,
            cargando: false,
            formMotivoReintegro: {
                id: 0,
                identificacion: "",
                descripcion: "",
                codigo: "",
            },
            editar: true,
        };
    },
    created: function () {},
    methods: {
        limpiarReintegro: function () {
            this.formMotivoReintegro.id = 0;
            this.formMotivoReintegro.identificacion = "";
            this.formMotivoReintegro.descripcion = "";
            this.formMotivoReintegro.codigo = "";
            $(".erroresInput").addClass("hidden");
        },
        async guardar() {
            this.cargando = true;
            var error = buscarErroresInput("motivo_reintegro_");
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/reintegro/guardar";
            if (error) {
                this.cargando = false;
                return false;
            }
            //var urlKeeps = "reintegro/guardar";
            this.formMotivoReintegro.identificacion =
                app.formCrear.identificacion;
            if (this.formMotivoReintegro.codigo == "")
                this.formMotivoReintegro.codigo = app_datos.formCrear.codigo;
            await axios
                .post(urlKeeps, this.formMotivoReintegro)
                .then((response) => {
                    this.cargando = false;
                    if (response.data.status == 200) {
                        alertToastSuccess(response.data.message, 3500);
                        cargarDatatableMotivosReintegros();
                        if (this.formMotivoReintegro.id != 0)
                            $("#cerrar_modal_reintegro").trigger("click");
                        this.limpiarReintegro();
                    } else {
                        alertToast("Error en la transacción", 3500);
                    }
                })
                .catch((error) => {
                    this.cargando = false;
                    alertToast("Error al grabar!", 3500);
                    //swal.fire("Cancelado!", "Error al grabar...", "error");
                });
        },
        async editarRegistro(id) {
            this.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/reintegro/editar";
            var fill = {
                id: id,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargando = false;
                    if (response.data.status == 200) {
                        this.limpiarReintegro();
                        this.formMotivoReintegro = response.data.datos;
                    }
                })
                .catch((error) => {
                    this.cargando = false;
                    alertToast("Error al grabar!", 3500);
                });
        },
        async eliminar(id) {
            this.cargando = true;
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
                            "/historias/reintegro/eliminar";
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                this.cargando = false;
                                if (response.data.status != "200")
                                    toastr["error"](response.data.message);
                                else {
                                    cargarDatatableDiagnosticos();
                                    toastr["success"](response.data.message);
                                }
                            })
                            .catch((error) => {
                                this.cargando = false;
                                alertToast("Cancelado!", 3500);
                                //swal.fire("Cancelado!", "Error al grabar...", error);
                            });
                    } else if (result.isDenied) {
                        this.cargando = false;
                        alertToast("Cancelado!", 3500);
                        //swal.fire("Cancelado!", "Error al grabar...", error);
                        return false;
                    }
                });
        },
    },
});
