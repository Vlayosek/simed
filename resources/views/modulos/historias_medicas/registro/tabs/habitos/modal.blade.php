<div class="modal fade" id="modal-habitos" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#E9ECEF;">
                <h5 style="font-size:15px;" class="modal-title" id="myModalLabel">
                    Registro
                </h5>
                <button type="button" class="close" data-dismiss="modal" id="cerrar_modal_habitos" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>* Tipo de H&aacute;bitos T&oacute;xicos:</label>
                            <select :disabled="!editar" class="form-control form-control-sm b-requerido habito"
                                placeholder="Tipos de Discapacidades" v-model="formCrear_habitos.descripcion">
                                <option value="">SELECCIONE UNA OPCION
                                </option>
                                <option v-for="(value,index) in consultaDatosHabitos" v-text="value"
                                    :value="value" :selected="value == formCrear_habitos.descripcion">
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2" v-show="consultaDatos">
                        <div class="form-group"><label>* Valor:</label>
                            <div class="row">
                                <div class="col-sm-6">
                                    <input :disabled="!editar" type="checkbox" class="form-control form-control-sm"
                                        id="valor" name="valor" data-bootstrap-switch data-off-color="danger"
                                        data-on-color="success" v-model="formCrear_habitos.valor">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>* Ex consumidor:</label>
                            <div class="row">
                                <div class="col-sm-6">
                                    <input :disabled="!editar" type="checkbox" id="ex_consumidor"
                                        class="form-control form-control-sm" name="ex_consumidor" data-bootstrap-switch
                                        data-off-color="danger" data-on-color="success"
                                        v-model="formCrear_habitos.ex_consumidor">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>* Tiempo de Consumo (meses):</label>
                            <input :disabled="!editar" type="text"
                                class="form-control form-control-sm numero b-requerido habito"
                                placeholder="Tiempo de consumo" v-model="formCrear_habitos.tiempo_consumo">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>* Cantidad:</label>
                            <input :disabled="!editar" type="text"
                                class="form-control form-control-sm b-requerido habito" placeholder="Cantidad"
                                v-model="formCrear_habitos.cantidad">
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Tiempo de abstinencia (meses):</label>
                            <input :disabled="!editar" type="text"
                                class="form-control form-control-sm numero habito" placeholder="Tiempo de abstinencia"
                                v-model="formCrear_habitos.tiempo_abstinencia">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between" v-show="editar">
                <button disabled="disabled" class="btn btn-info  btn-sm" style="display: none;"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Guardando
                </button>
                <button class="btn btn-default btn-sm" v-on:click="limpiarHabitos()" :disabled="!editar"><i
                        class="fa fa-eraser"></i>&nbsp;Limpiar datos</button>
                <button class="btn btn-outline-info btn-sm" v-on:click="guardarHabitos()" :disabled="!editar"><i
                        class="fa fa-save"></i>&nbsp;Grabar</button>
            </div>
        </div>

    </div>
</div>
