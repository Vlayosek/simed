<div class="tab-pane" id="recomendacion" v-show="consultaDatos">
    <div id="main_recomendaciones">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 hidden" style="background:#E9ECEF;text-align:center">
                    <label>RECOMENDACIONES Y/O TRATAMIENTOS</label>
                </div>
                <div class="col-md-12"><br>
                    <button type="button" class="btn btn-primary btnTop btn-sm" data-toggle="modal"  v-show="editar"  :disabled="!editar"  data-target="#modal-nueva_recomendacion" v-on:click="limpiarRecomendacion()" data-backdrop="static" data-keyboard="false">
                        <i class="fa fa-plus"></i>&nbsp; Nuevo
                    </button>
                </div>
                <div class="col-md-12"><br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table" id="tablaConsulta">
                                <table class="table table-bordered table-striped" id="dt_recomendaciones" style="width:100%!important">
                                    <thead>
                                        <th>Recomendaciones y/o tratamientos</th>
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
            @include('modulos.historias_medicas.registro.tabs.recomendacion.modal_recomendacion')
        </div>
    </div>
</div>
<script src="{{ url('js/modules/historias_medicas/registro/vue_script_recomendaciones.js?v=113') }}"></script>