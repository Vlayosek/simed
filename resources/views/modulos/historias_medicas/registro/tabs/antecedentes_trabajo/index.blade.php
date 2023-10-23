<div class="tab-pane" id="antecedentes_trabajo">
    <div id="main_antecedentes_trabajo">
        <div id="accordion">
            <div class="card card-info">
                <div class="card-header">
                    <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
                            Antecedentes de trabajos anteriores
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="collapse show" data-parent="#accordion">
                    <div class="card-body">

                        <a class="btn btn-primary btn-sm" href="#modal-antecedentes-trabajo" role="button"
                            data-toggle="modal" v-on:click="limpiarAntecedentesTrabajo()" data-backdrop="static"
                            data-keyboard="false"><i class="fa fa-plus"></i>&nbsp;
                            Nuevo Registro</a>

                        <div class="table-responsive" style="width:100%!important">
                            <table class="table table-bordered table-striped compact" id="dtAntecedentesTrabajo"
                                style="width:100%!important">
                                <thead>
                                    <tr>
                                        <th>Empresa</th>
                                        <th>Puesto Trabajo</th>
                                        <th>Actividades</th>
                                        <th>Tiempo de Trabajo</th>
                                        {{-- <th>Riesgos</th> --}}
                                        <th>Observaciones</th>
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
            <div class="card card-info">
                <div class="card-header">
                    <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                            Accidentes de trabajo (Descripci&oacute;n)
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo" class="collapse" data-parent="#accordion">

                    <div class="card-body">
                        <a class="btn btn-primary btn-sm" v-on:click="limpiarAccidentesTrabajo()"
                            href="#modal-accidentes-trabajo" data-toggle="modal" data-backdrop="static"
                            data-keyboard="false"><i class="fa fa-plus"></i> &nbsp;
                            Nuevo Registro</a>
                        <div class="table-responsive" style="width:100%!important">
                            <table class="table table-bordered table-striped compact"
                                id="dtAntecedentesAccidentesTrabajo" style="width:100%!important">
                                <thead>
                                    <tr>
                                        <th>Calificado por el IES</th>
                                        <th>Especificar</th>
                                        <th>Fecha</th>
                                        <th>Observaciones</th>
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
            <div class="card card-info">
                <div class="card-header">
                    <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseThree">
                            Enfermedades Profesionales
                        </a>
                    </h4>
                </div>
                <div id="collapseThree" class="collapse" data-parent="#accordion">

                    <div class="card-body">

                        <a class="btn btn-primary btn-sm" v-on:click="limpiarEnfermedadesProfesionales()"
                            href="#modal-enfermedades-profesionales" data-toggle="modal" data-backdrop="static"
                            data-keyboard="false"><i class="fa fa-plus"></i> &nbsp;
                            Nuevo Registro</a>

                        <div class="table-responsive" style="width:100%!important">
                            <table class="table table-bordered table-striped compact"
                                id="dtAntecedentesEnfermedadesProfesionales" style="width:100%!important">
                                <thead>
                                    <tr>
                                        <th>Calificado por el IES</th>
                                        <th>Especificar</th>
                                        <th>Fecha</th>
                                        <th>Observaciones</th>
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
        @include('modulos.historias_medicas.registro.tabs.antecedentes_trabajo.modal')

    </div>
</div>

<script src="{{ url('js/modules/historias_medicas/registro/vue_script_antecedentes_trabajo.js?v=2') }}"></script>
