<div class="modal fullscreen-modal fade" id="modal-examen-fisico-regional" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="min-width:95%">
        <div class="modal-content">
            <div class="modal-header" style="background:#E9ECEF;">
                <h5 style="font-size:15px;" class="modal-title" id="myModalLabel">
                    Ex&aacute;men F&iacute;sico Regional
                </h5>
                <button type="button" class="close" data-dismiss="modal" id="cerrar_modal_examen_fisico_regional"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div v-for="value in consultaDatosExamenFisicoRegional" class="col-md-4">
                            <h5 v-text="value.descripcion" style="font-weight: bold"></h5>
                            <ul>
                                <li v-for="valueHijos in value.hijos">
                                    <label class="container"><span v-text="valueHijos.descripcion"
                                            style="font-size: 13px;"></span>
                                        <input :disabled="!editar" type="checkbox" name="examenes_fisicos" :value="valueHijos.id"
                                            :checked="formCrear_examen_fisico_regional.detalles.indexOf(
                                                valueHijos.id) != -1">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-12"><label>* Descripci&oacute;n :</label>
                    <textarea :disabled="!editar" class="form-control b-requerido examen_fisico_regional" v-model="formCrear_examen_fisico_regional.descripcion"
                        placeholder="Desscripciones"></textarea>
                </div>
            </div>
            <div class="modal-footer" v-show="editar" >
                <button disabled="disabled" class="btn btn-info  btn-sm" style="display: none;" v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Guardando
                </button>
                <button v-show="!consultaDatos" class="btn btn-default btn-sm"
                    v-on:click="limpiarExamenFisicoRegional()" v-show="!cargando"><i
                        class="fa fa-eraser"></i>&nbsp;Limpiar datos</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" v-show="!cargando">
                    Cerrar
                </button>
                <button class="btn btn-outline-info btn-sm" v-on:click="guardarExamenFisicoRegional()"
                    v-show="!cargando"><i class="fa fa-save"></i>&nbsp;Grabar</button>
            </div>
        </div>

    </div>
</div>
