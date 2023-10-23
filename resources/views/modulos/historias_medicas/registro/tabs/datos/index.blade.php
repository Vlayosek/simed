<div class="tab-pane active " id="datos">
    <div id="main_datos">
        <div class="col-md-12 ">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <label>INSTITUCIÓN: </label>
                            <input :disabled="!editar" type="text" :value="formCrear.institucion"
                                class="form-control form-control-sm" disabled="disabled"></span>
                            <input :disabled="!editar" type="hidden" id="institucion"
                                value="{{ config('app_historias_medicas.institucion') }}">
                        </div>
                        <div class="col-md-3">
                            <label>RUC : </label>
                            <input :disabled="!editar" type="text" :value="formCrear.ruc"
                                class="form-control form-control-sm" disabled="disabled"></span>
                            <input :disabled="!editar" type="hidden" id="ruc"
                                value="{{ config('app_historias_medicas.ruc') }}">
                        </div>
                        <div class="col-md-3">
                            <label>CIIU : </label>
                            {!! Form::select('ciuu_', [], null, [
                                'class' => 'form-control form-control-sm',
                                'id' => 'codigo_ciiu',
                                'placeholder' => 'SELECCIONE UNA OPCION',
                                'style' => 'width:100%',
                                ':disabled' => '!editar',
                            ]) !!}
                            <input type="hidden" name="" :disabled="!editar" class="b-requerido"
                                id="ciiu_" placeholder="Ciiu" v-model="formCrear.ciiu_descripcion">
                        </div>
                        <div class="col-md-6">
                            <label>ESTABLECIMIENTO DE SALUD : </label>
                            <input :disabled="!editar" type="text" v-model="formCrear.establecimiento_salud"
                                class="inputSFC form-control form-control-sm">
                            <input :disabled="!editar" type="hidden" id="establecimiento_salud"
                                value="{{ config('app_historias_medicas.establecimiento_salud') }}">
                        </div>
                        <div class="col-md-6">
                            <label>C&Oacute;DIGO DEL REGISTRO : </label>
                            <input :disabled="!editar" type="text" v-model="formCrear.codigo"
                                class="inputSFC form-control form-control-sm" style="float:right">
                            <input :disabled="!editar" type="hidden" id="codigo_siguiente"
                                value="{{ $codigo_siguiente }}">
                            <input :disabled="!editar" type="hidden" id="id" value="{{ $id }}">
                        </div>
                        <div class="col-md-6">
                            <label>HISTORIA CLÍNICA : </label>
                            <input disabled type="text" v-model="formCrear.historia_clinica"
                                class="inputSFC form-control form-control-sm" style="float:right">
                        </div>
                        <div class="col-md-6">
                            <label>NÚMERO DE ARCHIVO : </label>
                            <input disabled type="text" v-model="formCrear.numero_archivo"
                                class="inputSFC form-control form-control-sm" style="float:right">
                        </div>
                        {{-- <div class="col-md-12">
                            <label>
                                Motivo de la Consulta
                            </label>
                            <textarea :disabled="!editar" class="form-control form-control-t" v-model="formCrear.motivo_consulta"></textarea>
                        </div> --}}
                        <a href="{{ url('historias/consulta') }}" id="ir_consulta" class="hidden">IR
                            A CONSULTAR
                            HISTORIAS</a>

                    </div>
                </div>
                <div class="col-md-12 btnTop" v-show="editar">
                    <button :disabled="!editar" class="btn btn-outline-primary"
                        v-on:click="guardarAtencionMedica()">Grabar Atención Médica</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ url('js/modules/historias_medicas/registro/vue_script_datos.js?v=119') }}"></script>
