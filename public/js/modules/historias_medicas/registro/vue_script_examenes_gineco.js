var app_examenes_gineco = new Vue({
    el: "#main_examenes_gineco",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,
            cargando: false,
            formExamenGineco: {
                id: 0,
                identificacion: "",
                tipo_examen: "",
                realizo_examen: true,
                tiempo: "",
                resultado: "",
                tipo_guardar: "",
                codigo: "",
            },
            editar: true,
        };
    },
    created: function () {},
    methods: {
        limpiarExamen: function () {
            this.formExamenGineco.id = 0;
            this.formExamenGineco.identificacion = "";
            this.formExamenGineco.tipo_examen = "";
            this.formExamenGineco.realizo_examen = true;
            this.formExamenGineco.tiempo = "";
            this.formExamenGineco.resultado = "";
            this.formExamenGineco.tipo_guardar = "";
            this.formExamenGineco.codigo = "";
            $("#tipo_examen option:first-child").attr("disabled", "disabled");
            $("#tipo_examen").val(null);
            $("input:radio[name=examen_gineco]")[0].checked = true;
            $("input:radio[name=examen_gineco]")[1].checked = false;
            $(".erroresInput").addClass("hidden");
        },
        async guardar() {
            var tipo_examen = $("#tipo_examen").val();
            var checkExamen = $("#examen_no").val();
            if ((tipo_examen == null || tipo_examen == "") && !checkExamen) {
                alertToast("Debe llenar el campo Tipo de examen");
                return false;
            }

            app_examenes_gineco.cargando = true;
            var error = buscarErroresInput("examen_gineco");
            if (error) {
                app_examenes_gineco.cargando = false;
                return false;
            }
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/gineco_obstetrico/guardar";
            this.formExamenGineco.identificacion = app.formCrear.identificacion;
            if (this.formExamenGineco.codigo == "")
                this.formExamenGineco.codigo = app_datos.formCrear.codigo;
            this.formExamenGineco.tipo_guardar = "examen";
            await axios
                .post(urlKeeps, this.formExamenGineco)
                .then((response) => {
                    app_examenes_gineco.cargando = false;
                    if (response.data.status == 200) {
                        alertToastSuccess(response.data.message, 3500);
                        cargarDatatableExamenesGineco();
                        if (this.formExamenGineco.id != 0)
                            $("#cerrar_examen_gineco").trigger("click");
                        this.limpiarExamen();
                    } else {
                        if (response.data.status == 300)
                            alertToast(response.data.message, 3500);
                        else alertToast("Error en la transacción", 3500);
                    }
                })
                .catch((error) => {
                    app_examenes_gineco.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async editarRegistro(id, tipo) {
            app_examenes_gineco.cargando = true;
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
                    app_examenes_gineco.cargando = false;
                    if (response.data.status == 200) {
                        this.limpiarExamen();
                        this.formExamenGineco = response.data.datos;
                        if (this.formExamenGineco.realizo_examen == true)
                            $("#examen_si").prop("checked", true);
                        else {
                            if (this.formExamenGineco.realizo_examen == false)
                                $("#examen_no").prop("checked", true);
                            else {
                                $(
                                    "input:radio[name=examen_gineco]"
                                )[0].checked = false;
                                $(
                                    "input:radio[name=examen_gineco]"
                                )[1].checked = false;
                            }
                        }
                    }
                })
                .catch((error) => {
                    app_examenes_gineco.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async eliminar(id) {
            app_examenes_gineco.cargando = true;
            var fill = {
                id: id,
                tipo: "examen",
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
                                app_examenes_gineco.cargando = false;
                                if (response.data.status != "200")
                                    toastr["error"](response.data.message);
                                else {
                                    cargarDatatableExamenesGineco();
                                    toastr["success"](response.data.message);
                                }
                            })
                            .catch((error) => {
                                app_examenes_gineco.cargando = false;
                                alertToast("Cancelado!", 3500);
                                //swal.fire("Cancelado!", "Error al grabar...", error);
                            });
                    } else if (result.isDenied) {
                        app_examenes_gineco.cargando = false;
                        alertToast("Cancelado!", 3500);
                        //swal.fire("Cancelado!", "Error al grabar...", error);
                        return false;
                    }
                });
        },
    },
});
