<div class="tab-pane" id="discapacidad">
    <div id="main_discapacidad">
        <a class="btn btn-primary btn-sm" href="#modal-container-discapacidad" role="button" data-toggle="modal"
            v-show="editar" :disabled="!editar" v-on:click="limpiarDiscapacidad()"> Nuevo Registro</a>
        @include('modulos.historias_medicas.registro.tabs.discapacidad.modal')
        <div class="col-md-12">
            <br />
            <div class="row">
                <div class="table-responsive" style="width:100%!important">
                    <table class="table table-bordered table-striped compact" id="dtDiscapacidad"
                        style="width:100%!important">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Porcentaje</th>
                                <th>Carnet</th>
                                <th>Acciones</th>
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
<script src="{{ url('js/modules/historias_medicas/registro/vue_script_discapacidad.js?v=114') }}"></script>
{{-- <script src="{{ asset('adminlte3/plugins/bs-custom-file-input/bs-custom-file-input.js') }}"></script> --}}
