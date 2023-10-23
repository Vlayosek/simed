var app_antecedentes_gineco = new Vue({
    el: "#main_antecedentes_gineco",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,
            cargando: false,
            formAntecedenteGineco: {
                id: 0,
                identificacion: "",
                menarquia: "",
                ciclos: "",
                menstruacion: "",
                gestas: 0,
                partos: 0,
                cesareas: 0,
                abortos: 0,
                hijos_vivos: 0,
                hijos_muertos: 0,
                vida_sexual: 0,
                planificacion_familiar: "",
                tipo_planificacion_familiar: "",
                tipo_guardar: "",
                codigo: "",
            },
            editar: true,
        };
    },
    created: function () {},
    methods: {
        limpiarAntecedente: function () {
            this.formAntecedenteGineco.id = 0;
            this.formAntecedenteGineco.identificacion = "";
            this.formAntecedenteGineco.menarquia = "";
            this.formAntecedenteGineco.ciclos = "";
            this.formAntecedenteGineco.menstruacion = "";
            this.formAntecedenteGineco.gestas = 0;
            this.formAntecedenteGineco.partos = 0;
            this.formAntecedenteGineco.cesareas = 0;
            this.formAntecedenteGineco.abortos = 0;
            this.formAntecedenteGineco.hijos_vivos = 0;
            this.formAntecedenteGineco.hijos_muertos = 0;
            this.formAntecedenteGineco.vida_sexual = "";
            this.formAntecedenteGineco.planificacion_familiar = "";
            this.formAntecedenteGineco.tipo_planificacion_familiar = "";
            this.formAntecedenteGineco.tipo_guardar = "";
            this.formAntecedenteGineco.codigo = "";
            $("input:radio[name=planificacion_familiar]")[0].checked = false;
            $("input:radio[name=planificacion_familiar]")[1].checked = false;
            $("input:radio[name=vida_sexual]")[0].checked = false;
            $("input:radio[name=vida_sexual]")[1].checked = false;
            $(".erroresInput").addClass("hidden");
        },
        async guardar() {
            app_antecedentes_gineco.cargando = true;
            var error = buscarErroresInput("antecedentes_gineco");
            if (error) {
                app_antecedentes_gineco.cargando = false;
                return false;
            }
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/gineco_obstetrico/guardar";
            this.formAntecedenteGineco.identificacion =
                app.formCrear.identificacion;
            this.formAntecedenteGineco.tipo_guardar = "antecedente";
            if (this.formAntecedenteGineco.codigo == "")
                this.formAntecedenteGineco.codigo = app_datos.formCrear.codigo;

            await axios
                .post(urlKeeps, this.formAntecedenteGineco)
                .then((response) => {
                    app_antecedentes_gineco.cargando = false;
                    if (response.data.status == 200) {
                        alertToastSuccess(response.data.message, 3500);
                        $("#cerrar_antecedente_gineco").trigger("click");
                        this.limpiarAntecedente();
                        cargarDatatableAntecedentesGineco();
                    } else {
                        if (response.data.status == 300)
                            alertToast(response.data.message, 3500);
                        else alertToast("Error en la transacción", 3500);
                    }
                })
                .catch((error) => {
                    app_antecedentes_gineco.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async editarRegistro(id, tipo) {
            app_antecedentes_gineco.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/gineco_obstetrico/editar";
            var fill = {
                id: id,
                tipo: tipo,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app_antecedentes_gineco.cargando = false;
                    if (response.data.status == 200) {
                        this.limpiarAntecedente();
                        this.formAntecedenteGineco = response.data.datos;
                        if (this.formAntecedenteGineco.vida_sexual == true)
                            $("#vida_sexual_si").prop("checked", true);
                        else {
                            if (this.formAntecedenteGineco.vida_sexual == false)
                                $("#vida_sexual_no").prop("checked", true);
                            else {
                                $(
                                    "input:radio[name=vida_sexual]"
                                )[0].checked = false;
                                $(
                                    "input:radio[name=vida_sexual]"
                                )[1].checked = false;
                            }
                        }
                        if (
                            this.formAntecedenteGineco.planificacion_familiar ==
                            true
                        )
                            $("#planificacion_si").prop("checked", true);
                        else {
                            if (
                                this.formAntecedenteGineco
                                    .planificacion_familiar == false
                            )
                                $("#planificacion_no").prop("checked", true);
                            else {
                                $(
                                    "input:radio[name=planificacion_familiar]"
                                )[0].checked = false;
                                $(
                                    "input:radio[name=planificacion_familiar]"
                                )[1].checked = false;
                            }
                        }
                    }
                })
                .catch((error) => {
                    app_antecedentes_gineco.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async eliminar(id) {
            app_antecedentes_gineco.cargando = true;
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
                            "/historias/gineco_obstetrico/eliminar";
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                app_antecedentes_gineco.cargando = false;
                                if (response.data.status != "200")
                                    toastr["error"](response.data.message);
                                else {
                                    cargarDatatableAntecedentesGineco();
                                    toastr["success"](response.data.message);
                                }
                            })
                            .catch((error) => {
                                app_antecedentes_gineco.cargando = false;
                                alertToast("Cancelado!", 3500);
                                //swal.fire("Cancelado!", "Error al grabar...", error);
                            });
                    } else if (result.isDenied) {
                        app_antecedentes_gineco.cargando = false;
                        alertToast("Cancelado!", 3500);
                        //swal.fire("Cancelado!", "Error al grabar...", error);
                        return false;
                    }
                });
        },
    },
});
