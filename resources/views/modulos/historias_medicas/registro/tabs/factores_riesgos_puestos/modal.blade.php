<div class="modal fullscreen-modal fade" id="modal-container-factores_riesgos_puesto" role="dialog"
    aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document" style="min-width:95%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">
                    Factores Riegosos
                </h5>
                <button type="button" class="close" data-dismiss="modal" id="cerrar_modal_factores_riesgos_puesto">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row"><input :disabled="!editar" type="hidden" v-model="formCrear_externos.factores_riesgos_puesto.id">

                        <div class="col-md-6"><label>Cargo :</label>
                            <input disabled type="text"
                                   class="form-control b-requerido factores_riesgos_puesto"
                                   placeholder="Puesto de trabajo" v-model="formCrear_externos.factores_riesgos_puesto.puesto_trabajo">

                            {{--<textarea :disabled="!editar" class="form-control b-requerido factores_riesgos_puesto"
                                v-model="formCrear_externos.factores_riesgos_puesto.puesto_trabajo" placeholder="Puesto de trabajo"></textarea>--}}
                        </div>
                        <div class="col-md-6"><label>* Actividades :</label>
                            <textarea :disabled="!editar" class="form-control b-requerido factores_riesgos_puesto"
                                v-model="formCrear_externos.factores_riesgos_puesto.actividades" placeholder="actividades"></textarea>
                            <br />
                        </div>

                        <div class="col-md-12">
                            <div class="row">

                                <div class="col-md-6">
                                    <label>* Medidas Preventivas :</label>
                                    <textarea :disabled="!editar" class="form-control b-requerido factores_riesgos_puesto"
                                        v-model="formCrear_externos.factores_riesgos_puesto.medidas_preventivas" placeholder="medidas preventivas"></textarea>
                                </div>
                                <div class="col-md-6"><label>* Descripción :</label>
                                    <textarea :disabled="!editar" class="form-control b-requerido factores_riesgos_puesto"
                                        v-model="formCrear_externos.factores_riesgos_puesto.descripcion" placeholder="descripcion"></textarea>
                                </div>
                                <div class="col-md-12">
                                    <br />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div v-for="value in consultaDatosFactoresRiegososPuestos" class="col-md-4">
                                    <h5 v-text="value.descripcion" style="font-weight: bold"></h5>
                                    <ul>
                                        <li v-for="valueHijos in value.hijos">
                                            <label class="container"><span v-text="valueHijos.descripcion"
                                                                           style="font-size: 13px;"></span>
                                                <input :disabled="!editar" type="checkbox" name="factores_laborales" :value="valueHijos.id"
                                                       :checked="formCrear_externos.factores_riesgos_puesto.detalles.indexOf(
                                                        valueHijos.id) != -1">
                                                <span class="checkmark"></span>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer" v-show="editar" >
                <button disabled="disabled" class="btn btn-info " style="display: none;" v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Cargando
                </button>
                <button type="button" class="btn btn-default" :disabled="!editar" data-dismiss="modal" v-show="!cargando">
                    Cerrar
                </button>
                <button class="btn btn-outline-info" v-on:click="guardarFactoresRiesgosos()" :disabled="!editar" v-show="!cargando"><i
                        class="fa fa-save"></i>&nbsp;Grabar</button>

            </div>
        </div>

    </div>

</div>
