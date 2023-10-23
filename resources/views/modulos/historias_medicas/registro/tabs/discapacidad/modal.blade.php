<div class="modal fade" id="modal-container-discapacidad" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">
                    Discapacidad
                </h5>
                <button type="button" class="close" data-dismiss="modal" id="cerrar_modal_discapacidad">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <input :disabled="!editar" type="hidden" v-model="formCrear_externos.discapacidad.id">
                        <div class="col-md-12">
                            <label>* Tipo de Discapacidades:</label>
                            <select :disabled="!editar"
                                class=" form-control form-control-sm b-requerido discapacidad"
                                placeholder="Tipos de Discapacidades" v-model="formCrear_externos.discapacidad.nombre">
                                <option value="">SELECCIONE UNA OPCION
                                </option>
                                <option v-for="(value,index) in consultaDatosDiscapacidades" v-text="value"
                                    :value="value" :selected="value == formCrear_externos.discapacidad.nombre">
                                </option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label>Número de Carnet de Conadis:</label>
                            <input id="carnetConadis" :disabled="!editar || flagDiscapacidad == true" type="text"
                                size="40" maxlength="40" class="form-control form-control-sm"
                                placeholder="Numero de Carnet" v-model="formCrear_externos.discapacidad.numero_carnet ">
                        </div>
                        <div class="col-md-12">
                            <label>Porcentaje de Discapacidad:</label>
                            {{-- <input id="porcentajeDiscapacidad" :disabled="!editar || flagDiscapacidad == true"
                                type="number" placeholder="Porcentaje de discapacidad"
                                v-model="formCrear_externos.discapacidad.porcentaje"
                                class="form-control form-control-sm numero b-requerido discapacidad" min="0"
                                max="100"> --}}
                            <input :disabled="!editar || flagDiscapacidad == true" type="text" maxlength="2"
                                placeholder="Porcentaje de discapacidad"
                                v-model="formCrear_externos.discapacidad.porcentaje"
                                class="form-control form-control-sm numero">
                        </div>
                        <div class="col-md-12">
                            <label for="fileDiscapacidad">
                                Seleccione un archivo:</label>
                            <input :disabled="flagDiscapacidad == true" type="file"
                                placeholder="Archivo Discapacidad" name="fileDiscapacidad" id="fileDiscapacidad"
                                accept=".pdf, image/png, image/jpeg, image/jpg" class="form-control form-control-sm">
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12" style="padding-top: 10px" v-show="formDescargaAnexoDiscapacidad">
                            <button title="Descargar Archivo" class="btn btn-info  btn-sm"
                                onclick="app_discapacidad.descargarPdf64Discapacidad()"><i
                                    class="fa fa-info"></i>&nbsp;Descargar
                                Informe</button>

                            {{--  <button title="Eliminar Archivo" class="btn btn-danger  btn-sm"
                                onclick="app_discapacidad.eliminarAnexoDiscapacidad()"><i
                                    class="fa fa-trash"></i>&nbsp;Eliminar
                                Informe</button> --}}

                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer" v-show="editar">

                <button disabled="disabled" class="btn btn-info  btn-sm" style="display: none;"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Guardando
                </button>
                <button class="btn btn-default btn-sm" v-on:click="limpiarDiscapacidad()"><i
                        class="fa fa-eraser"></i>&nbsp;Limpiar datos</button>
                <button class="btn btn-outline-info btn-sm" v-on:click="guardarDiscapacidad()"><i
                        class="fa fa-save"></i>&nbsp;Grabar</button>

            </div>
        </div>

    </div>

</div>
