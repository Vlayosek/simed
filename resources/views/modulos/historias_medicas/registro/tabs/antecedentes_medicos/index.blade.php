<div class="tab-pane" id="antecedentes_medicos">
    <div id="main_antecedentes_medicos">

        <div class="col-md-12">
            <div class="row">

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12" v-show="editar">
                            <label>
                                Antecedentes Patologicos Personales:
                            </label>
                            <div class="input-group">
                                <input :disabled="!editar" type="text" class="form-control form-control-sm"
                                    placeholder="Descripcion de la patología"
                                    v-model="formCrear_externos.antecedentes_personales.descripcion">
                                <span class="input-group-btn">
                                    <button disabled="disabled" class="btn btn-info  btn-sm" style="display: none;"><img
                                            src="{{ url('/spinner.gif') }}">&nbsp;Guardando
                                    </button>
                                    <button class="btn btn-default btn-sm" v-on:click="limpiarInput"><i
                                            class="fa fa-eraser"></i>&nbsp;</button>
                                    <button class="btn btn-primary btn-sm"
                                        v-on:click="guardarAntecedentesPersonalesQuirurgicos('PERSONALES')"><i
                                            class="fa fa-save"></i>&nbsp;Grabar</button>

                                </span>
                            </div>
                            <div class="col-sm-12">
                                <input :disabled="!editar" type="hidden"
                                    class="b-requerido antecedentes_medicos_personales"
                                    placeholder="Antecedente Patologico Personal"
                                    v-model="formCrear_externos.antecedentes_personales.descripcion">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <br />
                        <div class="row">
                            <div class="table-responsive" style="width:100%!important">
                                <table class="table table-bordered table-striped compact" id="dtAntecedentesPersonales"
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
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12" v-show="editar">
                            <label>
                                Antecedentes Patologicos Quirurgicos:
                            </label>
                            <div class="input-group">
                                <input :disabled="!editar" type="text" class="form-control form-control-sm"
                                    placeholder="Descripcion de la patología"
                                    v-model="formCrear_externos.antecedentes_quirurgicos.descripcion">
                                <span class="input-group-btn">
                                    <button disabled="disabled" class="btn btn-info  btn-sm" style="display: none;"><img
                                            src="{{ url('/spinner.gif') }}">&nbsp;Guardando
                                    </button>
                                    <button class="btn btn-default btn-sm" v-on:click="limpiarInput"><i
                                            class="fa fa-eraser"></i>&nbsp;</button>
                                    <button class="btn btn-primary btn-sm"
                                        v-on:click="guardarAntecedentesPersonalesQuirurgicos('QUIRURGICOS')"><i
                                            class="fa fa-save"></i>&nbsp;Grabar</button>

                                </span>
                            </div>
                            <div class="col-sm-12">
                                <input :disabled="!editar" type="hidden"
                                    class="b-requerido antecedentes_medicos_quirurgicos"
                                    placeholder="Antecedente Patologico Quirurgico"
                                    v-model="formCrear_externos.antecedentes_quirurgicos.descripcion">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <br />
                        <div class="row">
                            <div class="table-responsive" style="width:100%!important">
                                <table class="table table-bordered table-striped compact" id="dtAntecedentesQuirurgicos"
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
<script src="{{ url('js/modules/historias_medicas/registro/vue_script_antecedentes.js?v=113') }}"></script>
