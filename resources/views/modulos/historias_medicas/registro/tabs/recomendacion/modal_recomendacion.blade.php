<div class="modal fade" id="modal-nueva_recomendacion">
    <div class='modal-dialog modal-md' style="min-width: 20%!important;">
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
                            <label>* Recomendaciones y/o tratamiento:</label>
                            <textarea :disabled="!editar" type="text" id='observacion' class='form-control b-requerido recomendacion_' v-model='formRecomendacion.recomendacion' maxlength="250" placeholder="Recomendación y/o tratamiento"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-end"  v-show="editar" >
                <button class="btn btn-primary" disabled v-show="cargando"><img src="{{ url('/spinner.gif') }}">&nbsp;Guardando</button>
                <button class="btn btn-outline-info btn-sm" v-on:click="guardar();" v-show="!cargando"><i class="fa fa-save"></i>&nbsp;Guardar</button>
                <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal" id="cerrar_modal_recomendaciones" v-show="!cargando"><b><i class="fa fa-times"></i></b> Cerrar</button>
            </div>
        </div>
    </div>
</div>