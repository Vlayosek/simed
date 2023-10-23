<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label>TIPO DE EVALUACIÓN : </label>

                        <select class="inputSFC form-control form-control-sm" placeholder="Tipos de Evaluaciones"
                            :disabled="editar || guardado" v-model="formCrear.tipo_evaluacion"
                            style="float:right;width:100%" id="tipo_evaluacion_id">
                            <option value="">SELECCIONE UNA OPCION
                            </option>
                            <option v-for="(value,index) in consultaDatosTipoEvaluacion" v-text="value"
                                :value="value" :selected="value == formCrear.tipo_evaluacion">
                            </option>
                        </select>
                        <br />
                        <img src="{{ url('/spinner.gif') }}" width="25px" v-show="cargando"> <label
                            v-show="cargando">Cargando..</label>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div :class="editar ? 'col-md-12' : 'col-md-8'">
                                <label>CÉDULA DEL FUNCIONARIO: &nbsp;&nbsp;&nbsp;&nbsp;</label> &nbsp;<span
                                    name="errorCedula" style="font-weight: bold"
                                    :class="cedula_valida ? 'green' : 'red'"></span>
                                <input maxlength="10" type="text" v-model="formCrear.identificacion"
                                    class="inputSFC numero form-control form-control-sm cedula"
                                    placeholder="cedula del funcionario" id="buscar_identificacion"
                                    :disabled="editar || guardado">
                            </div>
                            <div class="col-md-4" v-show="!editar && !guardado" :disabled="editar">
                                <label>&nbsp;</label>
                                <button class="btn btn-primary btn-sm btn-block"
                                    v-on:click="buscarFuncionario()">Buscar</button>

                            </div>
                            <div class="col-md-4" v-show="!editar && guardado" :disabled="editar">
                                <label>&nbsp;</label>
                                <a href="{{ url('historias/registro') }}"><button
                                        class="btn btn-primary btn-sm btn-block" v-on:click="guardado = false">Buscar
                                        nuevo paciente</button></a>


                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="row" v-show="guardado">
                    <div class="col-md-6">
                        <label>TIPO DE EVALUACIÓN : </label>
                        <select class="inputSFC form-control form-control-sm" :disabled="guardado"
                            v-model="formCrear.tipo_evaluacion" style="float:right;width:100%" id="tipo_evaluacion_id">
                            <option v-for="(value,index) in consultaDatosTipoEvaluacion" v-text="value"
                                :value="value" :selected="value == formCrear.tipo_evaluacion">
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div :class="editar ? 'col-md-12' : 'col-md-8'">
                                <label>CÉDULA DEL FUNCIONARIO: &nbsp;&nbsp;&nbsp;&nbsp;</label>
                                <input maxlength="10" type="text" v-model="formCrear.identificacion"
                                    class="inputSFC numero form-control form-control-sm cedula"
                                    placeholder="cedula del funcionario" id="buscar_identificacion"
                                    :disabled="guardado">
                            </div>
                            <div class="col-md-4" v-show="!editar" :disabled="editar">
                                <label>&nbsp;</label>
                                <a href="{{ url('historias/registro') }}"><button
                                        class="btn btn-primary btn-sm btn-block" v-on:click="guardado = false">Buscar
                                        nuevo paciente</button></a>


                            </div>
                        </div>
                    </div>

                </div> --}}
            </div>
        </div>
    </div>
</div>
