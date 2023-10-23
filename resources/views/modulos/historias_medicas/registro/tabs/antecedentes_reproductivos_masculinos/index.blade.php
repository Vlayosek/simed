<div class="tab-pane" id="antecedentes_reproductivos" v-show="consultaDatos">
    <div id="main_antecedentes_reproductivos_masculino">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 hidden" style="background:#E9ECEF;text-align:center">
                    <label>ANTECEDENTES REPRODUCTIVOS MASCULINO</label>
                </div>
                <div class="col-md-12"><br>
                    <button type="button" class="btn btn-primary btnTop btn-sm" data-toggle="modal"  v-show="editar"  :disabled="!editar"  data-target="#modal-antecedentes_reproductivos_masculinos" v-on:click="limpiarAntecedente()" data-backdrop="static" data-keyboard="false">
                        <i class="fa fa-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="col-md-12"><br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table" id="tablaConsulta">
                                <table class="table table-bordered table-striped" id="dt_antecedentes_reproductivos" style="width:100%!important">
                                    <thead>
                                        <th>Planificiaci&oacute;n Familiar</th>
                                        <th>Hijos</th>
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
            @include('modulos.historias_medicas.registro.tabs.antecedentes_reproductivos_masculinos.modal_antecedente')
        </div>
    </div>
</div>
<script src="{{ url('js/modules/historias_medicas/registro/vue_script_antecedentes_reproductivos_masculino.js?v=116') }}"></script>