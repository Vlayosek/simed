<div class="modal fade" id="modal-examen-general-especifico" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#E9ECEF;">
                <h5 style="font-size:15px;" class="modal-title" id="myModalLabel">
                    Ex&aacute;men General Espec&iacute;fico
                </h5>
                <button type="button" class="close" data-dismiss="modal" id="cerrar_modal_examen_general_especifico"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>* Tipo de Examen:</label>
                            <select :disabled="!editar"
                                class="form-control form-control-sm b-requerido examen_general_especifico"
                                placeholder="Tipos de Examenes"
                                v-model="formCrear_examen_general_especifico.descripcion">
                                <option value="">SELECCIONE UNA OPCION
                                </option>
                                <option v-for="(value,index) in consultaDatosExamenGeneralEspecifico" v-text="value"
                                    :value="value"
                                    :selected="value == formCrear_examen_general_especifico.descripcion">
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>* Fecha:</label>
                            <div class="row">
                                <div class="col-sm-12">
                                    <input :disabled="!editar" autocomplete="off"
                                        class="form-control form-control-sm b-requerido examen_general_especifico"
                                        type="date" id="fecha_examen" placeholder="Fecha de Exámen"
                                        v-model="formCrear_examen_general_especifico.fecha">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>* Resultados:</label>
                            <textarea :disabled="!editar" id="inputDescription" class="form-control b-requerido examen_general_especifico"
                                rows="8" v-model="formCrear_examen_general_especifico.resultados" placeholder="Resultados"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <br>
                    <div class="col-md-12">
                        <label for="fileExamen">Seleccione un archivo:</label>
                        <input type="file" name="fileExamen" id="fileExamen"  accept=".pdf, image/png, image/jpeg, image/jpg"
                            class="form-control form-control-sm">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" style="padding-top: 10px" v-show="formDescargaAnexoExamen">
                        <button title="Descargar Archivo" class="btn btn-info  btn-sm"
                            onclick="app_examen_general_especifico.descargarPdf64Examen()"><i
                                class="fa fa-info"></i>&nbsp;Descargar
                            Informe</button>

                        {{--  <button title="Eliminar Archivo" class="btn btn-danger  btn-sm"
                                onclick="app_examen_general_especifico.eliminarAnexoEnfermedad)"><i
                                    class="fa fa-trash"></i>&nbsp;Eliminar
                                Informe</button> --}}

                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between" v-show="editar">
                <button disabled="disabled" class="btn btn-info  btn-sm" style="display: none;"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Guardando
                </button>
                <button class="btn btn-default btn-sm" v-on:click="limpiarExamenGeneralEspecifico()"
                    :disabled="!editar"><i class="fa fa-eraser"></i>&nbsp;Limpiar datos</button>
                <button class="btn btn-outline-info btn-sm" v-on:click="guardarExamenGeneralEspecifico()"
                    :disabled="!editar"><i class="fa fa-save"></i>&nbsp;Grabar</button>
            </div>
        </div>

    </div>
</div>
