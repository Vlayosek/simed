<div class="tab-pane " id="paciente">
    <div id="main_paciente">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 btnTop">
                    @include('modulos.historias_medicas.registro.tabs.paciente.formulario')
                </div>


                <div class="col-md-12 btnTop" v-show="editar">
                    <br />
                    <button class="btn btn-outline-primary" v-on:click="guardarPaciente()"
                        :disabled="!editar">Actualizar Datos del
                        Paciente</button>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="{{ url('js/modules/historias_medicas/registro/vue_script_paciente.js?v=119') }}"></script>
