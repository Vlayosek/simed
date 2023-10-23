var app_paciente = new Vue({
    el: "#main_paciente",
    data() {
        return {
            currentTab: 1,
            consultaDatos: false,
            formCrear: {
                paciente: arregloDatapaciente,
            },
            consultaDatosGenero: [],
            consultaDatosTipoSangre: [],
            consultaDatosReligion: [],
            consultaDatosLateralidad: [],
            consultaDatosOrientacionSexual: [],
            consultaDatosIdentidadGenero: [],
            cargando: false,
            editar: true,
        };
    },
    created: function () {},
    methods: {
        limpiarDatosGeneralesPaciente: function () {
            this.formCrear.paciente.fecha_ingreso = "";
            this.formCrear.paciente.fecha_salida = "";
            this.formCrear.paciente.fecha_reingreso = "";
            this.formCrear.paciente.tiempo_meses = "";
            this.formCrear.paciente.total_dias = "";
            this.formCrear.paciente.edad = "";
            $("#codigo_ciuo").html("");
            $("#codigo_ciiu").html("");
            this.formCrear.paciente.religion = "";
            this.formCrear.paciente.nombres = "";
            this.formCrear.paciente.apellidos = "";
            this.formCrear.paciente.cargo = "";
            this.formCrear.paciente.primer_nombre = "";
            this.formCrear.paciente.segundo_nombre = "";
            this.formCrear.paciente.primer_apellido = "";
            this.formCrear.paciente.segundo_apellido = "";
            this.formCrear.paciente.tipo_sangre = "";
            this.formCrear.paciente.orientacion_sexual = "";
            this.formCrear.paciente.genero = "";
            this.formCrear.paciente.area = "";
            this.formCrear.paciente.lateralidad = "";
            this.formCrear.paciente.identidad_genero = "";
            this.formCrear.paciente.actividad_relevante = "";
            this.formCrear.paciente.causa_salida = "";
            this.formCrear.paciente.factores_riesgo = "";
            this.formCrear.paciente.motivo_consulta = "";
            this.formCrear.paciente.tipo_evaluacion =
                app_datos.formCrear.tipo_evaluacion;
            app_datos.formCrear.historia_clinica = "";
            app_datos.formCrear.numero_archivo = "";
        },
        llenarDatosGeneralesPaciente: function (data) {
            this.formCrear.paciente.nombres = data.nombres;
            this.formCrear.paciente.apellidos = data.apellidos;
            this.formCrear.paciente.area = data.area;
            this.formCrear.paciente.cargo = data.cargo;
            this.formCrear.paciente.fecha_ingreso = data.fecha_ingreso;
            this.formCrear.paciente.fecha_salida = data.fecha_salida;
            this.formCrear.paciente.tipo_sangre = data.tipo_sangre;
            this.formCrear.paciente.genero = data.genero;
            this.formCrear.paciente.tiempo_meses = data.tiempo_meses;
            this.formCrear.paciente.edad = calcular_edad_perfil(
                data.fecha_nacimiento
            );
            this.formCrear.paciente.tipo_evaluacion =
                app_datos.formCrear.tipo_evaluacion;
            app_datos.formCrear.historia_clinica = data.identificacion_;
            app_datos.formCrear.numero_archivo = data.identificacion_;
            this.formCrear.paciente.motivo_consulta = data.motivo_consulta;
            this.formCrear.paciente.primer_nombre = data.primer_nombre;
            this.formCrear.paciente.segundo_nombre = data.segundo_nombre;
            this.formCrear.paciente.primer_apellido = data.primer_apellido;
            this.formCrear.paciente.segundo_apellido = data.segundo_apellido;
        },
        llenarDatosEspecificosPaciente: function (
            data,
            atencion_medica,
            ciuo_descripcion,
            ciiu_descripcion
        ) {
            if (data != null) {
                this.formCrear.paciente.edad = data.edad;
                this.formCrear.paciente.primer_nombre =
                    data.primer_nombre == null ? "" : data.primer_nombre;
                this.formCrear.paciente.segundo_nombre =
                    data.segundo_nombre == null ? "" : data.segundo_nombre;
                this.formCrear.paciente.primer_apellido =
                    data.primer_apellido == null ? "" : data.primer_apellido;
                this.formCrear.paciente.segundo_apellido =
                    data.segundo_apellido == null ? "" : data.segundo_apellido;
                this.formCrear.paciente.religion = data.religion;
                this.formCrear.paciente.lateralidad = data.lateralidad;
                this.formCrear.paciente.orientacion_sexual =
                    data.orientacion_sexual;
                this.formCrear.paciente.identidad_genero =
                    data.identidad_genero;
                this.formCrear.paciente.actividad_relevante =
                    data.actividad_relevante;
                this.formCrear.paciente.puesto_trabajo_id =
                    data.puesto_trabajo_ciuo;
                this.formCrear.paciente.puesto_trabajo = ciuo_descripcion;
                app_datos.formCrear.ciiu = atencion_medica.codigo_ciiu;
                app_datos.formCrear.ciiu_descripcion = ciiu_descripcion;
                this.formCrear.paciente.tipo_evaluacion =
                    atencion_medica.tipo_evaluacion;
                app_datos.formCrear.historia_clinica =
                    atencion_medica.historia_clinica;
                app_datos.formCrear.numero_archivo =
                    atencion_medica.numero_archivo;
                $("#codigo_ciuo").html("");
                $("#codigo_ciiu").html("");
                this.llenarSelect(
                    "codigo_ciuo",
                    data.puesto_trabajo_ciuo,
                    ciuo_descripcion
                );
                this.llenarSelect(
                    "codigo_ciiu",
                    atencion_medica.codigo_ciiu,
                    ciiu_descripcion
                );
            }
            /* else {
                this.formCrear.paciente.religion = "";
                this.formCrear.paciente.lateralidad = "";
                this.formCrear.paciente.orientacion_sexual = "";
                this.formCrear.paciente.identidad_genero = "";
                this.formCrear.paciente.actividad_relevante = "";
                this.formCrear.historia_clinica = "";
                this.formCrear.numero_archivo = "";
            } */
        },
        llenarSelect: function (id, key, value) {
            $("#" + id + "").append(
                '<option value="' + key + '">' + value + "</option>"
            );
        },

        validarCampos: function () {
            let errores = "";

            if (this.formCrear.paciente.nombres == "")
                errores += "Debe ingresar nombres \n";
            if (this.formCrear.paciente.apellidos == "")
                errores += "Debe ingresar apellidos \n";
            if (this.formCrear.paciente.fecha_ingreso == "")
                errores += "Debe ingresar fecha de ingreso \n";
            if (this.formCrear.paciente.edad == "")
                errores += "Debe ingresar edad \n";
            if (this.formCrear.paciente.puesto_trabajo == "")
                errores += "Debe seleccionar un puesto de trabajo \n";
            // if (this.formCrear.paciente.religion == "")
            //     errores += "Debe seleccionar una religión \n";
            if (this.formCrear.paciente.cargo == "")
                errores += "Debe ingresar el cargo \n";
            if (this.formCrear.paciente.tipo_sangre == "")
                errores += "Debe seleccionar un tipo de sangre \n";
            // if (this.formCrear.paciente.orientacion_sexual == "")
            //     errores += "Debe seleccionar una orientacion sexual \n";
            if (this.formCrear.paciente.genero == "")
                errores += "Debe seleccionar un género \n";
            if (this.formCrear.paciente.area == "")
                errores += "Debe ingresar area \n";
            // if (this.formCrear.paciente.lateralidad == "")
            //     errores += "Debe seleccionar una lateralidad \n";
            // if (this.formCrear.paciente.identidad_genero == "")
            //     errores += "Debe seleccionar una identidad de genero \n";

            if (
                app.formCrear.tipo_evaluacion == "INGRESO" ||
                app.formCrear.tipo_evaluacion == "RETIRO"
            ) {
                if (this.formCrear.paciente.actividad_relevante == "")
                    errores += "Debe ingresar la actividad \n";
            }

            if (this.formCrear.paciente.motivo_consulta == "")
                errores += "Debe ingresar el motivo de la consulta \n";
            if (this.formCrear.paciente.primer_nombre == "")
                errores += "Debe ingresar el primer nombre \n";
            if (this.formCrear.paciente.segundo_nombre == "")
                errores += "Debe ingresar el segundo nombre \n";
            if (this.formCrear.paciente.primer_apellido == "")
                errores += "Debe ingresar el primer apellido \n";
            if (this.formCrear.paciente.segundo_apellido == "")
                errores += "Debe ingresar el segundo apellido \n";
            /* if (this.formCrear.paciente.causa_salida == "")
                errores += "Debe ingresar la causa de la salida \n"; */

            return errores;
        },

        async guardarPaciente() {
            var error = this.validarCampos();
            if (error.length > 0) {
                swal.fire(error);
                return false;
            }

            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/guardarPaciente";

            let fill = this.llenarDataPaciente();

            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    if (response.data.status == 200)
                        alertToastSuccess(response.data.datos);
                    else alertToast(response.data.datos);
                })
                .catch((error) => {
                    swal.fire("Cancelado!", "Error al grabar...", "error");
                });
        },
        llenarDataPaciente: function () {
            this.formCrear.paciente.codigo = app_datos.formCrear.codigo;
            this.formCrear.paciente.identificacion =
                app.formCrear.identificacion;
            return this.formCrear.paciente;
        },
        async consultaCombosRegistro() {
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/historias/consultaCombosRegistro";

            var fill = {};
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    if (response.data.status == 200) {
                        this.consultaDatosLateralidad =
                            response.data.lateralidad;
                        this.consultaDatosGenero = response.data.genero;
                        this.consultaDatosIdentidadGenero =
                            response.data.identidad_genero;
                        this.consultaDatosOrientacionSexual =
                            response.data.orientacion_sexual;
                        this.consultaDatosReligion = response.data.religion;
                        this.consultaDatosTipoSangre =
                            response.data.tipo_sangre;
                        this.consultaDatosTipoEvaluacion =
                            response.data.tipo_evaluacion;
                        this.consultaDatosDiscapacidades =
                            response.data.discapacidades;
                    }
                })
                .catch((error) => {
                    swal.fire("Cancelado!", "Error al grabar...", "error");
                });
        },
    },
});
