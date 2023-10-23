<div class="modal fade" id="modal-container-antecedentes_familiares" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">
                    Antecedentes Familiares
                </h5>
                <button type="button" class="close" data-dismiss="modal" id="cerrar_modal_antecedentes_familiares">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row"><input :disabled="!editar" type="hidden" v-model="formCrear_externos.antecedentes_familiares.id">

                        <div class="col-md-12"><label>* Tipo de Antecedentes Familiares:</label>
                            <select :disabled="!editar" class=" form-control form-control-sm b-requerido antecedentes_familiares select2"
                                placeholder="Antecedentes Familiares"
                                id="detalle_antecedentes_familiares">
                                <option v-for="(value,index) in consultaDatosAntecedentesFamiliares" v-text="value"
                                    :value="value">
                                </option>
                            </select>
                        </div>
                        <div class="col-md-12"><label>* Descripción :</label>
                            <textarea :disabled="!editar" class="form-control b-requerido antecedentes_familiares"
                                v-model="formCrear_externos.antecedentes_familiares.descripcion" placeholder="descripcion"></textarea>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer" v-show="editar" >

                <button disabled="disabled" class="btn btn-info  btn-sm" style="display: none;" v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Cargando
                </button>

                <button class="btn btn-outline-info btn-sm" v-on:click="guardarAntecedenteFamiliar()" v-show="!cargando"><i
                        class="fa fa-save"></i>&nbsp;Grabar</button>

            </div>
        </div>

    </div>

</div>
