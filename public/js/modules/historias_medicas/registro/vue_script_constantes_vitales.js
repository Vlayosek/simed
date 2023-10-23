var app_constantes = new Vue({
    el: "#main_constantes_vitales",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,
            cargando: false,
            formConstante: {
                id: 0,
                identificacion: "",
                codigo: "",
                presion_arterial: "",
                temperatura: "",
                frecuencia_cardiaca: "",
                saturacion_oxigeno: "",
                frecuencia_respiratoria: "",
                peso: "",
                talla: "",
                indice_masa_corporal: "",
                perimetro_abdominal: "",
            },
            editar: true,
        };
    },
    created: function () {},
    methods: {
        limpiarConstanteVital: function () {
            this.formConstante.id = 0;
            this.formConstante.identificacion = "";
            this.formConstante.codigo = "";
            this.formConstante.presion_arterial = "";
            this.formConstante.temperatura = "";
            this.formConstante.frecuencia_cardiaca = "";
            this.formConstante.saturacion_oxigeno = "";
            this.formConstante.frecuencia_respiratoria = "";
            this.formConstante.peso = "";
            this.formConstante.talla = "";
            this.formConstante.indice_masa_corporal = "";
            this.formConstante.perimetro_abdominal = "";
            $(".erroresInput").addClass("hidden");
        },
        async guardar() {
            app_constantes.cargando = true;
            var error = buscarErroresInput("constantes_");
            if (error) {
                app_constantes.cargando = false;
                return false;
            }
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/constantes_vitales/guardar";
            this.formConstante.identificacion = app.formCrear.identificacion;
            if (this.formConstante.codigo == "")
                this.formConstante.codigo = app_datos.formCrear.codigo;
            await axios
                .post(urlKeeps, this.formConstante)
                .then((response) => {
                    app_constantes.cargando = false;
                    if (response.data.status == 200) {
                        alertToastSuccess(response.data.message);
                        cargarDatatableConstantesVitales();
                        if (this.formConstante.id != 0)
                            $("#cerrar_modal_constantes").trigger("click");
                        this.limpiarConstanteVital();
                    } else {
                        alertToast(response.data.message);
                    }
                })
                .catch((error) => {
                    app_constantes.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async editarRegistro(id) {
            app_constantes.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/constantes_vitales/editar";
            var fill = {
                id: id,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app_constantes.cargando = false;
                    if (response.data.status == 200) {
                        this.limpiarConstanteVital();
                        this.formConstante = response.data.datos;
                    }
                })
                .catch((error) => {
                    app_constantes.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        async eliminar(id) {
            app_constantes.cargando = true;
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
                            "/historias/constantes_vitales/eliminar";
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                app_constantes.cargando = false;
                                if (response.data.status != "200")
                                    toastr["error"](response.data.message);
                                else {
                                    cargarDatatableConstantesVitales();
                                    toastr["success"](response.data.message);
                                }
                            })
                            .catch((error) => {
                                app_constantes.cargando = false;
                                alertToast("Cancelado!", 3500);
                                //swal.fire("Cancelado!", "Error al grabar...", "error");
                            });
                    } else if (result.isDenied) {
                        app_constantes.cargando = false;
                        alertToast("Cancelado!", 3500);
                        //swal.fire("Cancelado!", "Error al grabar...", "error");
                        return false;
                    }
                });
        },
    },
});
