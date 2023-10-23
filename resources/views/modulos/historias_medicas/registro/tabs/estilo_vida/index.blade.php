<div class="tab-pane" id="estilo_vida">
    <div id="main_estilo_vida">
        <a class="btn btn-primary btn-sm" href="#modal-estilo-vida" role="button" data-toggle="modal"  v-show="editar"  :disabled="!editar" 
            v-on:click="limpiarEstiloVida()" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i>&nbsp;
            Nuevo Registro</a>
        @include('modulos.historias_medicas.registro.tabs.estilo_vida.modal')
        <br><br>
        <div class="table-responsive" style="width:100%!important">
            <table class="table table-bordered table-striped compact" id="dtEstiloVida" style="width:100%!important">
                <thead>
                    <tr>
                        <th>Consumos Nocivos</th>
                        <th>Si/No</th>
                        <th>¿Cu&aacute;l?</th>
                        <th>Tiempo / Cantidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="{{ url('js/modules/historias_medicas/registro/vue_script_estilo_vida.js?v=116') }}"></script>
