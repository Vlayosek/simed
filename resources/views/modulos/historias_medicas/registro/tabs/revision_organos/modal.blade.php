<div class="modal fade" id="modal-container-revision_organos" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">
                    Revisión de órganos
                </h5>
                <button type="button" class="close" data-dismiss="modal" id="cerrar_modal_revision_organos">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row"><input :disabled="!editar" type="hidden" v-model="formCrear_externos.revision_organos.id">

                        <div class="col-md-12"><label>* Organos:</label>
                            <select :disabled="!editar" class=" form-control form-control-sm b-requerido revision_organos select2"
                                placeholder="Revision de Organos"
                                id="detalle_revision_organos">
                                <option v-for="(value,index) in consultaDatosRevisionOrganos" v-text="value"
                                    :value="value">
                                </option>
                            </select>
                        </div>
                        <div class="col-md-12"><label>* Descripción :</label>
                            <textarea :disabled="!editar" class="form-control b-requerido revision_organos"
                                v-model="formCrear_externos.revision_organos.descripcion" placeholder="descripcion"></textarea>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer" v-show="editar" >

                <button disabled="disabled" class="btn btn-info  btn-sm" style="display: none;" v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Cargando
                </button>

                <button class="btn btn-outline-info btn-sm" v-on:click="guardarRevisionOrganos()" v-show="!cargando"><i
                        class="fa fa-save"></i>&nbsp;Grabar</button>

            </div>
        </div>

    </div>

</div>
