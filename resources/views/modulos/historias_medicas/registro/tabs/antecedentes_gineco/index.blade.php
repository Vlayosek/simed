<div class="tab-pane" id="antecedentes_gineco" v-show="consultaDatos">
    <div id="main_antecedentes_gineco">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 hidden" style="background:#E9ECEF;text-align:center">
                    <label>ANTECEDENTES GINECO OBST&Eacute;TRICOS</label>
                </div>
                <div class="col-md-12"><br>
                    <button type="button" class="btn btn-primary btnTop btn-sm" data-toggle="modal"  v-show="editar"  :disabled="!editar"  data-target="#modal-antecedentes_gineco" v-on:click="limpiarAntecedente()" data-backdrop="static" data-keyboard="false">
                        <i class="fa fa-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="col-md-12"><br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table" id="tablaConsulta">
                                <table class="table table-bordered table-striped" id="dt_antecedentes_gineco" style="width:100%!important">
                                    <thead>
                                        <th>Menarqu&iacute;a</th>
                                        <th>Ciclos</th>
                                        <th>&Uacute;ltima menstruaci&oacute;n</th>
                                        <th>Gestas</th>
                                        <th>Partos</th>
                                        <th>Ces&aacute;rias</th>
                                        <th>Abortos</th>
                                        <th>Hijos</th>
                                        <th>Vida Sexual Activa</th>
                                        <th>Planificiaci&oacute;n Familiar</th>
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
            @include('modulos.historias_medicas.registro.tabs.antecedentes_gineco.modal_antecedente')
        </div>
    </div>
</div>
<script src="{{ url('js/modules/historias_medicas/registro/vue_script_antecedentes_gineco.js?v=116') }}"></script>