<div class="modal fade" id="modal-examenes_gineco">
    <div class='modal-dialog modal-md' style="min-width: 20%!important;">
        <div class="modal-content">
            <div class="modal-header" style="background:#E9ECEF;">
                <h5 style="font-size:15px;" class="modal-title col-sm-12" id="myModalLabel">
                    Registro - Exámenes
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
                            <label>* Examen Realizado:</label>
                        </div>
                        <div class="col-md-12" style="text-align:left">
                            <label>SI</label>&nbsp;
                            <input :disabled="!editar" type="radio" name="examen_gineco" id="examen_si"
                                value="true">&nbsp;&nbsp;
                            <label>NO</label>&nbsp;
                            <input :disabled="!editar" type="radio" name="examen_gineco" id="examen_no"
                                value="false">&nbsp;&nbsp;
                        </div>
                        <div class="col-sm-12">
                            <input :disabled="!editar" type="hidden" class="b-requerido examen_gineco"
                                placeholder="Examen realizado" v-model="formExamenGineco.realizo_examen">
                        </div>
                        {{-- <div class="col-sm-12 hidden">
                            <input :disabled="!editar" class=" b-requerido examen_gineco"
                                placeholder="Exámen realizado" v-model="formExamenGineco.realizo_examen">
                        </div> --}}
                        {{-- v-show="formExamenGineco.realizo_examen == true" --}}
                        <div class="col-md-6">
                            <label>* Tipo de examen:</label>
                            {!! Form::select('tipo_examen', $examenes, null, [
                                'placeholder' => 'SELECCIONE UNA OPCION',
                                'class' => 'form-control form-control-sm',
                                'id' => 'tipo_examen',
                                'v-model' => 'formExamenGineco.tipo_examen',
                            ]) !!}
                            <input :disabled="!editar" class="b-requerido examen_gineco " type="hidden"
                                placeholder="Tipo de exámen" v-model="formExamenGineco.tipo_examen">
                        </div>
                        <div class="col-md-6"><label>* Tiempo (años):</label><br>
                            <input :disabled="!editar" type="text"
                                class="form-control form-control-sm numero b-requerido examen_gineco" type="text"
                                id="año_examen" v-model="formExamenGineco.tiempo" placeholder="Tiempo (años)">
                        </div>
                        <div class="col-md-12"><label>* Resultado:</label>
                            <textarea :disabled="!editar" type="text" id='resultado' class='form-control b-requerido examen_gineco'
                                v-model='formExamenGineco.resultado' maxlength="250" placeholder="Resultado"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-end" v-show="editar">
                <button class="btn btn-primary" disabled v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Guardando</button>
                <button class="btn btn-outline-info btn-sm" v-on:click="guardar();" v-show="!cargando"><i
                        class="fa fa-save"></i>&nbsp;Guardar</button>
                <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal" id="cerrar_examen_gineco"
                    v-show="!cargando"><b><i class="fa fa-times"></i></b> Cerrar</button>
            </div>
        </div>
    </div>
</div>
