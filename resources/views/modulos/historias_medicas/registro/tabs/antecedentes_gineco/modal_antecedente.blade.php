<div class="modal fade" id="modal-antecedentes_gineco">
    <div class='modal-dialog modal-md' style="min-width: 20%!important;">
        <div class="modal-content">
            <div class="modal-header" style="background:#E9ECEF;">
                <h5 style="font-size:15px;" class="modal-title" id="myModalLabel">
                    Registro
                    <!-- <span v-text="': # '+formNuevo.id" v-show="formNuevo.id!=0" style="font-size:22px"></span> -->
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <label>* Menarqu&iacute;a:</label>
                            <input :disabled="!editar" autocomplete="off"
                                class="form-control form-control-sm b-requerido antecedentes_gineco" type="text"
                                id="fecha_menarquia" placeholder="Menarquía" v-model="formAntecedenteGineco.menarquia">
                        </div>
                        <div class="col-md-12">
                            <label>* Ciclos:</label>
                            <input :disabled="!editar" type="text"
                                class="form-control form-control-sm b-requerido antecedentes_gineco"
                                placeholder="Ciclos" v-model="formAntecedenteGineco.ciclos">
                        </div>
                        <div class="col-md-12">
                            <label>* Fecha de ultima menstruaci&oacute;n:</label>
                            <input :disabled="!editar" autocomplete="off"
                                class="form-control form-control-sm b-requerido antecedentes_gineco" type="date"
                                id="fecha_menstruacion" placeholder="Fecha de Menstruación"
                                v-model="formAntecedenteGineco.menstruacion">
                        </div>
                        <div class="col-md-6">
                            <label>* Gestas:</label>
                            <input :disabled="!editar" type="text" size="5" maxlength="10"
                                class="form-control form-control-sm b-requerido antecedentes_gineco numero"
                                placeholder="Gestas" v-model="formAntecedenteGineco.gestas">
                        </div>
                        <div class="col-md-6">
                            <label>* Partos:</label>
                            <input :disabled="!editar" type="text" size="5" maxlength="10"
                                class="form-control form-control-sm b-requerido antecedentes_gineco numero"
                                placeholder="Partos" v-model="formAntecedenteGineco.partos">
                        </div>
                        <div class="col-md-6">
                            <label>* Ces&aacute;reas:</label>
                            <input :disabled="!editar" type="text" size="5" maxlength="10"
                                class="form-control form-control-sm b-requerido antecedentes_gineco numero"
                                placeholder="Cesáreas" v-model="formAntecedenteGineco.cesareas">
                        </div>
                        <div class="col-md-6">
                            <label>* Abortos:</label>
                            <input :disabled="!editar" type="text" size="5" maxlength="10"
                                class="form-control form-control-sm b-requerido antecedentes_gineco numero"
                                placeholder="Abortos" v-model="formAntecedenteGineco.abortos">
                        </div>
                        <div class="col-md-12">
                            <label>* Hijos:</label><br>
                        </div>
                        <div class="col-md-6">
                            <label>Vivos</label>
                            <input :disabled="!editar" type="text" size="5" maxlength="5"
                                class="form-control form-control-sm numero b-requerido antecedentes_gineco"
                                placeholder="Hijos vivos" v-model="formAntecedenteGineco.hijos_vivos">&nbsp;&nbsp;
                        </div>
                        <div class="col-md-6">
                            <label>Muertos</label>
                            <input :disabled="!editar" type="text" size="5" maxlength="5"
                                class="form-control form-control-sm numero b-requerido antecedentes_gineco"
                                placeholder="Hijos muertos" v-model="formAntecedenteGineco.hijos_muertos">
                        </div>
                        <div class="col-md-12">
                            <label>* Vida sexual activa:</label>
                        </div>
                        <div class="col-md-12" style="text-align:center">
                            <label>SI</label>&nbsp;
                            <input :disabled="!editar" type="radio" name="vida_sexual" id="vida_sexual_si"
                                value="true">&nbsp;&nbsp;
                            <label>NO</label>&nbsp;
                            <input :disabled="!editar" type="radio" name="vida_sexual" id="vida_sexual_no"
                                value="false">&nbsp;&nbsp;
                        </div>
                        <div class="col-sm-12">
                            <input :disabled="!editar" type="hidden" class=" b-requerido antecedentes_gineco"
                                placeholder="Vida sexual activa" v-model="formAntecedenteGineco.vida_sexual">
                        </div>
                        <div class="col-md-12">
                            <label>* Método de Planificaci&oacute;n Familiar:</label>
                        </div>
                        <div class="col-md-12" style="text-align:center">
                            <label>SI</label>&nbsp;
                            <input :disabled="!editar" type="radio" name="planificacion_familiar"
                                id="planificacion_si" value="true">&nbsp;&nbsp;
                            <label>NO</label>&nbsp;
                            <input :disabled="!editar" type="radio" name="planificacion_familiar"
                                id="planificacion_no" value="false">&nbsp;&nbsp;
                        </div>
                        <div class="col-sm-12">
                            <input :disabled="!editar" type="hidden" class=" b-requerido antecedentes_gineco"
                                placeholder="Método de Planificación Familiar"
                                v-model="formAntecedenteGineco.planificacion_familiar">
                        </div>
                        <div class="col-md-12">
                            <input :disabled="!editar" type="text"
                                class="form-control form-control-sm b-requerido antecedentes_gineco"
                                placeholder="Método de Planificación Familiar"
                                v-model="formAntecedenteGineco.tipo_planificacion_familiar">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end" v-show="editar">
                <button class="btn btn-primary" disabled v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Guardando</button>
                <button class="btn btn-outline-info btn-sm" v-on:click="guardar();" v-show="!cargando"><i
                        class="fa fa-save"></i>&nbsp;Guardar</button>
                <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal"
                    id="cerrar_antecedente_gineco" v-show="!cargando"><b><i class="fa fa-times"></i></b>
                    Cerrar</button>
            </div>
        </div>
    </div>
</div>
