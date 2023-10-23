var app = new Vue({
    el: "#main",
    data() {
        return {
            currentTab: 1,
            editar: false,
            cargando: false,
            consultaDatos: false,
            cedula_valida: false,
            formCrear: {
                identificacion: "",
                tipo_evaluacion: "INGRESO",
            },
            guardado: false,
            consultaDatosTipoEvaluacion: [],
            secciones_habilitadas: 0,
        };
    },
    created: function () {
        this.consultaCombosRegistro();
    },
    methods: {
        async buscarSeccionesTiposEvaluaciones(tipo = null) {
            this.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/buscarSeccionesTiposEvaluaciones";
            var fill = {
                descripcion:
                    tipo != null
                        ? tipo
                        : document.querySelector("#tipo_evaluacion_id").value,
            };
            this.secciones_habilitadas = 0;
            this.campos_habilitados = 0;
            $(".tabs_seccion").addClass("hidden");
            $(".campo_evaluacion").addClass("hidden");
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargando = false;

                    if (response.data.status == 200) {
                        var datos = response.data.datos;
                        var datos_campos = response.data.campos;
                        if (datos_campos != null) {
                            let arregloDatosCampos = datos_campos.split(",");
                            arregloDatosCampos.forEach((value, index) => {
                                if ($(".campo_evaluacion").hasClass(value)) {
                                    $("." + value + "").removeClass("hidden");
                                    this.campos_habilitados =
                                        this.campos_habilitados + 1;
                                }
                            });
                        }

                        if (datos == null || datos == "") {
                            reseteoConsulta();
                            alertToast(
                                "No existen secciones en el catalogo de evaluaciones"
                            );
                            return false;
                        } else {
                            let arregloDatos = datos.split(",");
                            arregloDatos.forEach((value, index) => {
                                if ($(".tabs_seccion").hasClass(value)) {
                                    $("." + value + "").removeClass("hidden");
                                    this.secciones_habilitadas =
                                        this.secciones_habilitadas + 1;
                                }
                                if (this.secciones_habilitadas < 1) {
                                    alertToast("No hay secciones habilitadas");
                                    return false;
                                }
                            });
                        }
                    }
                })
                .catch((error) => {
                    this.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", "error");
                });
        },
        async consultaCombosRegistro() {
            this.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/consultaCombosRegistro";
            var fill = {};
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargando = false;
                    if (response.data.status == 200)
                        this.consultaDatosTipoEvaluacion =
                            response.data.tipo_evaluacion;
                })
                .catch((error) => {
                    this.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", "error");
                });
        },

        async buscarFuncionario() {
            this.consultaDatos = false;
            errore = "";
            if (app.formCrear.identificacion.trim() == "")
                errore += "No puede ingresar valores vacios";
            if (errore != "") {
                alertToastSuccess(errore);
                return false;
            }
            $(".erroresInput").addClass("hidden");
            app.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/buscarFuncionario";

            //*var urlKeeps = "";
            var fill = {
                identificacion: app.formCrear.identificacion,
            };
            document.querySelector("#datos_tab").click();
            app.consultaDatos = false;
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    console.log(response);
                    this.guardado = true;
                    app.cargando = false;
                    if (response.data.status == 200) {
                        app_paciente.limpiarDatosGeneralesPaciente();
                        this.cargarDatosPaciente(response);
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", "error");
                });
        },
        cambioDatoConsultaDatos: function () {
            if (!this.consultaDatos && !this.editar)
                $(".tabs_carga").addClass("hidden");
            else $(".tabs_carga").removeClass("hidden");
        },
        cargarDatosPaciente: function (response, editar = false) {
            this.consultaDatos = false;
            var data = response.data.datos;
            if (editar) {
                data = response.data.especificos;
                this.formCrear.tipo_evaluacion =
                    response.data.datos.tipo_evaluacion;
            } else {
                data.motivo_consulta = "";
                data.primer_nombre = "";
                data.segundo_nombre = "";
                data.primer_apellido = "";
                data.segundo_apellido = "";
            }

            var especificos = response.data.especificos;
            var atencion_medica = response.data.atencion_medica;
            var ciuo_descripcion = response.data.ciuo_descripcion;
            var ciiu_descripcion = response.data.ciiu_descripcion;
            /* this.formCrear.tipo_evaluacion =
                response.data.datos.tipo_evaluacion; */

            app_paciente.llenarDatosGeneralesPaciente(data);
            app_paciente.llenarDatosEspecificosPaciente(
                especificos,
                atencion_medica,
                ciuo_descripcion,
                ciiu_descripcion
            );
            app_paciente.formCrear.paciente.tipo_evaluacion =
                this.formCrear.tipo_evaluacion;

            this.buscarSeccionesTiposEvaluaciones(
                this.formCrear.tipo_evaluacion
            );

            if (app_paciente.formCrear.paciente.nombres == "") {
                this.consultaDatos = false;
                toastr["error"](
                    "No se encontraron datos del funcionario, consulte con talento humano"
                );
            } else this.consultaDatos = true;
            this.cambioDatoConsultaDatos();
        },
    },
});
