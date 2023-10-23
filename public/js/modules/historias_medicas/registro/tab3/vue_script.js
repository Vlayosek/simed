var app = new Vue({
    el: "#main",
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
                identificacion: "",
                motivo_consulta: "",
                antecedentes_personales: [],
                antecedentes_quirurgicos: [],
                paciente: {
                    area: "",
                    cargo: "",
                    fecha_ingreso: "",
                    apellidos: "",
                    nombres: "",
                    genero: "",
                    edad: "",
                    religion: "",
                    tipo_sangre: "",
                    lateralidad: "",
                    orientacion_sexual: "",
                    identidad_genero: "",
                },

                tipo_evaluacion: "INGRESO",
            },
            formCrear_externos: {
                antecedentes_quirurgicos: {
                    descripcion: "",
                },
                antecedentes_personales: {
                    descripcion: "",
                },
                discapacidad: {
                    numero_carnet: "",
                    porcentaje: "",
                    nombre: "",
                },
            },

            links: [
                "informacion_general_tab",
                "paciente_tab",
                "discapacidad_tab",
                "consulta_medica_tab",
                "antecedentes_medicos_tab",
            ],
            ubicacion_link: 0,
            consultaDatosTipoEvaluacion: [],
            consultaDatosGenero: [],
            consultaDatosTipoSangre: [],
            consultaDatosReligion: [],
            consultaDatosLateralidad: [],
            consultaDatosOrientacionSexual: [],
            consultaDatosIdentidadGenero: [],
            consultaDatosDiscapacidades: [],
        };
    },
    created: function () {
        this.limpiarData();
        this.consultaCombosRegistro();
    },
    methods: {
        ubicacionTab: function (id) {
            var key = this.links.indexOf(id);
            this.ubicacion_link = key;
        },
        tabAdelante: function () {
            this.ubicacion_link = this.ubicacion_link + 1;
            let tamano = this.links.length;
            var id = "informacion_general_tab";
            if (tamano - 1 < this.ubicacion_link) this.ubicacion_link = 0;
            else id = this.links[this.ubicacion_link];
            document.querySelector("#" + id + "").click();
        },
        tabAtras: function () {
            this.ubicacion_link = this.ubicacion_link - 1;
            var id = "informacion_general_tab";
            if (this.ubicacion_link == -1) this.ubicacion_link = 0;
            else id = this.links[this.ubicacion_link];
            document.querySelector("#" + id + "").click();
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
            this.formCrear.establecimiento_salud = document.querySelector(
                "#establecimiento_salud"
            ).value;
            this.formCrear.tipo_evaluacion = "INGRESO";
            this.formCrear.identificacion = "";
            this.limpiarDiscapacidad();
            if (this.formCrear.id != 0) this.editarRegistro();
        },
        limpiarDiscapacidad: function () {
            this.formCrear_externos.discapacidad.nombre = "";
            this.formCrear_externos.discapacidad.id = "0";
            this.formCrear_externos.discapacidad.porcentaje = "";
            this.formCrear_externos.discapacidad.numero_carnet = "";
        },
        llenarPaciente: function (data) {
            this.formCrear.paciente.nombres = data.nombres;
            this.formCrear.paciente.apellidos = data.apellidos;
            this.formCrear.paciente.area = data.area;
            this.formCrear.paciente.cargo = data.cargo;
            this.formCrear.paciente.fecha_ingreso = data.fecha_ingreso;
            this.formCrear.paciente.tipo_sangre = data.tipo_sangre;
            this.formCrear.paciente.genero = data.genero;
            this.formCrear.paciente.edad = calcular_edad_perfil(
                data.fecha_nacimiento
            );
            cargarDatatablePersona();
        },
        async buscarFuncionario() {
            $(".erroresInput").addClass("hidden");
            this.cargando = true;
            var urlKeeps = "buscarFuncionario";
            var fill = {
                identificacion: this.formCrear.identificacion,
            };
            this.consultaDatos = false;
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargando = false;
                    if (response.data.status == 200) {
                        var data = response.data.datos;
                        this.llenarPaciente(data);
                        if (this.formCrear.paciente.nombres == "") {
                            this.consultaDatos = false;
                            alertToast(
                                "No se encontraron datos del funcionario, consulte con talento humano",
                                3500
                            );
                        } else this.consultaDatos = true;
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    swal("Cancelado!", "Error al grabar...", "error");
                });
        },
        async editarRegistro() {
            this.cargando = true;
            var urlKeeps = "editarRegistro";
            var fill = {
                id: this.formCrear.id,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargando = false;
                    if (response.data.status == 200) {
                        this.consultaDatos = true;
                        this.formCrear = response.data.datos;
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    swal("Cancelado!", "Error al grabar...", "error");
                });
        },
        async consultaCombosRegistro() {
            this.cargando = true;
            var urlKeeps = "consultaCombosRegistro";
            var fill = {};
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargando = false;
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
                    app.cargando = false;
                    swal("Cancelado!", "Error al grabar...", "error");
                });
        },

        async guardarRegistro() {
            var error = buscarErroresInput();
            if (!error) {
                return false;
            }
            let result = await swal(
                {
                    title: "Estás seguro de realizar esta acción",
                    text: "Al confirmar se grabaran los datos exitosamente",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Si!",
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: false,
                },
                function (isConfirm) {
                    if (isConfirm) {
                        var urlKeeps = "guardarRegistro";

                        axios
                            .post(urlKeeps, app.formCrear)
                            .then((response) => {
                                if (response.data.status != "200")
                                    alertToast(response.data.datos, 3500);
                                else {
                                    alertToastSuccess(
                                        response.data.datos,
                                        3500
                                    );
                                    $("#ir_consulta").click();
                                }
                            })
                            .catch((error) => {
                                app.cargando = false;
                                swal(
                                    "Cancelado!",
                                    "Error al grabar...",
                                    "error"
                                );
                            });
                    } else {
                        swal(
                            "Cancelado!",
                            "No se registraron cambios...",
                            "error"
                        );
                        return false;
                    }
                }
            );
        },
        async guardarDiscapacidad() {
            var error = buscarErroresInput("discapacidad");
            if (error) {
                return false;
            }
            let result = await swal(
                {
                    title: "Estás seguro de realizar esta acción",
                    text: "Al confirmar se grabaran los datos exitosamente",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Si!",
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: false,
                },
                function (isConfirm) {
                    if (isConfirm) {
                        var urlKeeps = "guardarDiscapacidad";
                        app.formCrear_externos.discapacidad.identificacion =
                            app.formCrear.identificacion;
                        axios
                            .post(urlKeeps, app.formCrear_externos.discapacidad)
                            .then((response) => {
                                if (response.data.status != "200")
                                    alertToast(response.data.datos, 3500);
                                else {
                                    alertToastSuccess(
                                        response.data.datos,
                                        3500
                                    );
                                    app.limpiarDiscapacidad();
                                }
                            })
                            .catch((error) => {
                                app.cargando = false;
                                swal(
                                    "Cancelado!",
                                    "Error al grabar...",
                                    "error"
                                );
                            });
                    } else {
                        swal(
                            "Cancelado!",
                            "No se registraron cambios...",
                            "error"
                        );
                        return false;
                    }
                }
            );
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
            let result = await swal(
                {
                    title: "Estás seguro de realizar esta acción",
                    text: "Al confirmar se grabaran los datos exitosamente",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Si!",
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: false,
                },
                function (isConfirm) {
                    if (isConfirm) {
                        var urlKeeps =
                            "guardarAntecedentesPersonalesQuirurgicos";
                        var fill =
                            app.formCrear_externos.antecedentes_personales;
                        if (tipo != "PERSONALES")
                            fill =
                                app.formCrear_externos.antecedentes_quirurgicos;
                        fill.tipo = tipo;
                        fill.identificacion = app.formCrear.identificacion;
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                if (response.data.status != "200")
                                    alertToast(response.data.datos, 3500);
                                else {
                                    alertToastSuccess(
                                        response.data.datos,
                                        3500
                                    );
                                    app.formCrear_externos.antecedentes_personales.descripcion =
                                        "";
                                    app.formCrear_externos.antecedentes_quirurgicos.descripcion =
                                        "";
                                    if (tipo == "PERSONALES")
                                        cargarDatatablAntecedentesPersonales();
                                    else
                                        cargarDatatablAntecedentesQuirurgicos();
                                }
                            })
                            .catch((error) => {
                                app.cargando = false;
                                swal(
                                    "Cancelado!",
                                    "Error al grabar...",
                                    "error"
                                );
                            });
                    } else {
                        swal(
                            "Cancelado!",
                            "No se registraron cambios...",
                            "error"
                        );
                        return false;
                    }
                }
            );
        },
        async eliminarAntecedentes(id, tipo) {
            let result = await swal(
                {
                    title: "Estás seguro de realizar esta acción",
                    text: "Al confirmar se eliminará los datos exitosamente",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Si!",
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: false,
                },
                function (isConfirm) {
                    if (isConfirm) {
                        var urlKeeps = "eliminarAntecedentes";
                        var fill = {
                            id: id,
                            tipo: tipo,
                        };
                        axios
                            .post(urlKeeps, fill)
                            .then((response) => {
                                if (response.data.status != "200")
                                    alertToast(response.data.datos, 3500);
                                else {
                                    alertToastSuccess(
                                        response.data.datos,
                                        3500
                                    );
                                    app.formCrear_externos.antecedentes_personales.descripcion =
                                        "";
                                    app.formCrear_externos.antecedentes_quirurgicos.descripcion =
                                        "";
                                    if (tipo == "PERSONALES")
                                        cargarDatatablAntecedentesPersonales();
                                    else
                                        cargarDatatablAntecedentesQuirurgicos();
                                }
                            })
                            .catch((error) => {
                                app.cargando = false;
                                swal(
                                    "Cancelado!",
                                    "Error al grabar...",
                                    "error"
                                );
                            });
                    } else {
                        swal(
                            "Cancelado!",
                            "No se registraron cambios...",
                            "error"
                        );
                        return false;
                    }
                }
            );
        },
    },
});
