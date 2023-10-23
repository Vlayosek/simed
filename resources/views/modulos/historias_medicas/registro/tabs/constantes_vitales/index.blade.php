<div class="tab-pane" id="constantes_vitales" v-show="consultaDatos">
    <div id="main_constantes_vitales">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 hidden" style="background:#E9ECEF;text-align:center">
                    <label>CONSTANTES VITALES Y ANTROPOMETR&Iacute;A</label>
                </div>
                <div class="col-md-12"><br>
                    <button type="button" class="btn btn-primary btnTop btn-sm" data-toggle="modal"  v-show="editar"  :disabled="!editar"  data-target="#modal-constantes_vitales" v-on:click="limpiarConstanteVital()" data-backdrop="static" data-keyboard="false">
                        <i class="fa fa-plus"></i>&nbsp; Nuevo
                    </button>
                </div>
                <div class="col-md-12"><br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table" id="tablaConsulta">
                                <table class="table table-bordered table-striped" id="dt_constantes_vitales" style="width:100%!important">
                                    <thead>
                                        <th>Presi&oacute;n Arterial<br>(mmHg)</th>
                                        <th>Temperatura<br>(°C)</th>
                                        <th>Frecuencia Cardiaca<br>(Lat/min)</th>
                                        <th>Saturaci&oacute;n de ox&iacute;geno<br>(02%)</th>
                                        <th>Frecuencia Respiratoria<br>(fr/min)</th>
                                        <th>Peso<br>(Kg)</th>
                                        <th>Talla<br>(cm)</th>
                                        <th>&Iacute;ndice de masa corporal<br>(kg/m2)</th>
                                        <th>Per&iacute;metro abdominal<br>(cm)</th>
                                        <th>Acciones</th>
                                    </thead>
                                    <tbody id="tbobymenu" class="menu-pen">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('modulos.historias_medicas.registro.tabs.constantes_vitales.modal_constantes')
        </div>
    </div>
</div>
<script src="{{ url('js/modules/historias_medicas/registro/vue_script_constantes_vitales.js?v=113') }}"></script>