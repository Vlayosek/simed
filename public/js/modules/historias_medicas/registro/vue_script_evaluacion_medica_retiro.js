var app_evaluacion_medica_retiro = new Vue({
    el: "#main_evaluacion_medica_retiro",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,
            cargando: false,
            formEvaluacionMedicaRetiro: {
                id: 0,
                observaciones: "",
                identificacion: "",
                codigo: "",
                evaluacion_realizada: "",
                condicion_diagnostico: "",
                salud_relacionada: "",
            },
            editar: true,
        };
    },
    created: function () {},
    methods: {
        limpiarEvaluacionMedicaRetiro: function () {
            this.formEvaluacionMedicaRetiro.id = 0;
            this.formEvaluacionMedicaRetiro.observaciones = "";
            this.formEvaluacionMedicaRetiro.identificacion = "";
            this.formEvaluacionMedicaRetiro.codigo = "";
            this.formEvaluacionMedicaRetiro.evaluacion_realizada = "";
            this.formEvaluacionMedicaRetiro.condicion_diagnostico = "";
            this.formEvaluacionMedicaRetiro.salud_relacionada = "";
            $("input:radio[name=evaluacion_medica]")[0].checked = false;
            $("input:radio[name=evaluacion_medica]")[1].checked = false;
            $("input:radio[name=condicion_diagnostico]")[0].checked = false;
            $("input:radio[name=condicion_diagnostico]")[1].checked = false;
            $("input:radio[name=condicion_diagnostico]")[2].checked = false;
            $("input:radio[name=salud_relacionada]")[0].checked = false;
            $("input:radio[name=salud_relacionada]")[1].checked = false;
            $("input:radio[name=salud_relacionada]")[2].checked = false;
            $(".erroresInput").addClass("hidden");
        },
        async guardarEvaluacionMedicaRetiro() {
            this.cargando = true;
            var error = buscarErroresInput("evaluacion_medica_retiro");
            if (error) {
                this.cargando = false;
                return false;
            }
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/guardarEvaluacionMedicaRetiro";
            this.formEvaluacionMedicaRetiro.identificacion =
                app.formCrear.identificacion;
            if (this.formEvaluacionMedicaRetiro.codigo == "")
                this.formEvaluacionMedicaRetiro.codigo =
                    app_datos.formCrear.codigo;
            await axios
                .post(urlKeeps, this.formEvaluacionMedicaRetiro)
                .then((response) => {
                    this.cargando = false;

                    if (response.data.status != "200")
                        toastr["error"](response.data.message);
                    else {
                        toastr["success"](response.data.message);
                        cargarDatatableEvaluacionMedicaRetiro();
                        if (this.formEvaluacionMedicaRetiro.id != 0)
                            $("#cerrar_modal_evaluacion_medica_retiro").trigger(
                                "click"
                            );
                        this.limpiarEvaluacionMedicaRetiro();
                    }
                })
                .catch((error) => {
                    this.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async editarRegistro(id) {
            this.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/editarEvaluacionMedicaRetiro";
            var fill = {
                id: id,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargando = false;
                    if (response.data.status == 200) {
                        this.limpiarEvaluacionMedicaRetiro();
                        this.formEvaluacionMedicaRetiro = response.data.datos;
                        if (
                            this.formEvaluacionMedicaRetiro
                                .evaluacion_realizada == true
                        )
                            $("#evaluacion_si").prop("checked", true);
                        else {
                            if (
                                this.formEvaluacionMedicaRetiro
                                    .evaluacion_realizada == false
                            )
                                $("#evaluacion_no").prop("checked", true);
                            else {
                                $(
                                    "input:radio[name=evaluacion_medica]"
                                )[0].checked = false;
                                $(
                                    "input:radio[name=evaluacion_medica]"
                                )[1].checked = false;
                            }
                        }

                        // cond del dx
                        if (
                            this.formEvaluacionMedicaRetiro
                                .condicion_diagnostico == "presuntiva"
                        )
                            $("#dx_presuntiva").prop("checked", true);
                        else if (
                            this.formEvaluacionMedicaRetiro
                                .condicion_diagnostico == "definitiva"
                        )
                            $("#dx_definitiva").prop("checked", true);
                        else {
                            $("#dx_no_aplica").prop("checked", true);
                        }

                        // salud relacionada
                        if (
                            this.formEvaluacionMedicaRetiro.salud_relacionada ==
                            "true"
                        )
                            $("#salud_relacionada_si").prop("checked", true);
                        else if (
                            this.formEvaluacionMedicaRetiro.salud_relacionada ==
                            "false"
                        )
                            $("#salud_relacionada_no").prop("checked", true);
                        else {
                            $("#salud_relacionada_no_aplica").prop(
                                "checked",
                                true
                            );
                        }
                    }
                })
                .catch((error) => {
                    this.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
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
                            "/historias/eliminarEvaluacionMedicaRetiro";
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                this.cargando = false;
                                if (response.data.status != "200")
                                    toastr["error"](response.data.message);
                                else {
                                    cargarDatatableEvaluacionMedicaRetiro();
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
