<div class="tab-pane" id="revision_organos">
    <div id="main_revision_organos">
        <a class="btn btn-primary btn-sm" href="#modal-container-revision_organos" role="button" class="btn"
            data-toggle="modal"  v-show="editar"  :disabled="!editar"  v-on:click="limpiarRevisionOrganos()"> Nuevo Registro</a>
        @include('modulos/historias_medicas/registro/tabs/revision_organos/modal')
        <div class="col-md-12">
            <br />
            <div class="row">
                <div class="table-responsive" style="width:100%!important">
                    <table class="table table-bordered table-striped compact" id="dt_revision_organos"
                        style="width:100%!important">
                        <thead>
                            <tr>
                                <th>Descripcion</th>
                                <th>Detalle</th>
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
<script src="{{ url('js/modules/historias_medicas/registro/vue_script_revision_organos.js?v=113') }}"></script>
