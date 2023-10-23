<div class="tab-pane" id="aptitud_medica" v-show="consultaDatos">
    <div id="main_aptitudes_medicas">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 hidden" style="background:#E9ECEF;text-align:center">
                    <label>APTITUDES M&Eacute;DICAS</label>
                </div>
                <div class="col-md-12"><br>
                    <button type="button" class="btn btn-primary btnTop btn-sm" data-toggle="modal"  v-show="editar"  :disabled="!editar"  data-target="#modal-aptitud_medica" v-on:click="limpiarAptitud()" data-backdrop="static" data-keyboard="false">
                        <i class="fa fa-plus"></i>&nbsp; Nuevo
                    </button>
                </div>
                <div class="col-md-12"><br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table" id="tablaConsulta">
                                <table class="table table-bordered table-striped" id="dt_aptitudes_medicas" style="width:100%!important">
                                    <thead>
                                        <th>Aptitud</th>
                                        <th>Observaci&oacute;n</th>
                                        <th>Limitaci&oacute;n</th>
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
            @include('modulos.historias_medicas.registro.tabs.aptitud_medica.modal_aptitud')
        </div>
    </div>
</div>
<script src="{{ url('js/modules/historias_medicas/registro/vue_script_aptitudes_medicas.js?v=17') }}"></script>