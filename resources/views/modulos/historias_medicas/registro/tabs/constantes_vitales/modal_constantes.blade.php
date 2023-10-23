<div class="modal fade" id="modal-constantes_vitales">
    <div class='modal-dialog modal-md' style="min-width: 20%!important;">
        <div class="modal-content">
            <div class="modal-header" style="background:#E9ECEF;">
                <h5 style="font-size:15px;" class="modal-title" id="myModalLabel">
                    Registro
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
                            <label>* Presi&oacute;n Arterial (mmHg):</label>
                            <input :disabled="!editar" type="text" id='presion_arterial'
                                class='form-control form-control-sm b-requerido constantes_'
                                v-model='formConstante.presion_arterial' placeholder="Presión arterial (mmHg)">
                        </div>
                        <div class="col-md-12">
                            <label>* Temperatura (°C):</label>
                            <input :disabled="!editar" type="text" id='temperatura'
                                class='form-control form-control-sm b-requerido constantes_ decimal'
                                v-model='formConstante.temperatura' placeholder="Temperatura (°C)">
                        </div>
                        <div class="col-md-12">
                            <label>* Frecuencia Cardiaca (Lat/min):</label>
                            <input :disabled="!editar" type="text" id='frecuencia_cardiaca'
                                class='form-control form-control-sm b-requerido constantes_'
                                v-model='formConstante.frecuencia_cardiaca' placeholder="Frecuencia Cardiaca (Lat/min)">
                        </div>
                        <div class="col-md-12">
                            <label>* Saturaci&oacute;n de ox&iacute;geno (02%):</label>
                            <input :disabled="!editar" type="text" id='saturacion_oxigeno'
                                class='form-control form-control-sm b-requerido constantes_ decimal'
                                v-model='formConstante.saturacion_oxigeno' placeholder="Saturación de oxígeno (02%)">
                        </div>
                        <div class="col-md-12">
                            <label>* Frecuencia Respiratoria (fr/min):</label>
                            <input :disabled="!editar" type="text" id='frecuencia_respiratoria'
                                class='form-control form-control-sm b-requerido constantes_'
                                v-model='formConstante.frecuencia_respiratoria'
                                placeholder="Frecuencia Respiratoria (fr/min)">
                        </div>
                        <div class="col-md-12">
                            <label>* Peso (Kilogramos):</label>
                            <input :disabled="!editar" type="text" id='peso'
                                class='form-control form-control-sm b-requerido constantes_ decimal'
                                v-model='formConstante.peso' placeholder="Peso en (Kg)">
                        </div>
                        <div class="col-md-12">
                            <label>* Talla (metros):</label>
                            <input :disabled="!editar" type="text" id='talla'
                                class='form-control form-control-sm b-requerido constantes_ decimal'
                                v-model='formConstante.talla' placeholder="Ejemplo... 1.70">
                        </div>
                        <div class="col-md-12">
                            <label>* &Iacute;ndice de masa corporal (kg/m2):</label>
                            <input readonly type="text" id='masa_corporal'
                                class='form-control form-control-sm b-requerido constantes_'
                                v-model='formConstante.indice_masa_corporal'
                                placeholder="Índice de masa corporal (kg/m2)">
                        </div>
                        <div class="col-md-12">
                            <label>* Per&iacute;metro abdominal (cm):</label>
                            <input :disabled="!editar" type="text" id='perimetro_abdominal'
                                class='form-control form-control-sm b-requerido constantes_ decimal'
                                v-model='formConstante.perimetro_abdominal' placeholder="Perímetro abdominal (cm)">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-end" v-show="editar">
                <button class="btn btn-primary" disabled v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Guardando</button>
                <button class="btn btn-outline-info btn-sm" v-on:click="guardar();" v-show="!cargando"><i
                        class="fa fa-save"></i>&nbsp;Guardar</button>
                <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal" id="cerrar_modal_constantes"
                    v-show="!cargando"><b><i class="fa fa-times"></i></b> Cerrar</button>
            </div>
        </div>
    </div>
</div>
