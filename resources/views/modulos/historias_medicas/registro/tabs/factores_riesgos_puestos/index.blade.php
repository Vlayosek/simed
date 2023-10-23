<div class="tab-pane" id="factores_riesgos_puesto">
    <div id="main_factores_riesgos_puesto">
        <a class="btn btn-primary btn-sm" href="#modal-container-factores_riesgos_puesto" role="button" class="btn"
            data-toggle="modal"  v-show="editar"  :disabled="!editar"  v-on:click="limpiarFactoresRiegosos()"> Nuevo Registro</a>
        @include('modulos/historias_medicas/registro/tabs/factores_riesgos_puestos/modal')
        <div class="col-md-12">
            <br />
            <div class="row">
                <div class="table-responsive" style="width:100%!important">
                    <table class="table table-bordered table-striped compact" id="dt_factores_riesgosos"
                        style="width:100%!important">
                        <thead>
                            <tr>
                                <th>Puesto de Trabajo</th>
                                <th>Actividades</th>
                                <th>Descripcion</th>
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
<script src="{{ url('js/modules/historias_medicas/registro/vue_script_factores_riesgos_puestos.js?v=113') }}"></script>
