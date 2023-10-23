<div class="tab-pane" id="antecedentes_familiares">
    <div id="main_antecedentes_familiares">
        <a class="btn btn-primary btn-sm" href="#modal-container-antecedentes_familiares" role="button" class="btn"
            data-toggle="modal"  v-show="editar"  :disabled="!editar"  v-on:click="limpiarAntecedenteFamiliar()"> Nuevo Registro</a>
        @include('modulos/historias_medicas/registro/tabs/antecedentes_familiares/modal')
        <div class="col-md-12">
            <br />
            <div class="row">
                <div class="table-responsive" style="width:100%!important">
                    <table class="table table-bordered table-striped compact" id="dt_antecedentes_familiares"
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
<script src="{{ url('js/modules/historias_medicas/registro/vue_script_antecedentes_familiares.js?v=115') }}"></script>
