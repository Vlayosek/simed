<div class="tab-pane" id="actividades_extras_enfermedad">
    <div id="main_actividades_extras_enfermedades_actual">
        <div class="col-md-12" v-show="habilitaActividad">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12" v-show="editar">
                            <label>
                                ACTIVIDADES EXTRA LABORALES :
                            </label>
                            <div class="input-group">
                                <input :disabled="!editar" type="text" class="form-control form-control-sm"
                                    placeholder="Descripcion de las actividades extra laborales"
                                    v-model="formCrear_externos.actividades_extras.descripcion">
                                <span class="input-group-btn">
                                    <button disabled="disabled" class="btn btn-info  btn-sm" style="display: none;"><img
                                            src="{{ url('/spinner.gif') }}">&nbsp;Guardando
                                    </button>
                                    <button class="btn btn-default btn-sm" v-on:click="limpiarInput"><i
                                            class="fa fa-eraser"></i>&nbsp;</button>
                                    <button class="btn btn-primary btn-sm"
                                        v-on:click="guardarActividadExtraEnfermedadActual('ACTIVIDAD')"><i
                                            class="fa fa-save"></i>&nbsp;Grabar</button>

                                </span>
                            </div>
                            <div class="col-sm-12">
                                <input :disabled="!editar" type="hidden" class="b-requerido actividades_extras"
                                    placeholder="Descripcion de las actividades extra laborales"
                                    v-model="formCrear_externos.actividades_extras.descripcion">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <br />
                        <div class="row">
                            <div class="table-responsive" style="width:100%!important">
                                <table class="table table-bordered table-striped compact" id="dt_actividades_extras"
                                    style="width:100%!important">
                                    <thead>
                                        <tr>
                                            <th>Descripción</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12" v-show="!habilitaActividad">
            <div class="row">
                <div class="col-md-12">
                    <div class="row" v-show="editar">
                        <div class="col-md-12">
                            <label>
                                ENFERMEDAD ACTUAL:
                            </label>
                            <div class="input-group">
                                <input :disabled="!editar" type="text" class="form-control form-control-sm"
                                    placeholder="Descripcion de las enferemedades actuales"
                                    v-model="formCrear_externos.enfermedad_actual.descripcion">
                                <span class="input-group-btn">
                                    <button disabled="disabled" class="btn btn-info  btn-sm" style="display: none;"><img
                                            src="{{ url('../spinner.gif') }}">&nbsp;Guardando
                                    </button>
                                    <button class="btn btn-default btn-sm" v-on:click="limpiarInput"><i
                                            class="fa fa-eraser"></i>&nbsp;</button>
                                    <button class="btn btn-primary btn-sm"
                                        v-on:click="guardarActividadExtraEnfermedadActual('ENFERMEDAD')"><i
                                            class="fa fa-save"></i>&nbsp;Grabar</button>

                                </span>
                            </div>
                            <div class="col-sm-12">
                                <input :disabled="!editar" type="hidden" class="b-requerido enfermedad_actual"
                                    placeholder="Descripcion de las enferemedades actuales"
                                    v-model="formCrear_externos.enfermedad_actual.descripcion">
                            </div>
                        </div>

                    </div>

                    <div class="col-md-12">
                        <br />
                        <div class="row">
                            <div class="table-responsive" style="width:100%!important">
                                <table class="table table-bordered table-striped compact" id="dt_enfermedad_actual"
                                    style="width:100%!important">
                                    <thead>
                                        <tr>
                                            <th>Descripción</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script
    src="{{ url('js/modules/historias_medicas/registro/vue_script_actividades_extras_enfermedades_actual.js?v=115') }}">
</script>
