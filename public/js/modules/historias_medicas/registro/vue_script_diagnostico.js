var app_diagnostico = new Vue({
    el: "#main_diagnostico",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,
            cargando: false,
            formDiagnostico: {
                id: 0,
                identificacion: "",
                descripcion: "",
                codigo_cie_id: "",
                cie_descripcion: "",
                tipo: "",
                codigo: "",
                evaluacion:"",
            },
            editar: true,
        };
    },
    created: function () {},
    methods: {
        limpiarDiagnostico: function () {
            this.formDiagnostico.id = 0;
            this.formDiagnostico.identificacion = "";
            this.formDiagnostico.descripcion = "";
            this.formDiagnostico.codigo_cie_id = "";
            this.formDiagnostico.cie_descripcion = "";
            this.formDiagnostico.tipo = "";
            this.formDiagnostico.codigo = "";
            $("#codigo_cie").val(null).trigger("change");
            $("input:radio[name=tipo_diagnostico]")[0].checked = false;
            $("input:radio[name=tipo_diagnostico]")[1].checked = false;
            $(".erroresInput").addClass("hidden");
        },
        async guardar() {
            app_diagnostico.cargando = true;
            var error = buscarErroresInput("diagnostico_");
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/diagnostico/guardar";
            if (error) {
                app_diagnostico.cargando = false;
                return false;
            }
            //  var urlKeeps = "diagnostico/guardar";
            this.formDiagnostico.identificacion = app.formCrear.identificacion;
            if (this.formDiagnostico.codigo == "")
                this.formDiagnostico.codigo = app_datos.formCrear.codigo;
            this.formDiagnostico.evaluacion = app_datos.formCrear.tipo_evaluacion;
            await axios
                .post(urlKeeps, this.formDiagnostico)
                .then((response) => {
                    this.cargando = false;
                    if (response.data.status != "200")
                        toastr["error"](response.data.message);
                    else {
                        toastr["success"](response.data.message);
                        cargarDatatableDiagnosticos();
                        if (this.formDiagnostico.id != 0)
                            $("#cerrar_modal_diagnostico").trigger("click");
                        this.limpiarDiagnostico();
                    }
                })
                .catch((error) => {
                    app_diagnostico.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async editarRegistro(id) {
            app_diagnostico.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/diagnostico/editar";
            var fill = {
                id: id,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app_diagnostico.cargando = false;
                    if (response.data.status == 200) {
                        this.limpiarDiagnostico();
                        this.formDiagnostico = response.data.datos;
                        this.formDiagnostico.cie_descripcion =
                            response.data.cie_descripcion;
                        $("#codigo_cie").trigger("click");
                        if (this.formDiagnostico.tipo == "PRESUNTIVO")
                            $("#presuntivo_chk").prop("checked", true);
                        else {
                            if (this.formDiagnostico.tipo == "DEFINITIVO")
                                $("#definitivo_chl").prop("checked", true);
                            else {
                                $(
                                    "input:radio[name=tipo_diagnostico]"
                                )[0].checked = false;
                                $(
                                    "input:radio[name=tipo_diagnostico]"
                                )[1].checked = false;
                            }
                        }
                    }
                })
                .catch((error) => {
                    app_diagnostico.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async eliminar(id) {
            app_diagnostico.cargando = true;
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
                            "/historias/diagnostico/eliminar";
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                app_diagnostico.cargando = false;
                                if (response.data.status != "200")
                                    toastr["error"](response.data.message);
                                else {
                                    cargarDatatableDiagnosticos();
                                    toastr["success"](response.data.message);
                                }
                            })
                            .catch((error) => {
                                app_diagnostico.cargando = false;
                                alertToast("Cancelado!", 3500);
                                //swal.fire("Cancelado!", "Error al grabar...", error);
                            });
                    } else if (result.isDenied) {
                        app_diagnostico.cargando = false;
                        alertToast("Cancelado!", 3500);
                        //swal.fire("Cancelado!", "Error al grabar...", error);
                        return false;
                    }
                });
        },
    },
});
