<div class="tab-pane" id="examen_general_especifico">
    <div id="main_examen_general_especifico">
        <a class="btn btn-primary btn-sm" href="#modal-examen-general-especifico" role="button" data-toggle="modal"  v-show="editar"  :disabled="!editar" 
            v-on:click="limpiarExamenGeneralEspecifico()" data-backdrop="static" data-keyboard="false"><i
                class="fa fa-plus"></i>&nbsp;
            Nuevo Registro</a>
        @include('modulos.historias_medicas.registro.tabs.examenes_generales.modal')
        <br><br>
        <div class="table-responsive" style="width:100%!important">
            <table class="table table-bordered table-striped compact" id="dtExamenGeneralEspecifico"
                style="width:100%!important">
                <thead>
                    <tr>
                        <th>Descripcion</th>
                        <th>Fecha</th>
                        <th>Resultados</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="{{ url('js/modules/historias_medicas/registro/vue_script_examen_general_especifico.js?v=113') }}"></script>
