@extends('layouts.app_')

@section('descripcion_detalle')
    ADMINISTRADOR DE SECCIONES
@endsection
@section('css')
    <link rel="stylesheet" href="{{ url('adminlte3/plugins/wizard/css/montserrat-font.css') }}">
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
@endsection
@section('javascript')
    <!-- Bootstrap Switch -->
    <script src="{{ url('adminlte3/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ url('adminlte3/plugins/datepicker/bootstrap-datepicker.js') }}/"></script>

    <script src="{{ url('js/modules/historias_medicas/secciones/global.js?v=112') }}"></script>
    <script src="{{ url('js/modules/historias_medicas/secciones/script.js?v=117') }}"></script>
@endsection
@section('content')
    @include('modulos.historias_medicas.registro.global')

    <div class="card  card-info card-outline">
        <div class="card-body" style=" padding: 0px; padding-right: 0px;">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 card card-body">
                        <div id="main">
                            <a class="btn btn-primary btn-sm " style="top:20px" href="#modal-secciones" role="button"
                                data-toggle="modal" v-on:click="limpiarData()" data-backdrop="static"
                                data-keyboard="false"><i class="fa fa-plus"></i>&nbsp;
                                Nuevo Registro</a>
                            @include('modulos.historias_medicas.administracion.secciones.modal')
                            <br><br>
                            <div class="table-responsive" style="width:100%!important">
                                <table class="table table-bordered table-striped compact" id="dt"
                                    style="width:100%!important">
                                    <thead>
                                        <tr>
                                            <th>Reg</th>
                                            <th>Descripción</th>
                                            <th>Sección</th>
                                            <th>Campos</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>


            </div>

        </div>
    </div>
    <script src="{{ url('js/plugins/vue.js') }}"></script>
    <script src="{{ url('js/plugins/axios.js') }}"></script>
    <script src="{{ url('js/modules/historias_medicas/secciones/vue_script.js?v=120') }}"></script>
@endsection
