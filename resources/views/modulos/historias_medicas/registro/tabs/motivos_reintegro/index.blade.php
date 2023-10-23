<div class="tab-pane" id="motivo_reintegro">
    <div id="main_motivos_reintegros">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="row" v-show="editar">
                        <div class="col-md-12">
                            <label>
                                MOTIVO DE CONSULTA / CONDICI&Oacute;N DE REINTEGRO :
                            </label>
                            <div class="input-group">
                                <input :disabled="!editar" type="text"
                                    class="form-control form-control-sm b-requerido motivo_reintegro_"
                                    placeholder="Descripcion" v-model="formMotivoReintegro.descripcion">
                                <span class="input-group-btn">
                                    <button disabled="disabled" class="btn btn-info  btn-sm" style="display: none;"><img
                                            src="{{ url('/spinner.gif') }}">&nbsp;Guardando</button>
                                    <button class="btn btn-default btn-sm" v-on:click="limpiarReintegro"
                                        :disabled="!editar"><i class="fa fa-eraser"></i>&nbsp;</button>
                                    <button class="btn btn-primary btn-sm" v-on:click="guardar();"
                                        :disabled="!editar"><i class="fa fa-save"></i>&nbsp;Grabar</button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <br />
                        <div class="row">
                            <div class="table-responsive" style="width:100%!important">
                                <table class="table table-bordered table-striped compact" id="dt_motivos_reintegros"
                                    style="width:100%!important">
                                    <thead>
                                        <tr>
                                            <th>Descripción</th>
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
</div>
<script src="{{ url('js/modules/historias_medicas/registro/vue_script_motivos_reintegros.js?v=113') }}"></script>
