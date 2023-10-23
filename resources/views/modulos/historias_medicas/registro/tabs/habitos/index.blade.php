<div class="tab-pane" id="habitos_toxicos">
    <div id="main_habitos">
        <a class="btn btn-primary btn-sm" href="#modal-habitos" role="button" data-toggle="modal"  v-show="editar"  :disabled="!editar" 
            v-on:click="limpiarHabitos()" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i>&nbsp;
            Nuevo Registro</a>
        @include('modulos.historias_medicas.registro.tabs.habitos.modal')
        <br><br>
        <div class="table-responsive" style="width:100%!important">
            <table class="table table-bordered table-striped compact" id="dtHabitos" style="width:100%!important">
                <thead>
                    <tr>
                        <th>Consumos Nocivos</th>
                        <th>Si/No</th>
                        <th>Tiempo de Consumo</th>
                        <th>Cantidad</th>
                        <th>Ex Consumidor</th>
                        <th>Tiempo de Abstinencia</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="{{ url('js/modules/historias_medicas/registro/vue_script_habitos_toxicos.js?v=113') }}"></script>
