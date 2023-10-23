<div class="tab-pane" id="diagnostico" v-show="consultaDatos">
    <div id="main_diagnostico">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 hidden" style="background:#E9ECEF;text-align:center">
                    <label>DIAGNÓSTICO</label>
                </div>
                <div class="col-md-12"><br>
                    <button type="button" class="btn btn-primary btnTop btn-sm" data-toggle="modal"  v-show="editar"  :disabled="!editar"  data-target="#modal-nuevo_diagnostico" v-on:click="limpiarDiagnostico()" data-backdrop="static" data-keyboard="false">
                        <i class="fa fa-plus"></i>&nbsp;Nuevo Diagnóstico
                    </button>
                </div>
                <div class="col-md-12"><br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table" id="tablaConsulta">
                                <table class="table table-bordered table-striped" id="dt_diagnosticos" style="width:100%!important">
                                    <thead>
                                        <th>Descripci&oacute;n</th>
                                        <th>CIE</th>
                                        <th>Tipo</th>
                                        <th>Acciones</th>
                                    </thead>
                                    <tbody id="tbobymenu" class="menu-pen">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('modulos.historias_medicas.registro.tabs.diagnostico.modal_diagnostico')
        </div>
    </div>
</div>
<script src="{{ url('js/modules/historias_medicas/registro/vue_script_diagnostico.js?v=114') }}"></script>

