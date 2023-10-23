<div class="row">

    <div class="col-md-4 campo_evaluacion  fecha_ingreso">
        <label>
            Fecha de ingreso al trabajo:</label>
        <input :disabled="!editar" type="date" class="form-control form-control-sm"
            v-model="formCrear.paciente.fecha_ingreso">
    </div>
    <div class="col-md-4 campo_evaluacion   fecha_salida">
        <label>Fecha de Salida:</label>
        <input :disabled="!editar || formCrear.paciente.fecha_salida == null" type="date"
            class="form-control form-control-sm" v-model="formCrear.paciente.fecha_salida">
    </div>
    <div class="col-md-4 campo_evaluacion fecha_reingreso">
        <label>Fecha de reingreso:</label>
        <input :disabled="!editar" type="date" class="form-control form-control-sm"
            v-model="formCrear.paciente.fecha_reingreso">
    </div>
    <div class="col-md-4 campo_evaluacion tiempo_meses">
        <label>Tiempo (meses):</label>
        <input disabled autocomplete="off" class="form-control form-control-sm b-requerido" type="text"
            id="tiempo_meses" placeholder="Tiempo (meses)" v-model="formCrear.paciente.tiempo_meses">
    </div>
    <div class="col-md-4 campo_evaluacion total_dias">
        <label>Tiempo (dias):</label>
        <input disabled autocomplete="off" class="form-control form-control-sm b-requerido" type="text"
            id="total_dias" placeholder="Tiempo (dias)" v-model="formCrear.paciente.total_dias">
    </div>
    <div class="col-md-4 campo_evaluacion edad">
        <label>Edad:</label>
        <input :disabled="!editar" type="text" class="form-control form-control-sm"
            v-model="formCrear.paciente.edad">
    </div>
    <div class="col-md-4 campo_evaluacion puesto_trabajo">
        <label>Puesto de Trabajo (CIUO):</label>
        {!! Form::select('ciuo_', [], null, [
            'class' => 'form-control form-control-sm',
            'id' => 'codigo_ciuo',
            'placeholder' => 'SELECCIONE UNA OPCION',
            'style' => 'width:100%',
            ':disabled' => '!editar',
        ]) !!}
        <input type="hidden" name="" :disabled="!editar" class="b-requerido" id="ciuo_"
            placeholder="Ciuo" v-model="formCrear.paciente.puesto_trabajo">
    </div>
    <div class="col-md-4 campo_evaluacion religion">
        <label>Religión:</label>
        <select :disabled="!editar" class=" form-control form-control-sm" placeholder="Religión"
            v-model="formCrear.paciente.religion">
            <option value="">SELECCIONE UNA OPCION
            </option>
            <option v-for="(value,index) in consultaDatosReligion" v-text="value" :value="value"
                :selected="value == formCrear.paciente.religion">
            </option>
        </select>
    </div>


    <div class="col-md-4 campo_evaluacion nombres">
        <label>Nombres:</label>
        <input :disabled="!editar" type="text" class="form-control form-control-sm"
            v-model="formCrear.paciente.nombres">
    </div>

    <div class="col-md-4 campo_evaluacion apellidos">
        <label>Apellidos:</label>
        <input :disabled="!editar" type="text" class="form-control form-control-sm"
            v-model="formCrear.paciente.apellidos">
    </div>

    <div class="col-md-4 campo_evaluacion cargo">
        <label>Cargo:</label>
        <input :disabled="!editar" type="text" class="form-control form-control-sm"
            v-model="formCrear.paciente.cargo">
    </div>



    {{-- NOMBRES SEPARADOS --}}
    <div class="col-md-4 campo_evaluacion primer_nombre">
        <label>Primer Nombre:</label>
        <input :disabled="!editar" type="text" class="form-control form-control-sm"
            v-model="formCrear.paciente.primer_nombre">
    </div>

    <div class="col-md-4 campo_evaluacion segundo_nombre">
        <label>Segundo Nombre:</label>
        <input :disabled="!editar" type="text" class="form-control form-control-sm"
            v-model="formCrear.paciente.segundo_nombre">
    </div>
    <div class="col-md-4 campo_evaluacion primer_apellido">
        <label>Primer Apellido:</label>
        <input :disabled="!editar" type="text" class="form-control form-control-sm"
            v-model="formCrear.paciente.primer_apellido">
    </div>

    <div class="col-md-4 campo_evaluacion segundo_apellido">
        <label>Segundo Apellido:</label>
        <input :disabled="!editar" type="text" class="form-control form-control-sm"
            v-model="formCrear.paciente.segundo_apellido">
    </div>

    {{-- FIN NOMBRES SEPARADOS --}}

    <div class="col-md-4 campo_evaluacion tipo_sangre">
        <label>Grupo Sangu&iacute;neo:</label>
        <select :disabled="!editar" class=" form-control form-control-sm" placeholder="Grupo Sanguineo"
            v-model="formCrear.paciente.tipo_sangre">
            <option value="">SELECCIONE UNA OPCION
            </option>
            <option v-for="(value,index) in consultaDatosTipoSangre" v-text="value" :value="value"
                :selected="value == formCrear.paciente.tipo_sangre">
            </option>
        </select>
    </div>
    <div class="col-md-4 campo_evaluacion orientacion_sexual">
        <label>Orientación Sexual:</label>
        <select :disabled="!editar" class=" form-control form-control-sm" placeholder="Orientación Sexual"
            v-model="formCrear.paciente.orientacion_sexual">
            <option value="">SELECCIONE UNA OPCION
            </option>
            <option v-for="(value,index) in consultaDatosOrientacionSexual" v-text="value" :value="value"
                :selected="value == formCrear.paciente
                    .orientacion_sexual">
            </option>
        </select>
    </div>

    <div class="col-md-4 campo_evaluacion genero">
        <label>G&eacute;nero:</label>
        <select :disabled="!editar" class="form-control form-control-sm" placeholder="Genero"
            v-model="formCrear.paciente.genero">
            <option value="">SELECCIONE UNA OPCION
            </option>
            <option v-for="(value,index) in consultaDatosGenero" v-text="value" :value="value"
                :selected="value == formCrear.paciente.genero">
            </option>
        </select>
    </div>

    <div class="col-md-4 campo_evaluacion area">
        <label>&Aacute;rea:</label>
        <input :disabled="!editar" type="text" class="form-control form-control-sm"
            v-model="formCrear.paciente.area">
    </div>
    <div class="col-md-4 campo_evaluacion lateralidad">
        <label>Lateralidad:</label>
        <select :disabled="!editar" class=" form-control form-control-sm " placeholder="Lateralidad"
            v-model="formCrear.paciente.lateralidad">
            <option value="">SELECCIONE UNA OPCION
            </option>
            <option v-for="(value,index) in consultaDatosLateralidad" v-text="value" :value="value"
                :selected="value == formCrear.paciente.lateralidad">
            </option>
        </select>
    </div>
    <div class="col-md-4 campo_evaluacion identidad_genero">
        <label>Identidad de Género:</label>
        <select :disabled="!editar" class=" form-control form-control-sm" placeholder="Identidad de Género"
            v-model="formCrear.paciente.identidad_genero">
            <option value="">SELECCIONE UNA OPCION
            </option>
            <option v-for="(value,index) in consultaDatosIdentidadGenero" v-text="value" :value="value"
                :selected="value == formCrear.paciente.identidad_genero">
            </option>
        </select>
    </div>


    <div class="col-md-12 campo_evaluacion actividad_relevante">
        <label v-show="formCrear.paciente.tipo_evaluacion == 'INGRESO'">Actividades relevantes al
            puesto de trabajo a
            ocupar:</label>
        <label v-show="formCrear.paciente.tipo_evaluacion == 'RETIRO'">Actividades:</label>
        <textarea v-show="formCrear.paciente.tipo_evaluacion == 'RETIRO' || formCrear.paciente.tipo_evaluacion == 'INGRESO'"
            :disabled="!editar" class="form-control form-control-sm form-control-t"
            v-model="formCrear.paciente.actividad_relevante" placeholder="Actividades"></textarea>

    </div>
    <div class="col-md-12 campo_evaluacion causa_salida">
        <label>Causa de salida:</label>
        <input :disabled="!editar" type="text" class="form-control form-control-sm"
            v-model="formCrear.paciente.causa_salida">
    </div>
    <div class="col-md-12 campo_evaluacion factores_riesgo" v-show="formCrear.paciente.tipo_evaluacion == 'RETIRO'">
        <label>Factores de Riesgo:</label>
        <textarea placeholder="Factores de Riesgo" class="form-control form-control-sm form-control-t"
            v-model="formCrear.paciente.factores_riesgo"></textarea>
    </div>
    <div class="col-md-12 campo_evaluacion motivo_consulta">
        <label>
            Motivo de la Consulta
        </label>
        <textarea placeholder="Motivo de Consulta" :disabled="!editar" class="form-control form-control-sm form-control-t"
            v-model="formCrear.paciente.motivo_consulta"></textarea>
    </div>

</div>
