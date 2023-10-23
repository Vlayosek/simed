<div class="tab-pane" id="evaluacion_medica_retiro">
    <div id="main_evaluacion_medica_retiro">
        <a class="btn btn-primary btn-sm" href="#modal-evaluacion-medica-retiro" role="button" data-toggle="modal"
            v-show="editar" :disabled="!editar" v-on:click="limpiarEvaluacionMedicaRetiro()" data-backdrop="static"
            data-keyboard="false"><i class="fa fa-plus"></i>&nbsp;
            Nuevo Registro</a>
        @include('modulos.historias_medicas.registro.tabs.evaluacion_medica_retiro.modal')
        <br><br>
        <div class="table-responsive" style="width:100%!important">
            <table class="table table-bordered table-striped compact" id="dtEvaluacionMedicaRetiro"
                style="width:100%!important">
                <thead>
                    <tr>
                        <th>Evaluacion Realizada</th>
                        <th>Observaciones</th>
                        <th>Condici&oacute;n Dx</th>
                        <th>Condici&oacute;n Relacionada</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="{{ url('js/modules/historias_medicas/registro/vue_script_evaluacion_medica_retiro.js?v=10') }}"></script>
