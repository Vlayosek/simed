@extends('layouts.app_')
@section('css')
    <link href="{{ url('adminlte3/plugins/notifications/sweetalert.css') }}" rel="stylesheet">
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
@endsection
@section('javascript')
    <script src="{{ url('adminlte3/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ url('js/modules/historias_medicas/consulta/global.js?v=6') }}"></script>
    <script src="{{ url('js/modules/historias_medicas/consulta/script.js?v=6') }}"></script>
    <script>
        $(function() {
            $('.pickadate').datepicker({
                formatSubmit: 'yyyy-mm-dd',
                format: 'yyyy-mm-dd',
                selectYears: true,
                editable: true,
                autoclose: true,
                todayHighlight: true,
                orientation: 'top'
            }).datepicker('update', '');

            $("#paciente_id").select2({
                placeholder: "SELECCIONE UNA OPCION",
                ajax: {
                    url: "{{ route('getCargarDatosFuncionario') }}",
                    type: "post",
                    delay: 250,
                    dataType: 'json',
                    data: function(params) {
                        return {
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
        });
    </script>
@endsection
@section('content')
    <div id="main">
        @include('modulos.historias_medicas.consulta.modal')
        <div class="card">
            <div class="card-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="titulo">ATENCIONES MÉDICAS</h5>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>fecha inicio:</label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" id="fecha_inicio"
                                            value="<?php echo date('Y-m-01'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>fecha fin:</label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" id="fecha_fin"
                                            value="<?php echo date('Y-m-t'); ?>">
                                        <span class="input-group-btn">&nbsp;
                                            <button class="btn btn-default" type="button" onclick="cargarDatatable()">
                                                <span class="fa fa-search">&nbsp;Buscar</span>
                                            </button>

                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row"></div>
                            <label style="float: right">&nbsp;</label><br />
                            <button class="btn btn-primary" v-show="!filtro_activo" style="float: right"
                                href="#modal-container-filtro" role="button" data-toggle="modal"><i
                                    class="fa fa-tasks"></i>&nbsp;&nbsp; Filtrar por funcionario</button>
                            <button class="btn btn-default"v-show="filtro_activo" style="float: right"
                                v-on:click="cambiarEstadoFiltro()"><i class="fa fa-circle"
                                    style="color:#2186df"></i>&nbsp;&nbsp; Filtrado por funcionario</button>
                        </div>
                    </div>

                    <br />
                    <div class="row">
                        <div class="table-responsive" style="width:100%!important">
                            <table class="table table-bordered table-striped compact" id="dt"
                                style="width:100%!important">
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Tipo/Evaluación</th>
                                        <th>Área</th>
                                        <th>Identificación</th>
                                        <th>Funcionario</th>
                                        <th>Motivo / Consulta</th>
                                        <th>Diagnóstico</th>
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
    <script src="{{ url('js/plugins/vue.js') }}"></script>
    <script src="{{ url('js/plugins/axios.js') }}"></script>
    <script src="{{ url('js/modules/historias_medicas/consulta/vue_script.js?v=8') }}"></script>
@endsection
