var app_examenes_reproductivos_masculinos = new Vue({
    el: "#main_examenes_reproductivos_masculinos",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,
            cargando: false,
            formExamenReproductivo: {
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
            this.formExamenReproductivo.id = 0;
            this.formExamenReproductivo.identificacion = "";
            this.formExamenReproductivo.tipo_examen = "";
            this.formExamenReproductivo.realizo_examen = true;
            this.formExamenReproductivo.tiempo = "";
            this.formExamenReproductivo.resultado = "";
            this.formExamenReproductivo.tipo_guardar = "";
            this.formExamenReproductivo.codigo = "";
            $("#tipo_examen option:first-child").attr("disabled", "disabled");
            $("#tipo_examen").val(null);
            $("input:radio[name=examen_reproductivo]")[0].checked = true;
            $("input:radio[name=examen_reproductivo]")[1].checked = false;
            $(".erroresInput").addClass("hidden");
        },
        async guardar() {
            app_examenes_reproductivos_masculinos.cargando = true;
            var error = buscarErroresInput("examenes_reproductivos");
            if (error) {
                app_examenes_reproductivos_masculinos.cargando = false;
                return false;
            }
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/reproductivo_masculino/guardar";
            this.formExamenReproductivo.identificacion =
                app.formCrear.identificacion;
            if (this.formExamenReproductivo.codigo == "")
                this.formExamenReproductivo.codigo = app_datos.formCrear.codigo;
            this.formExamenReproductivo.tipo_guardar = "examen";
            await axios
                .post(urlKeeps, this.formExamenReproductivo)
                .then((response) => {
                    app_examenes_reproductivos_masculinos.cargando = false;
                    if (response.data.status == 200) {
                        alertToastSuccess(response.data.message, 3500);
                        cargarDatatableExamenesReproductivosMasculinos();
                        if (this.formExamenReproductivo.id != 0)
                            $(
                                "#cerrar_examen_reproductivos_masculinos"
                            ).trigger("click");
                        this.limpiarExamen();
                    } else {
                        if (response.data.status == 300)
                            alertToast(response.data.message, 3500);
                        else alertToast("Error en la transacción", 3500);
                    }
                })
                .catch((error) => {
                    app_examenes_reproductivos_masculinos.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async editarRegistro(id, tipo) {
            app_examenes_reproductivos_masculinos.cargando = true;
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
                    app_examenes_reproductivos_masculinos.cargando = false;
                    if (response.data.status == 200) {
                        this.limpiarExamen();
                        this.formExamenReproductivo = response.data.datos;
                        if (this.formExamenReproductivo.realizo_examen == true)
                            $("#examen_si_reproductivo").prop("checked", true);
                        else {
                            if (
                                this.formExamenReproductivo.realizo_examen ==
                                false
                            )
                                $("#examen_no_reproductivo").prop(
                                    "checked",
                                    true
                                );
                            else {
                                $(
                                    "input:radio[name=examen_reproductivo]"
                                )[0].checked = false;
                                $(
                                    "input:radio[name=examen_reproductivo]"
                                )[1].checked = false;
                            }
                        }
                    }
                })
                .catch((error) => {
                    app_examenes_reproductivos_masculinos.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async eliminar(id) {
            app_examenes_reproductivos_masculinos.cargando = true;
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
                            "/historias/reproductivo_masculino/eliminar";
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                app_examenes_reproductivos_masculinos.cargando = false;
                                if (response.data.status != "200")
                                    toastr["error"](response.data.message);
                                else {
                                    cargarDatatableExamenesReproductivosMasculinos();
                                    toastr["success"](response.data.message);
                                }
                            })
                            .catch((error) => {
                                app_examenes_reproductivos_masculinos.cargando = false;
                                alertToast("Cancelado!", 3500);
                                //swal.fire("Cancelado!", "Error al grabar...", error);
                            });
                    } else if (result.isDenied) {
                        app_examenes_reproductivos_masculinos.cargando = false;
                        alertToast("Cancelado!", 3500);
                        //swal.fire("Cancelado!", "Error al grabar...", error);
                        return false;
                    }
                });
        },
    },
});
