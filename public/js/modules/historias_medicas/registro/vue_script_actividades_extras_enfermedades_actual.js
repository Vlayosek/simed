var app_actividades_extras_enfermedades_actual = new Vue({
    el: "#main_actividades_extras_enfermedades_actual",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,
            formCrear_externos: {
                actividades_extras: {
                    descripcion: "",
                    codigo: "",
                },
                enfermedad_actual: {
                    descripcion: "",
                    codigo: "",
                },
            },
            habilitaActividad: "",
            editar: true,
        };
    },
    created: function () {
        this.habilitaActividad = "";
    },
    methods: {
        limpiarInput: function () {
            app_actividades_extras_enfermedades_actual.formCrear_externos.actividades_extras.descripcion =
                "";
            app_actividades_extras_enfermedades_actual.formCrear_externos.enfermedad_actual.descripcion =
                "";
            $(".erroresInput").addClass("hidden");
        },
        async guardarActividadExtraEnfermedadActual(tipo) {
            if (tipo == "ACTIVIDAD")
                var error = buscarErroresInput("actividades_extras");
            else var error = buscarErroresInput("enfermedad_actual");

            if (error) {
                return false;
            }

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
                            "/historias/guardarActividadExtraEnfermedadActual";
                        var fill =
                            app_actividades_extras_enfermedades_actual
                                .formCrear_externos.actividades_extras;
                        if (tipo != "ACTIVIDAD")
                            fill =
                                app_actividades_extras_enfermedades_actual
                                    .formCrear_externos.enfermedad_actual;
                        fill.tipo = tipo;
                        fill.identificacion = app.formCrear.identificacion;
                        fill.codigo = app_datos.formCrear.codigo;
                        app.cargando = true;

                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                app.cargando = false;

                                if (response.data.status != "200")
                                    toastr["error"]("Error al guardar datos");
                                else {
                                    toastr["success"](
                                        "Datos guardados correctamente"
                                    );

                                    app_actividades_extras_enfermedades_actual.limpiarInput();
                                    if (tipo == "ACTIVIDAD")
                                        cargarDatatableExtraLaborales();
                                    else cargarDatatableEnfermedadActual();
                                }
                            })
                            .catch((error) => {
                                app.cargando = false;
                                swal.fire(
                                    "Cancelado!",
                                    "Error al grabar...",
                                    "error"
                                );
                            });
                    } else if (result.isDenied) {
                        app.cargando = false;

                        swal.fire(
                            "Cancelado!",
                            "No se registraron cambios...",
                            "error"
                        );
                        return false;
                    }
                });
        },
        async eliminarActividadExtraEnfermedadActual(id, tipo) {
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
                            "/historias/eliminarActividadExtraEnfermedadActual";
                        var fill = {
                            id: id,
                            tipo: tipo,
                        };
                        app.cargando = true;

                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                app.cargando = false;

                                if (response.data.status != "200")
                                    alertToast(response.data.datos, 3500);
                                else {
                                    alertToastSuccess(
                                        response.data.datos,
                                        3500
                                    );
                                    app_actividades_extras_enfermedades_actual.limpiarInput();
                                    if (tipo == "ACTIVIDAD")
                                        cargarDatatableExtraLaborales();
                                    else cargarDatatableEnfermedadActual();
                                }
                            })
                            .catch((error) => {
                                app.cargando = false;
                                swal.fire(
                                    "Cancelado!",
                                    "Error al grabar...",
                                    "error"
                                );
                            });
                    } else if (result.isDenied) {
                        app.cargando = false;

                        swal.fire(
                            "Cancelado!",
                            "No se registraron cambios...",
                            "error"
                        );
                        return false;
                    }
                });
        },
    },
});
