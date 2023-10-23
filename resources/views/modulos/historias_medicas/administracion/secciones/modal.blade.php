<div class="modal fade" id="modal-secciones" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">
                    Sección
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12"><label>* Tipo de Evaluación:</label>
                            {!! Form::select('descripcion', $tipos_evaluaciones, null, [
                                'class' => 'form-control form-control-sm',
                                'id' => 'descripcion',
                                'v-model' => 'formCrear.secciones.descripcion',
                                'placeholder' => 'SELECCIONE UNA OPCION',
                                'style' => 'width:100%',
                                ':selected' => 'formCrear.descripcion',
                            ]) !!}
                        </div>
                        <div class="col-md-12"><label>* Secciones:</label>
                            <select class=" form-control form-control-sm b-requerido select2" placeholder="Evaluación"
                                multiple="multiple" id="seccion">
                                <option v-for="(value,index) in secciones" v-text="value" :value="value">
                                </option>
                            </select>
                        </div>
                        <div class="col-md-12"><label>* Campos:</label>
                            <select class=" form-control form-control-sm b-requerido select2" placeholder="Evaluación"
                                multiple="multiple" id="campos">
                                <option v-for="(value,index) in campos" v-text="value" :value="value">
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <button disabled="disabled" class="btn btn-info  btn-sm" style="display: none;"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Cargando
                </button>
                <button type="button" class="btn btn-primary" :disabled="cargando" v-on:click="guardarSeccion()">
                    Grabar Sección
                </button>
                <button type="button" class="btn btn-secondary" :disabled="cargando" data-dismiss="modal"
                    id="cerrar_modal_seccion">
                    Cerrar
                </button>
            </div>
        </div>

    </div>
</div>

<div class="hidden">
    @include('modulos.historias_medicas.registro.menu_opciones')
    @include('modulos.historias_medicas.registro.tabs.paciente.formulario')

</div>
