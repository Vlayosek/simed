var app = new Vue({
    el: "#main",
    data() {
        return {
            currentTab: 1,
            secciones: [],
            campos: [],
            formCrear: {
                paciente: arregloDatapaciente,
                secciones: {
                    id: 0,
                    descripcion: "",
                    seccion: null,
                    campos: null,
                },
            },
            cargando: false,
            editar: false,
            consultaDatosLateralidad: [],
            consultaDatosGenero: [],
            consultaDatosIdentidadGenero: [],
            consultaDatosOrientacionSexual: [],
            consultaDatosTipoSangre: [],
            consultaDatosReligion: [],
        };
    },
    created: function () {},
    methods: {
        limpiarData: function () {
            this.formCrear.secciones.id = 0;
            this.formCrear.secciones.descripcion = "";
            this.formCrear.secciones.seccion = null;
            $("#seccion").val(null).change();
            $("#campos").val(null).change();
        },
        async editarRegistro(id) {
            let url = "editarRegistro";
            let fill = {
                id: id,
            };

            await axios
                .post(url, fill)
                .then((response) => {
                    app.cargando = false;
                    if (response.data.status == 200) {
                        this.formCrear.secciones = response.data.datos;
                        $("#seccion")
                            .val(
                                this.formCrear.secciones.seccion != null
                                    ? this.formCrear.secciones.seccion.split(
                                          ","
                                      )
                                    : null
                            )
                            .change();
                        $("#campos")
                            .val(
                                this.formCrear.secciones.campos != null
                                    ? this.formCrear.secciones.campos.split(",")
                                    : null
                            )
                            .change();
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", "error");
                });
        },
        async eliminarRegistro(id) {
            let url = "eliminarRegistro";
            let fill = {
                id: id,
            };
            await axios
                .post(url, fill)
                .then((response) => {
                    app.cargando = false;
                    if (response.data.status == 200) {
                        alertToastSuccess("Eliminado Exitosamente");
                        cargarDatatable();
                    } else {
                        swal.fire("Cancelado!", "Error al grabar...", "error");
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", "error");
                });
        },
        async guardarSeccion() {
            let url = "guardarSeccion";
            this.formCrear.secciones.seccion = $("#seccion").val().join();
            if ($("#seccion").val() == "" || $("#seccion").val() == null) {
                alertToast("Debe ingresar por lo menos una sección");
                return false;
            }
            this.formCrear.secciones.campos = $("#campos").val().join();
            if ($("#campos").val() == "" || $("#campos").val() == null) {
                alertToast("Debe ingresar por lo menos una sección");
                return false;
            }
            await axios
                .post(url, this.formCrear.secciones)
                .then((response) => {
                    app.cargando = false;
                    if (response.data.status == 200) {
                        alertToastSuccess(response.data.datos);
                        cargarDatatable();
                        document.querySelector("#cerrar_modal_seccion").click();
                    } else {
                        alertToast(response.data.datos);
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", "error");
                });
        },
    },
});
