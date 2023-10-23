<div class="modal fade" id="modal-container-filtro" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">
                    Filtro por funcionario
                </h5>
                <button type="button" class="close" data-dismiss="modal" id="cerrar_filtrado">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    {!! Form::select('paciente_id', [], null, [
                        'class' => 'form-control form-control-sm',
                        'id' => 'paciente_id',
                        'placeholder' => 'SELECCIONE UNA OPCION',
                        'style' => 'width:100%',
                    ]) !!}

                </div>
            </div>
            <div class="modal-footer">

                <button disabled="disabled" class="btn btn-info  btn-sm" style="display: none;"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Cargando
                </button>

                <button class="btn btn-outline-info btn-sm" v-on:click="filtrarDatos()"><i
                        class="fa fa-tasks"></i>&nbsp;Filtrar Datos</button>

            </div>
        </div>

    </div>

</div>
