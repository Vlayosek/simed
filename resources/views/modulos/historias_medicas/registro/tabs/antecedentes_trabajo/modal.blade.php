<div class="modal fade" id="modal-antecedentes-trabajo" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#E9ECEF;">
                <h5 style="font-size:15px;" class="modal-title" id="myModalLabel">
                    Antecedentes de Trabajo
                </h5>
                <button type="button" class="close" data-dismiss="modal" id="cerrar_modal_antecedentes_trabajo"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label>* Empresa :</label>
                        <input type="text" class="form-control form-control-sm b-requerido antecedentes_trabajo"
                            placeholder="Empresa" v-model="formCrear.antecedentes_trabajo.empresa">
                        {{-- <select class="form-control form-control-sm b-requerido estilo_vida"
                            placeholder="Tipos de Discapacidades" v-model="formCrear.antecedentes_trabajo.descripcion">
                            <option value="">SELECCIONE UNA OPCION
                            </option>
                            <option v-for="(value,index) in consultaDatosAreas" v-text="value"
                                :value="value" :selected="value == formCrear.antecedentes_trabajo.descripcion">
                            </option>
                        </select> --}}
                    </div>
                    <div class="col-md-6">
                        <label>* Puesto de Trabajo :</label>
                        <input type="text" class="form-control form-control-sm b-requerido antecedentes_trabajo"
                            placeholder="Puesto de Trabajo" v-model="formCrear.antecedentes_trabajo.puesto_trabajo">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label>* Observaciones :</label>
                        <textarea class="form-control b-requerido antecedentes_trabajo" v-model="formCrear.antecedentes_trabajo.observaciones"
                            placeholder="Observaciones"></textarea>
                    </div>
                    <div class="col-md-6"><label>* Actividades :</label>
                        <textarea class="form-control b-requerido antecedentes_trabajo"
                            v-model="formCrear.antecedentes_trabajo.actividades_desempenadas" placeholder="Actividades"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>* Tiempo de trabajo (meses):</label>
                            <input type="text"
                                class="form-control form-control-sm numero b-requerido antecedentes_trabajo"
                                placeholder="Tiempo (meses)" v-model="formCrear.antecedentes_trabajo.tiempo_trabajo">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>* Riesgo:</label>

                            <ul>
                                <li v-for="(value,index) in consultaDatosAntecedentesTrabajo">
                                    <label class="container"><span v-text="value" style="font-size: 13px;"></span>
                                        <input type="checkbox" name="antecedentes_trabajo" :value="index"
                                            :checked="formCrear.antecedentes_trabajo.descripcion.indexOf(
                                                index) != -1">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button disabled="disabled" class="btn btn-info  btn-sm" style="display: none;" v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Guardando
                </button>
                <button v-show="!consultaDatos" class="btn btn-default btn-sm" v-on:click="limpiarAntecedentesTrabajo()"
                    v-show="!cargando"><i class="fa fa-eraser"></i>&nbsp;Limpiar datos</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" v-show="!cargando">
                    Cerrar
                </button>
                <button class="btn btn-outline-info btn-sm" v-on:click="guardarAntecedentesTrabajo()"><i
                        class="fa fa-save" v-show="!cargando"></i>&nbsp;Grabar</button>
            </div>
        </div>

    </div>
</div>


<div class="modal fade" id="modal-accidentes-trabajo" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">
                    Accidentes de Trabajo
                </h5>
                <button type="button" class="close" data-dismiss="modal" id="cerrar_modal_antecedentes_accidentes"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>* Fue Calificado por el instituto de seguridad Social Correspondiente:</label>
                    </div>
                    <div class="col-md-6">
                        <label>SI</label>&nbsp;
                        <input id="calificado_accidente" name="calificado_accidente" type="checkbox"
                            v-model="formCrear.accidentes_trabajo.calificado_accidente">&nbsp;&nbsp;
                    </div>
                    {{-- <div class="col-sm-6">
                        <input class="hidden b-requerido accidentes_trabajo" placeholder="Calificacion Accidente" v-model="formExamenGineco.realizo_examen">
                    </div> --}}

                </div>
                <div class="row">
                    <div class="col-md-8" v-show="formCrear.accidentes_trabajo.calificado_accidente">
                        <label>Especificar</label>&nbsp;
                        <input type="text"
                            :class="formCrear.accidentes_trabajo.calificado_accidente ?
                                'form-control form-control-sm b-requerido accidentes_trabajo' :
                                'form-control form-control-sm b-requerido'"
                            placeholder="Especificar" v-model="formCrear.accidentes_trabajo.especificar_accidente">
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>* Fecha:</label>
                            <input autocomplete="off"
                                class="form-control form-control-sm b-requerido accidentes_trabajo" type="date"
                                id="fecha_accidente" placeholder="Fecha de Calificacion Accidente"
                                v-model="formCrear.accidentes_trabajo.fecha_accidente">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>* Observaciones:</label>
                            <textarea id="inputDescription" class="form-control b-requerido accidentes_trabajo" rows="4"
                                v-model="formCrear.accidentes_trabajo.observaciones_accidente"
                                placeholder="Observaciones calificacion por Accidente"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button disabled="disabled" class="btn btn-info  btn-sm" style="display: none;"
                    v-show="cargando"><img src="{{ url('/spinner.gif') }}">&nbsp;Guardando
                </button>
                <button v-show="!consultaDatosAccidentes" class="btn btn-default btn-sm"
                    v-on:click="limpiarAccidentesTrabajo()" v-show="!cargando"><i
                        class="fa fa-eraser"></i>&nbsp;Limpiar datos</button>

                <button v-show="consultaDatosAccidentes" class="btn btn-default btn-sm"
                    v-on:click="limpiarAccidentesTrabajo()" v-show="!cargando"><i
                        class="fa fa-eraser"></i>&nbsp;Nuevo</button>

                <button class="btn btn-outline-info btn-sm" v-on:click="guardarAntecedenteAccidentesTrabajo()"><i
                        class="fa fa-save" v-show="!cargando"></i>&nbsp;Grabar</button>
            </div>
        </div>

    </div>

</div>


<div class="modal fade" id="modal-enfermedades-profesionales" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">
                    Enfermedades profesionales
                </h5>
                <button type="button" class="close" data-dismiss="modal" id="cerrar_modal_enfermedades_profesionales"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>* Fue Calificado por el instituto de seguridad Social Correspondiente:</label>
                    </div>
                    <div class="col-md-6">
                        <label>SI</label>&nbsp;
                        <input id="calificado_enfermedad" name="calificado_enfermedad" type="checkbox"
                            v-model="formCrear.enfermedades_profesionales.calificado_enfermedad">&nbsp;&nbsp;
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-8" v-show="formCrear.enfermedades_profesionales.calificado_enfermedad">
                        <label>Especificar</label>&nbsp;
                        <input type="text"
                            :class="formCrear.enfermedades_profesionales.calificado_enfermedad ?
                                'form-control form-control-sm b-requerido enfermedades_profesionales' :
                                'form-control form-control-sm b-requerido'"
                            placeholder="Especificar"
                            v-model="formCrear.enfermedades_profesionales.especificar_enfermedad">
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>* Fecha:</label>
                            <input autocomplete="off"
                                class="form-control form-control-sm b-requerido enfermedades_profesionales"
                                type="date" id="fecha_enfermedad" placeholder="Fecha de Calificacion Enfermedad"
                                v-model="formCrear.enfermedades_profesionales.fecha_enfermedad">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>* Observaciones:</label>
                            <textarea id="inputDescription" class="form-control b-requerido enfermedades_profesionales" rows="4"
                                v-model="formCrear.enfermedades_profesionales.observaciones_enfermedad"
                                placeholder="Observaciones calificacion por Accidente"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <button disabled="disabled" class="btn btn-info  btn-sm" style="display: none;"
                    v-show="cargando"><img src="{{ url('/spinner.gif') }}">&nbsp;Guardando
                </button>
                <button v-show="!consultaDatosEnfermedades" class="btn btn-default btn-sm"
                    v-on:click="limpiarEnfermedadesProfesionales()" v-show="!cargando"><i
                        class="fa fa-eraser"></i>&nbsp;Limpiar datos</button>
                <button v-show="consultaDatosEnfermedades" class="btn btn-default btn-sm"
                    v-on:click="limpiarEnfermedadesProfesionales()" v-show="!cargando"><i
                        class="fa fa-eraser"></i>&nbsp;Nuevo</button>
                <button class="btn btn-outline-info btn-sm"
                    v-on:click="guardarAntecedentesEnfermedadesProfesionales()"><i class="fa fa-save"
                        v-show="!cargando"></i>&nbsp;Grabar</button>
            </div>
        </div>

    </div>

</div>

{{-- <div class="modal fade" id="modal-antecedentes-accidentes-trabajo" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#E9ECEF;">
                <h5 style="font-size:15px;" class="modal-title" id="myModalLabel">
                    Antecedentes Accidentes de Trabajo
                </h5>
                <button type="button" class="close" data-dismiss="modal" id="cerrar_modal_antecedentes_accidentes" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="row">
                        <div class="col-md-12">
                            <label>* Fue Calificado por el instituto de seguridad Social Correspondiente:</label>
                        </div>
                        <div class="col-md-6">
                            <label>SI</label>&nbsp;
                            <input id="calificado_accidente" name="calificado_accidente" type="checkbox" v-model="formCrear.accidentes_trabajo.calificado_accidente" >&nbsp;&nbsp;
                        </div>
                        <div class="col-md-12" v-show="formCrear.accidentes_trabajo.calificado_accidente">
                            <label>Especificar</label>&nbsp;
                            <input type="text" :class="formCrear.accidentes_trabajo.calificado_accidente ? 'form-control form-control-sm b-requerido accidentes_trabajo' : 'form-control form-control-sm b-requerido'"
                                placeholder="Especificar" v-model="formCrear.accidentes_trabajo.especificar_accidente">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>* Fecha:</label>
                                <input autocomplete="off" class="form-control form-control-sm b-requerido accidentes_trabajo" type="date" id="fecha_accidente" placeholder="Fecha de Calificacion Accidente" v-model="formCrear.accidentes_trabajo.fecha_accidente">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>* Observaciones:</label>
                                <textarea id="inputDescription" class="form-control b-requerido accidentes_trabajo" rows="4"
                            v-model="formCrear.accidentes_trabajo.observaciones_accidente"
                            placeholder="Observaciones calificacion por Accidente"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button disabled="disabled" class="btn btn-info  btn-sm" style="display: none;" v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Guardando
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal" v-show="!cargando">
                    Cerrar
                </button>
                <button class="btn btn-outline-info btn-sm" v-on:click="guardarAntecedenteAccidentesTrabajo()"><i
                        class="fa fa-save" v-show="!cargando"></i>&nbsp;Grabar</button>
            </div>
        </div>

    </div>
</div>


<div class="modal fade" id="modal-antecedentes-enfermedades-profesionales" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#E9ECEF;">
                <h5 style="font-size:15px;" class="modal-title" id="myModalLabel">
                    Antecedentes Enfermedades Profesionales de Trabajo
                </h5>
                <button type="button" class="close" data-dismiss="modal" id="cerrar_modal_antecedentes_enfermedades" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button disabled="disabled" class="btn btn-info  btn-sm" style="display: none;" v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Guardando
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal" v-show="!cargando">
                    Cerrar
                </button>
                <button class="btn btn-outline-info btn-sm" v-on:click="guardarAntecedentesEnfermedadesProfesionales()"><i
                        class="fa fa-save" v-show="!cargando"></i>&nbsp;Grabar</button>
            </div>
        </div>

    </div>
</div> --}}
