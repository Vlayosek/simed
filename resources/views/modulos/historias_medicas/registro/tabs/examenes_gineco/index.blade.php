<div class="tab-pane" id="examenes_gineco" v-show="consultaDatos">
    <div id="main_examenes_gineco">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 hidden" style="background:#E9ECEF;text-align:center">
                    <label>EX&Aacute;MENES REALIZADOS</label>
                </div>
                <div class="col-md-12"><br>
                    <button type="button" class="btn btn-primary btnTop btn-sm" data-toggle="modal"  v-show="editar"  :disabled="!editar"  data-target="#modal-examenes_gineco" v-on:click="limpiarExamen()" data-backdrop="static" data-keyboard="false">
                        <i class="fa fa-plus"></i>&nbsp; Nuevo
                    </button>
                </div>
                <div class="col-md-12"><br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table" id="tablaConsulta">
                                <table class="table table-bordered table-striped" id="dt_examenes_gineco" style="width:100%!important">
                                    <thead>
                                        <th>Tipo de examen</th>
                                        <th>Realizo el examen</th>
                                        <th>Tiempo (años)</th>
                                        <th>Resultado</th>
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

            @include('modulos.historias_medicas.registro.tabs.examenes_gineco.modal_examen')
        </div>
    </div>
</div>
<script src="{{ url('js/modules/historias_medicas/registro/vue_script_examenes_gineco.js?v=116') }}"></script>