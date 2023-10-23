var app_antecedentes_reproductivos_masculinos = new Vue({
    el: "#main_antecedentes_reproductivos_masculino",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,
            cargando: false,
            formAntecedenteReproductivo: {
                id: 0,
                identificacion: "",
                planificacion_familiar: true,
                tipo_planificacion_familiar: "",
                hijos_vivos: 0,
                hijos_muertos: 0,
                tipo_guardar: "",
                codigo: "",
            },
            editar: true,
        };
    },
    created: function () {},
    methods: {
        limpiarAntecedente: function () {
            this.formAntecedenteReproductivo.id = 0;
            this.formAntecedenteReproductivo.identificacion = "";
            this.formAntecedenteReproductivo.planificacion_familiar = true;
            this.formAntecedenteReproductivo.tipo_planificacion_familiar = "";
            this.formAntecedenteReproductivo.hijos_vivos = 0;
            this.formAntecedenteReproductivo.hijos_muertos = 0;
            this.formAntecedenteReproductivo.tipo_guardar = "";
            this.formAntecedenteReproductivo.codigo = "";
            $(
                "input:radio[name=planificacion_familiar_reproductivo]"
            )[0].checked = true;
            $(
                "input:radio[name=planificacion_familiar_reproductivo]"
            )[1].checked = false;
            $(".erroresInput").addClass("hidden");
        },
        async guardar() {
            app_antecedentes_reproductivos_masculinos.cargando = true;
            var error = buscarErroresInput("antecedentes_reproductivos");
            if (error) {
                app_antecedentes_reproductivos_masculinos.cargando = false;
                return false;
            }
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/reproductivo_masculino/guardar";
            this.formAntecedenteReproductivo.identificacion =
                app.formCrear.identificacion;
            if (this.formAntecedenteReproductivo.codigo == "")
                this.formAntecedenteReproductivo.codigo =
                    app_datos.formCrear.codigo;
            this.formAntecedenteReproductivo.tipo_guardar = "antecedente";
            await axios
                .post(urlKeeps, this.formAntecedenteReproductivo)
                .then((response) => {
                    app_antecedentes_reproductivos_masculinos.cargando = false;
                    if (response.data.status == 200) {
                        alertToastSuccess(response.data.message, 3500);
                        $(
                            "#cerrar_antecedente_reproductivos_masculinos"
                        ).trigger("click");
                        this.limpiarAntecedente();
                        cargarDatatableAntecedentesReproductivosMasculinos();
                    } else {
                        if (response.data.status == 300)
                            alertToast(response.data.message, 3500);
                        else alertToast("Error en la transacción", 3500);
                    }
                })
                .catch((error) => {
                    app_antecedentes_reproductivos_masculinos.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async editarRegistro(id, tipo) {
            app_antecedentes_reproductivos_masculinos.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/reproductivo_masculino/editar";
            var fill = {
                id: id,
                tipo: tipo,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app_antecedentes_reproductivos_masculinos.cargando = false;
                    if (response.data.status == 200) {
                        this.limpiarAntecedente();
                        this.formAntecedenteReproductivo = response.data.datos;
                        if (
                            this.formAntecedenteReproductivo
                                .planificacion_familiar == true
                        )
                            $("#planificacion_si_reproductivo").prop(
                                "checked",
                                true
                            );
                        else {
                            if (
                                this.formAntecedenteReproductivo
                                    .planificacion_familiar == false
                            )
                                $("#planificacion_no_reproductivo").prop(
                                    "checked",
                                    true
                                );
                            else {
                                $(
                                    "input:radio[name=planificacion_familiar_reproductivo]"
                                )[0].checked = false;
                                $(
                                    "input:radio[name=planificacion_familiar_reproductivo]"
                                )[1].checked = false;
                            }
                        }
                    }
                })
                .catch((error) => {
                    app_antecedentes_reproductivos_masculinos.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async eliminar(id) {
            app_antecedentes_reproductivos_masculinos.cargando = true;
            var fill = {
                id: id,
                tipo: "antecedente",
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
                            "/historias/reproductivo_masculino/eliminar";
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                app_antecedentes_reproductivos_masculinos.cargando = false;
                                if (response.data.status != "200")
                                    toastr["error"](response.data.message);
                                else {
                                    cargarDatatableAntecedentesReproductivosMasculinos();
                                    toastr["success"](response.data.message);
                                }
                            })
                            .catch((error) => {
                                app_antecedentes_reproductivos_masculinos.cargando = false;
                                alertToast("Cancelado!", 3500);
                                //swal.fire("Cancelado!", "Error al grabar...", error);
                            });
                    } else if (result.isDenied) {
                        app_antecedentes_reproductivos_masculinos.cargando = false;
                        alertToast("Cancelado!", 3500);
                        //swal.fire("Cancelado!", "Error al grabar...", error);
                        return false;
                    }
                });
        },
    },
});
