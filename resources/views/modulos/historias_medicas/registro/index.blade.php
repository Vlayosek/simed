@extends('layouts.app_')

@section('descripcion_detalle')
    HISTORIAL MÉDICO / NUEVO REGISTRO
@endsection
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ url('adminlte3/plugins/wizard/css/montserrat-font.css') }}">
    <link rel="stylesheet" href="{{ url('adminlte3/plugins/wizard/css/style.css') }}" />
    <meta name="robots" content="noindex, follow">

    <style>
        label {
            font-size: 12px !important;
        }

        .nav-item {
            font-size: 12px;
        }

        .menu-tab {
            background: #3a4e7b;
            padding: 0px;
        }

        .nav-pills .nav-link.active,
        .nav-pills .show>.nav-link {
            color: #000;
            background-color: #fff;
        }

        .nav-pills .nav-link {
            border-radius: 0px;
        }

        .nav-pills .nav-link {
            color: #ffffff;
        }

        .green {
            color: green;
        }

        .red {
            color: red;
        }

        li {
            list-style: none;
        }

        /*
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    Full screen Modal
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    */
        .fullscreen-modal .modal-dialog {
            margin: 0;
            margin-right: auto;
            margin-left: auto;
            width: 100%;
        }

        @media (min-width: 768px) {
            .fullscreen-modal .modal-dialog {
                width: 750px;
            }
        }

        @media (min-width: 992px) {
            .fullscreen-modal .modal-dialog {
                width: 970px;
            }
        }

        @media (min-width: 1200px) {
            .fullscreen-modal .modal-dialog {
                width: 1170px;
            }
        }
    </style>
    <style>
        /* The container */
        .container {
            display: block;
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 22px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Hide the browser's default checkbox */
        .container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* Create a custom checkbox */
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #eee;
        }

        /* On mouse-over, add a grey background color */
        .container:hover input~.checkmark {
            background-color: #ccc;
        }

        /* When the checkbox is checked, add a blue background */
        .container input:checked~.checkmark {
            background-color: #2196F3;
        }

        /* Create the checkmark/indicator (hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .container input:checked~.checkmark:after {
            display: block;
        }

        /* Style the checkmark/indicator */
        .container .checkmark:after {
            left: 9px;
            top: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }

        .form-control:disabled,
        .form-control[readonly] {
            background-color: #f7f7f7 !important;
            opacity: 1;
        }
    </style>
    {{-- <style>
        input[type=file]::file-selector-button {
            margin-right: 20px;
            border: none;
            background: #084cdf;
            padding: 10px 20px;
            border-radius: 10px;
            color: #fff;
            cursor: pointer;
            transition: background .2s ease-in-out;
        }

        input[type=file]::file-selector-button:hover {
            background: #0d45a5;
        }
    </style> --}}
@endsection
@section('javascript')
    <!-- Bootstrap Switch -->
    <script src="{{ url('adminlte3/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ url('adminlte3/plugins/datepicker/bootstrap-datepicker.js') }}/"></script>

    <script src="{{ url('js/modules/historias_medicas/registro/global.js?v=110') }}"></script>
    <script src="{{ url('js/modules/historias_medicas/registro/script.js?v=112') }}"></script>
    <script src="{{ url('js/modules/historias_medicas/registro/script_tab.js?v=17') }}"></script>
    <script>
        $(function() {
            cargarCodigoCiuo();
            cargarCodigoCiiu();
            $("#codigo_cie").select2({
                placeholder: "SELECCIONE UNA OPCION",
                ajax: {
                    url: "{{ route('getCargaDatosCie') }}",
                    type: "post",
                    delay: 250,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            'tipo': 'cie_id',
                            query: params.term, // search term
                            "_token": "{{ csrf_token() }}",
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });

            function cargarCodigoCiuo() {

                $("#codigo_ciuo").select2({
                    placeholder: "SELECCIONE UNA OPCION",
                    ajax: {
                        url: "{{ route('historias.getCargaDatosCiuo') }}",
                        type: "post",
                        delay: 250,
                        dataType: 'json',
                        data: function(params) {
                            return {
                                'tipo': 'ciuo_id',
                                query: params.term, // search term
                                "_token": "{{ csrf_token() }}",
                            };
                        },
                        processResults: function(response) {
                            return {
                                results: response
                            };
                        },
                        cache: true
                    }
                });
            }

            function cargarCodigoCiiu() {

                $("#codigo_ciiu").select2({
                    placeholder: "SELECCIONE UNA OPCION",
                    ajax: {
                        url: "{{ route('historias.getCargaDatosCiiu') }}",
                        type: "post",
                        delay: 250,
                        dataType: 'json',
                        data: function(params) {
                            return {
                                'tipo': 'ciiu_id',
                                query: params.term, // search term
                                "_token": "{{ csrf_token() }}",
                            };
                        },
                        processResults: function(response) {
                            return {
                                results: response
                            };
                        },
                        cache: true
                    }
                });
            }


        });
    </script>
@endsection
@section('content')
    <div id="main">
        @include('modulos.historias_medicas.registro.consulta_general')
    </div>

    <div class="card hidden tabs_carga card-info card-outline">
        <div class="card-body"
            style="  padding: 0px;
        padding-right: 0px;
      padding-right: 10px !important;
    ">
            <div class="row">
                <div class="col-10">
                    <h5 id="codigo_general" class="hidden" style="padding:20px 0px 0px 20px;color: #8e8787;"></h5>
                    <div class="tab-content" style="padding:20px">

                        @include('modulos.historias_medicas.registro.global')
                        @include('modulos.historias_medicas.registro.utils')
                        @include('modulos.historias_medicas.registro.tabs.paciente.index')
                        @include('modulos.historias_medicas.registro.tabs.discapacidad.index')
                        @include('modulos.historias_medicas.registro.tabs.antecedentes_medicos.index')
                        @include('modulos.historias_medicas.registro.tabs.antecedentes_familiares.index')
                        @include('modulos.historias_medicas.registro.tabs.factores_riesgos_puestos.index')
                        @include('modulos.historias_medicas.registro.tabs.antecedentes_gineco.index')
                        @include('modulos.historias_medicas.registro.tabs.examenes_gineco.index')
                        @include('modulos.historias_medicas.registro.tabs.antecedentes_reproductivos_masculinos.index')
                        @include('modulos.historias_medicas.registro.tabs.examenes_reproductivos_masculinos.index')
                        @include('modulos.historias_medicas.registro.tabs.habitos.index')
                        @include('modulos.historias_medicas.registro.tabs.estilo_vida.index')
                        @include('modulos.historias_medicas.registro.tabs.antecedentes_trabajo.index')
                        @include('modulos.historias_medicas.registro.tabs.revision_organos.index')
                        @include('modulos.historias_medicas.registro.tabs.actividades_extras_enfermedades_actual.index')
                        @include('modulos.historias_medicas.registro.tabs.constantes_vitales.index')
                        @include('modulos.historias_medicas.registro.tabs.examen_fisico_regional.index')
                        @include('modulos.historias_medicas.registro.tabs.examenes_generales.index')
                        @include('modulos.historias_medicas.registro.tabs.diagnostico.index')
                        @include('modulos.historias_medicas.registro.tabs.aptitud_medica.index')
                        @include('modulos.historias_medicas.registro.tabs.evaluacion_medica_retiro.index')
                        @include('modulos.historias_medicas.registro.tabs.recomendacion.index')
                        @include('modulos.historias_medicas.registro.tabs.motivos_reintegro.index')
                        @include('modulos.historias_medicas.registro.tabs.datos.index')
                    </div>

                </div>
                <div class="col-2 menu-tab" style="max-height: 500px;
                overflow-y: scroll;">
                    <div class="tabbable" id="tabs-583767">
                        <ul class="nav nav-pills">
                            @include('modulos.historias_medicas.registro.menu_opciones')
                        </ul>

                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
