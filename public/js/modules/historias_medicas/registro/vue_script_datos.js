var app_datos = new Vue({
    el: "#main_datos",
    data() {
        return {
            currentTab: 1,
            cargando: false,
            consultaDatos: false,
            formCrear: {
                id: 0,
                codigo: "",
                historia_clinica: "",
                numero_archivo: "",
                institucion: "",
                ruc: "",
                ciiu: "",
                ciiu_descripcion: "",
                identificacion: "",
                // motivo_consulta: "",
                tipo_evaluacion: "",
            },
            editar: true,
            consultaDatosTipoEvaluacion: [],
        };
    },
    created: function () {
        this.limpiarData();
        this.consultaCombosRegistro();
    },
    methods: {
        validarCampos: function () {
            let errores = "";

            if (this.formCrear.ciiu_descripcion == "")
                errores += "Debe seleccionar CIIU \n";
            if (this.formCrear.establecimiento_salud == "")
                errores += "Debe ingresar el establecimiento de salud \n";
            if (this.formCrear.fecha_ingreso == "")
                errores += "Debe ingresar fecha de ingreso \n";
            if (this.formCrear.codigo == "")
                errores += "Debe ingresar el codigo del registro \n";
            if (this.formCrear.historia_clinica == "")
                errores += "Debe ingresar la historia clinica \n";
            if (this.formCrear.numero_archivo == "")
                errores += "Debe ingresar el numero de archivo \n";
            /* if (this.formCrear.motivo_consulta == "")
                errores += "Debe ingresar el motivo de la consulta \n"; */

            return errores;
        },

        async guardarAtencionMedica() {
            var error = this.validarCampos();
            if (error.length > 0) {
                swal.fire(error);
                return false;
            }

            this.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/guardarAtencionMedica";
            this.formCrear.identificacion = app.formCrear.identificacion;
            this.formCrear.tipo_evaluacion = app.formCrear.tipo_evaluacion;
            var datos = this.formCrear;
            let paciente = app_paciente.llenarDataPaciente();
            //var paciente = app_paciente.formCrear;
            var fill = {
                datos: datos,
                paciente: paciente,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargando = false;
                    if (response.data.status == 200) {
                        this.formCrear.id = response.data.id;
                        alertToastSuccess(response.data.datos);
                    } else alertToast(response.data.datos);
                })
                .catch((error) => {
                    this.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", error);
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
                    swal.fire("Cancelado!", "Error al grabar...", error);
                });
        },
        limpiarData: function () {
            this.formCrear.id = document.querySelector("#id").value;

            this.formCrear.codigo =
                document.querySelector("#codigo_siguiente").value;
            this.formCrear.institucion =
                document.querySelector("#institucion").value;
            this.formCrear.ruc = document.querySelector("#ruc").value;
            this.formCrear.establecimiento_salud = document.querySelector(
                "#establecimiento_salud"
            ).value;
            this.formCrear.tipo_evaluacion = "INGRESO";
            this.formCrear.identificacion = "";
            this.formCrear.ciiu = "";
            this.formCrear.historia_clinica = "";
            this.formCrear.numero_archivo = "";
            app.guardado = false;
            if (this.formCrear.id != 0) this.editarRegistro();
        },

        cambioDatoConsultaDatos: function () {
            if (!this.consultaDatos) $(".tabs_carga").addClass("hidden");
            else $(".tabs_carga").removeClass("hidden");
        },
        async editarRegistro() {
            app_paciente.consultaCombosRegistro();

            this.editar = false;
            app.editar = true;

            this.cargando = true;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/editarRegistro";
            var fill = { id: this.formCrear.id };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargando = false;
                    if (response.data.status == 200) {
                        this.formCrear.identificacion =
                            response.data.identificacion;
                        app.formCrear.identificacion =
                            this.formCrear.identificacion;
                        this.cargarDatosGeneral(response.data.datos);
                        app.cargarDatosPaciente(response, true);
                        this.habilitarEdicionDatos(response.data.editar);
                    }
                })
                .catch((error) => {
                    this.cargando = false;
                    swal.fire("Cancelado!", "Error al grabar...", "error");
                });
        },
        cargarDatosGeneral: function (data) {
            this.formCrear = data;
            document.querySelector("#codigo_general").innerHTML =
                "CÓDIGO: " + this.formCrear.codigo;
            $("#codigo_general").removeClass("hidden");
        },
        habilitarEdicionDatos: function (valor_editar) {
            this.editar = valor_editar;
            app_actividades_extras_enfermedades_actual.editar = this.editar;
            app_antecedentes_familiares.editar = this.editar;
            app_antecedentes_gineco.editar = this.editar;
            app_antecedentes_reproductivos_masculinos.editar = this.editar;
            app_antecedentes.editar = this.editar;
            app_aptitudes_medicas.editar = this.editar;
            app_constantes.editar = this.editar;
            app_diagnostico.editar = this.editar;
            app_discapacidad.editar = this.editar;
            app_estilo_vida.editar = this.editar;
            app_examen_fisico_regional.editar = this.editar;
            app_examen_general_especifico.editar = this.editar;
            app_examenes_gineco.editar = this.editar;
            app_examenes_reproductivos_masculinos.editar = this.editar;
            app_factores_riesgosos.editar = this.editar;
            app_habitos.editar = this.editar;
            app_motivo_reintegro.editar = this.editar;
            app_paciente.editar = this.editar;
            app_recomendaciones.editar = this.editar;
            app_revision_organos.editar = this.editar;
        },
    },
});
