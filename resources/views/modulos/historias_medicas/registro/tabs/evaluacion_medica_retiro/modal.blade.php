<div class="modal fade" id="modal-evaluacion-medica-retiro" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#E9ECEF;">
                <h5 style="font-size:15px;" class="modal-title" id="myModalLabel">
                    Registro
                </h5>
                <button type="button" class="close" data-dismiss="modal" id="cerrar_modal_evaluacion_medica_retiro"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-md-12">
                            <label>* Se realiz&oacute; la evaluaci&oacute;n:</label>
                        </div>
                        <div class="col-md-12" style="text-align:left">
                            <label>SI</label>&nbsp;
                            <input :disabled="!editar" type="radio" name="evaluacion_medica" id="evaluacion_si"
                                value="true">&nbsp;&nbsp;
                            <label>NO</label>&nbsp;
                            <input :disabled="!editar" type="radio" name="evaluacion_medica" id="evaluacion_no"
                                value="false">&nbsp;&nbsp;
                        </div>
                        <div class="col-sm-12">
                            <input :disabled="!editar" type="hidden" class=" b-requerido evaluacion_medica_retiro"
                                placeholder="Evaluación realizado"
                                v-model="formEvaluacionMedicaRetiro.evaluacion_realizada">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-md-12"><label>* Observaciones:</label>
                            <textarea :disabled="!editar" type="text" id='resultado' class='form-control b-requerido evaluacion_medica_retiro'
                                v-model='formEvaluacionMedicaRetiro.observaciones' maxlength="250" placeholder="Observaciones"></textarea>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-md-12">
                            <label>* Condici&oacute;n del diagn&oacute;stico:</label>
                        </div>
                        <div class="col-md-12" style="text-align:left">
                            <label>PRESUNTIVA</label>&nbsp;
                            <input :disabled="!editar" type="radio" name="condicion_diagnostico" id="dx_presuntiva"
                                value="presuntiva">&nbsp;&nbsp;
                            <label>DEFINITIVO</label>&nbsp;
                            <input :disabled="!editar" type="radio" name="condicion_diagnostico" id="dx_definitiva"
                                value="definitiva">&nbsp;&nbsp;
                            <label>NO APLICA</label>&nbsp;
                            <input :disabled="!editar" type="radio" name="condicion_diagnostico" id="dx_no_aplica"
                                value="no_aplica">&nbsp;&nbsp;
                        </div>
                        <div class="col-sm-12">
                            <input :disabled="!editar" type="hidden" class=" b-requerido evaluacion_medica_retiro"
                                placeholder="Condición del diagnóstico"
                                v-model="formEvaluacionMedicaRetiro.condicion_diagnostico">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-md-12">
                            <label>* La condici&oacute;n de salud esta relacionada con el trabajo:</label>
                        </div>
                        <div class="col-md-12" style="text-align:left">
                            <label>SI</label>&nbsp;
                            <input :disabled="!editar" type="radio" name="salud_relacionada"
                                id="salud_relacionada_si" value="true">&nbsp;&nbsp;
                            <label>NO</label>&nbsp;
                            <input :disabled="!editar" type="radio" name="salud_relacionada"
                                id="salud_relacionada_no" value="false">&nbsp;&nbsp;
                            <label>NO APLICA</label>&nbsp;
                            <input :disabled="!editar" type="radio" name="salud_relacionada"
                                id="salud_relacionada_no_aplica" value="no_aplica">&nbsp;&nbsp;
                        </div>
                        <div class="col-sm-12">
                            <input :disabled="!editar" type="hidden" class=" b-requerido evaluacion_medica_retiro"
                                placeholder="Condición del diagnóstico"
                                v-model="formEvaluacionMedicaRetiro.salud_relacionada">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" v-show="editar">
                <button disabled="disabled" class="btn btn-info  btn-sm" style="display: none;"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Guardando
                </button>
                <button class="btn btn-default btn-sm" v-on:click="limpiarEvaluacionMedicaRetiro()"
                    :disabled="!editar"><i class="fa fa-eraser"></i>&nbsp;Limpiar datos</button>
                <button class="btn btn-outline-info btn-sm" v-on:click="guardarEvaluacionMedicaRetiro()"
                    :disabled="!editar"><i class="fa fa-save"></i>&nbsp;Grabar</button>
            </div>
        </div>

    </div>
</div>
