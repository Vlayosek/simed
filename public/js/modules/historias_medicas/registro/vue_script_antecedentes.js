var app_antecedentes = new Vue({
    el: "#main_antecedentes_medicos",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,
            formCrear: {
                antecedentes_personales: [],
                antecedentes_quirurgicos: [],
            },
            formCrear_externos: {
                antecedentes_quirurgicos: {
                    descripcion: "",
                    codigo: "",
                },
                antecedentes_personales: {
                    descripcion: "",
                    codigo: "",
                },
            },
            editar: true,
        };
    },
    created: function () {},
    methods: {
        limpiarInput: function () {
            this.formCrear_externos.antecedentes_quirurgicos.descripcion = "";
            this.formCrear_externos.antecedentes_personales.descripcion = "";
            $(".erroresInput").addClass("hidden");
        },

        async guardarAntecedentesPersonalesQuirurgicos(tipo) {
            if (tipo == "PERSONALES")
                var error = buscarErroresInput(
                    "antecedentes_medicos_personales"
                );
            else
                var error = buscarErroresInput(
                    "antecedentes_medicos_quirurgicos"
                );

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
                            "/historias/guardarAntecedentesPersonalesQuirurgicos";

                        var fill =
                            app_antecedentes.formCrear_externos
                                .antecedentes_personales;
                        if (tipo != "PERSONALES")
                            fill =
                                app_antecedentes.formCrear_externos
                                    .antecedentes_quirurgicos;
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

                                    app_antecedentes.formCrear_externos.antecedentes_personales.descripcion =
                                        "";
                                    app_antecedentes.formCrear_externos.antecedentes_quirurgicos.descripcion =
                                        "";
                                    if (tipo == "PERSONALES")
                                        cargarDatatablAntecedentesPersonales();
                                    else
                                        cargarDatatablAntecedentesQuirurgicos();
                                }
                            })
                            .catch((error) => {
                                app.cargando = false;
                                swal.fire(
                                    "Cancelado!",
                                    "Error al grabar...",
                                    error
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
        async eliminarAntecedentes(id, tipo) {
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
                            "/historias/eliminarAntecedentes";
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
                                    app_antecedentes.formCrear_externos.antecedentes_personales.descripcion =
                                        "";
                                    app_antecedentes.formCrear_externos.antecedentes_quirurgicos.descripcion =
                                        "";
                                    if (tipo == "PERSONALES")
                                        cargarDatatablAntecedentesPersonales();
                                    else
                                        cargarDatatablAntecedentesQuirurgicos();
                                }
                            })
                            .catch((error) => {
                                app.cargando = false;
                                swal.fire(
                                    "Cancelado!",
                                    "Error al grabar...",
                                    error
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
