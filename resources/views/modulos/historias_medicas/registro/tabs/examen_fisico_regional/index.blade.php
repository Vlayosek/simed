<div class="tab-pane" id="examen_fisico_regional">
    <div id="main_examen_fisico_regional">
        <a class="btn btn-primary btn-sm" href="#modal-examen-fisico-regional" role="button" data-toggle="modal"  v-show="editar"  :disabled="!editar" 
            v-on:click="limpiarExamenFisicoRegional()" data-backdrop="static" data-keyboard="false"><i
                class="fa fa-plus"></i>&nbsp;
            Nuevo Registro</a>
        @include('modulos.historias_medicas.registro.tabs.examen_fisico_regional.modal')
        <br><br>
        <div class="table-responsive" style="width:100%!important">
            <table class="table table-bordered table-striped compact" id="dtExamenFisicoRegional"
                style="width:100%!important">
                <thead>
                    <tr>
                        <th>Descripcion</th>
                        {{-- <th>Detalle</th> --}}
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="{{ url('js/modules/historias_medicas/registro/vue_script_examen_fisico_regional.js?v=113') }}"></script>
