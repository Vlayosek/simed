<div class="modal fade" id="modal-nuevo_diagnostico">
    <div class='modal-dialog modal-md' style="min-width: 30%!important;">
        <div class="modal-content">
            <div class="modal-header" style="background:#E9ECEF;">
                <h5 style="font-size:15px;" class="modal-title col-sm-12" id="myModalLabel">
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
                            <label>* Descripci&oacute;n:</label>
                            <textarea :disabled="!editar" type="text" class="form-control b-requerido diagnostico_" placeholder="Decripción"
                                v-model="formDiagnostico.descripcion"></textarea>
                        </div>
                        <div class="col-md-12"><br></div>
                        <div class="col-md-12 input-group">
                            <label>* Cie:</label>&nbsp;&nbsp;
                            <label style="font-weight:400" v-text="formDiagnostico.cie_descripcion"></label>
                            <!-- <input type="text" class="form-control form-control-sm b-requerido diagnostico_" placeholder="CIE" v-model="formDiagnostico.codigo_cie_id"> -->
                        </div>
                        <div class="col-md-12">
                            {!! Form::select('cie_', [], null, [
                                'class' => 'form-control form-control-sm',
                                'id' => 'codigo_cie',
                                'placeholder' => 'SELECCIONE UNA OPCION',
                                'style' => 'width:100%',
                                ':selected' => 'formDiagnostico.codigo_cie_id',
                            ]) !!}
                            <input type="hidden" name="" :disabled="!editar" class="b-requerido diagnostico_"
                                id="cie_" placeholder="Cie" v-model="formDiagnostico.codigo_cie_id">
                        </div>
                        <div class="col-md-12"><br></div>
                        <div class="col-md-6" style="text-align:center">
                            <label>PRESUNTIVO</label>&nbsp;
                            <input :disabled="!editar" type="radio" name="tipo_diagnostico" id="presuntivo_chk"
                                value="presuntivo">
                        </div>
                        <div class="col-md-6" style="text-align:center">
                            <label>DEFINITIVO</label>&nbsp;
                            <input :disabled="!editar" type="radio" name="tipo_diagnostico" id="definitivo_chl"
                                value="definitivo">
                        </div>
                        <div class="col-sm-12">
                            <input :disabled="!editar" type="hidden" class=" b-requerido diagnostico_"
                                placeholder="Tipo de diagnostico" v-model="formDiagnostico.tipo">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end" v-show="editar">
                <button class="btn btn-primary" disabled v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Guardando</button>
                <button class="btn btn-outline-info btn-sm" v-on:click="guardar();" v-show="!cargando"><i
                        class="fa fa-save"></i>&nbsp;Guardar</button>
                <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal" id="cerrar_modal_diagnostico"
                    v-show="!cargando"><b><i class="fa fa-times"></i></b> Cerrar</button>
            </div>
        </div>
    </div>
</div>
