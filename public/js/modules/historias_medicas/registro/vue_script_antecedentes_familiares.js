var app_antecedentes_familiares = new Vue({
    el: "#main_antecedentes_familiares",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,
            cargando:false,
            formCrear_externos: {
                antecedentes_familiares: {
                    id: 0,
                    descripcion: "",
                    detalle: null,
                    codigo:""
                },
            },
            consultaDatosAntecedentesFamiliares: [],
            editar: true,

        };
    },
    created: function () {
       // this.limpiarAntecedenteFamiliar();

    },
    methods: {
        limpiarAntecedenteFamiliar: function () {
            this.formCrear_externos.antecedentes_familiares.id = 0;
            this.formCrear_externos.antecedentes_familiares.descripcion = "";
            this.formCrear_externos.antecedentes_familiares.detalle = null;
            $("#detalle_antecedentes_familiares").val(null).change();
        },

        async editarAntecedenteFamiliar(id) {
            app_antecedentes_familiares.cargando = true;
            app.cargando = true;
           var urlKeeps = document.querySelector("#inicializacion").value +"/historias/editarAntecedenteFamiliar";
            var fill = {'id':id};
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false;
                    app_antecedentes_familiares.cargando = false;
                    if (response.data.status == 200)   {
                        var data=response.data.datos;
                      this.formCrear_externos.antecedentes_familiares.id = data.id;
                        this.formCrear_externos.antecedentes_familiares.descripcion = data.descripcion;
                        this.formCrear_externos.antecedentes_familiares.detalle = data.detalle.split(",");
                        $("#detalle_antecedentes_familiares").val(this.formCrear_externos.antecedentes_familiares.detalle).change();
                    }

                })
                .catch((error) => {
                    app.cargando = false;
                    app_antecedentes_familiares.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async consultaCombosRegistroAntecedenteFamiliar() {
            app.cargando = true;
           var urlKeeps = document.querySelector("#inicializacion").value +"/historias/consultaCombosRegistroAntecedenteFamiliar";
            var fill = {};
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false;
                    if (response.data.status == 200)   this.consultaDatosAntecedentesFamiliares =   response.data.datos;

                })
                .catch((error) => {
                    app.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },

        async eliminarAntecedenteFamiliar(id) {
            var fill = {
                id: id,
            };
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
                       var urlKeeps = document.querySelector("#inicializacion").value +"/historias/eliminarAntecedenteFamiliar";
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                if (response.data.status != "200")
                                    alertToast(response.data.datos, 3500);
                                else {
                                    cargarDatatableAntecedentesFamiliar();
                                    alertToastSuccess(
                                        response.data.datos,
                                        3500
                                    );
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
                        swal.fire(
                            "Cancelado!",
                            "No se registraron cambios...",
                            "error"
                        );
                        return false;
                    }
                });
        },

        async guardarAntecedenteFamiliar() {
            var error = buscarErroresInput("antecedentes_familiares");
            if (error) return false;

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
                       var urlKeeps = document.querySelector("#inicializacion").value +"/historias/guardarAntecedenteFamiliar";
                        app_antecedentes_familiares.formCrear_externos.antecedentes_familiares.identificacion =    app.formCrear.identificacion;
                        app_antecedentes_familiares.formCrear_externos.antecedentes_familiares.detalle =    $("#detalle_antecedentes_familiares").val();
                        if (this.formCrear_externos.antecedentes_familiares.codigo=="")
                            this.formCrear_externos.antecedentes_familiares.codigo = app_datos.formCrear.codigo;
                        axios
                            .post(
                                urlKeeps,
                                app_antecedentes_familiares.formCrear_externos.antecedentes_familiares
                            )
                            .then((response) => {
                                if (response.data.status != "200")
                                    alertToast(response.data.datos, 3500);
                                else {
                                    alertToastSuccess(
                                        response.data.datos,
                                        3500
                                    );
                                    app_antecedentes_familiares.limpiarAntecedenteFamiliar();
                                    cargarDatatableAntecedentesFamiliar();
                                    document
                                        .querySelector(
                                            "#cerrar_modal_antecedentes_familiares"
                                        )
                                        .click();
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
