<div class="modal fade" id="modal-antecedentes_reproductivos_masculinos">
    <div class='modal-dialog modal-md' style="min-width: 20%!important;">
        <div class="modal-content">
            <div class="modal-header" style="background:#E9ECEF;">
                <h5 style="font-size:15px;" class="modal-title col-sm-12" id="myModalLabel">
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
                            <label>* Método de Planificaci&oacute;n Familiar:</label>
                        </div>
                        <div class="col-md-12" style="text-align:center">
                            <label>SI</label>&nbsp;
                            <input :disabled="!editar" type="radio" name="planificacion_familiar_reproductivo"
                                id="planificacion_si_reproductivo" value="true">&nbsp;&nbsp;
                            <label>NO</label>&nbsp;
                            <input :disabled="!editar" type="radio" name="planificacion_familiar_reproductivo"
                                id="planificacion_no_reproductivo" value="false">&nbsp;&nbsp;
                        </div>
                        <div class="col-sm-12">
                            <input type="hidden" placeholder="Método de Planificación Familiar"
                                v-model="formAntecedenteReproductivo.planificacion_familiar">
                        </div>
                        <div class="col-md-12">
                            <input :disabled="!editar" type="text"
                                class="form-control form-control-sm b-requerido antecedentes_reproductivos"
                                placeholder="Método de Planificación Familiar"
                                v-model="formAntecedenteReproductivo.tipo_planificacion_familiar">
                        </div>
                        <div class="col-md-12">
                            <label>* Hijos:</label><br>
                        </div>
                        <div class="col-md-6">
                            <label>Vivos</label>
                            <input :disabled="!editar" type="text" size="5" maxlength="5"
                                class="form-control form-control-sm numero b-requerido antecedentes_reproductivos"
                                placeholder="Hijos vivos" v-model="formAntecedenteReproductivo.hijos_vivos">&nbsp;&nbsp;
                        </div>
                        <div class="col-md-6">
                            <label>Muertos</label>
                            <input :disabled="!editar" type="text" size="5" maxlength="5"
                                class="form-control form-control-sm numero b-requerido antecedentes_reproductivos"
                                placeholder="Hijos muertos" v-model="formAntecedenteReproductivo.hijos_muertos">
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
                    id="cerrar_antecedente_reproductivos_masculinos" v-show="!cargando"><b><i
                            class="fa fa-times"></i></b> Cerrar</button>
            </div>
        </div>
    </div>
</div>
