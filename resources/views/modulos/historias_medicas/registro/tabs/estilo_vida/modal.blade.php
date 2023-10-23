<div class="modal fade" id="modal-estilo-vida" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#E9ECEF;">
                <h5 style="font-size:15px;" class="modal-title" id="myModalLabel">
                    Registro
                </h5>
                <button type="button" class="close" data-dismiss="modal" id="cerrar_modal_estilo_vida"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>* Tipo de Estilo de Vida:</label>
                            <select :disabled="!editar" class="form-control form-control-sm b-requerido estilo_vida"
                                placeholder="Tipos de Discapacidades" v-model="formCrear_estilo_vida.descripcion"
                                id="tipo_estilo">
                                <option value="">SELECCIONE UNA OPCION
                                </option>
                                <option v-for="(value,index) in consultaDatosEstiloVida" v-text="value"
                                    :value="value" :selected="value == formCrear_estilo_vida.descripcion">
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6" v-show="tiempo_cantidad!=''">
                        <div class="form-group">
                            <label v-text="tiempo_cantidad"></label>
                            <input :disabled="!editar" type="text"
                                class="form-control form-control-sm b-requerido estilo_vida"
                                placeholder="Tiempo / Cantidad" v-model="formCrear_estilo_vida.tiempo_cantidad">
                        </div>
                    </div>
                </div>
                <div class="row">

                    {{-- <div class="col-sm-6">
                        <div class="form-group">
                            <label>* Cantidad (Veces):</label>
                            <input :disabled="!editar" type="text" class="form-control form-control-sm numero b-requerido estilo_vida"
                                placeholder="Cantidad (veces en meses)" v-model="formCrear_estilo_vida.cantidad">
                        </div>
                    </div> --}}
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>* ¿Cu&aacute;les?:</label>
                            <textarea :disabled="!editar" id="inputDescription" class="form-control" rows="4"
                                v-model="formCrear_estilo_vida.tipo_estilo_vida"
                                placeholder="Cu&aacute;les estilos de vida (separados en coma (,))"></textarea>
                            <input class="b-requerido estilo_vida " type="hidden"
                                v-model="formCrear_estilo_vida.tipo_estilo_vida"
                                placeholder="Cu&aacute;les estilos de vida (separados en coma (,))"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between" v-show="editar">
                <button disabled="disabled" class="btn btn-info  btn-sm" style="display: none;"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Guardando
                </button>
                <button class="btn btn-default btn-sm" v-on:click="limpiarEstiloVida()" :disabled="!editar"><i
                        class="fa fa-eraser"></i>&nbsp;Limpiar datos</button>
                <button class="btn btn-outline-info btn-sm" v-on:click="guardarEstiloVida()" :disabled="!editar"><i
                        class="fa fa-save"></i>&nbsp;Grabar</button>
            </div>
        </div>

    </div>
</div>
