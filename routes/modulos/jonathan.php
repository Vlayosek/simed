<?php

use App\Http\Controllers\HistoriasMedicas\GinecoObstetricosController;
use App\Http\Controllers\HistoriasMedicas\ReproductivoMasculinoController;
use App\Http\Controllers\HistoriasMedicas\DiagnosticoController;
use App\Http\Controllers\HistoriasMedicas\AptitudMedicaController;
use App\Http\Controllers\HistoriasMedicas\RecomendacionController;
use App\Http\Controllers\HistoriasMedicas\ConstanteVitalController;
use App\Http\Controllers\HistoriasMedicas\MotivoReintegroController;
use App\Http\Controllers\HistoriasMedicas\AtencionMedicaController;
use Illuminate\Support\Facades\Route;

/* antecedentes y examenes gineco obstetricos */

Route::post('gineco_obstetrico/guardar', [GinecoObstetricosController::class, 'guardar']);
Route::post('gineco_obstetrico/editar', [GinecoObstetricosController::class, 'editar']);
Route::post('gineco_obstetrico/eliminar', [GinecoObstetricosController::class, 'eliminar']);
/* antecedentes y examenes reproductivos masculinos */
Route::post('reproductivo_masculino/guardar', [ReproductivoMasculinoController::class, 'guardar']);
Route::post('reproductivo_masculino/editar', [ReproductivoMasculinoController::class, 'editar']);
Route::post('reproductivo_masculino/eliminar', [ReproductivoMasculinoController::class, 'eliminar']);
/* diagnostico */
Route::post('diagnostico/guardar', [DiagnosticoController::class, 'guardar']);
Route::post('diagnostico/editar', [DiagnosticoController::class, 'editar']);
Route::post('diagnostico/eliminar', [DiagnosticoController::class, 'eliminar']);

/* aptitudes medicas */
Route::post('aptitud_medica/guardar', [AptitudMedicaController::class, 'guardar']);
Route::post('aptitud_medica/editar', [AptitudMedicaController::class, 'editar']);
Route::post('aptitud_medica/eliminar', [AptitudMedicaController::class, 'eliminar']);
/* recomendacion y tratamiento */
Route::post('recomendacion/guardar', [RecomendacionController::class, 'guardar']);
Route::post('recomendacion/editar', [RecomendacionController::class, 'editar']);
Route::post('recomendacion/eliminar', [RecomendacionController::class, 'eliminar']);
/* constantes vitales y antropometria */
Route::post('constantes_vitales/guardar', [ConstanteVitalController::class, 'guardar']);
Route::post('constantes_vitales/editar', [ConstanteVitalController::class, 'editar']);
Route::post('constantes_vitales/eliminar', [ConstanteVitalController::class, 'eliminar']);
/* motivos de reintegro */
Route::post('reintegro/guardar', [MotivoReintegroController::class, 'guardar']);
Route::post('reintegro/editar', [MotivoReintegroController::class, 'editar']);
Route::post('reintegro/eliminar', [MotivoReintegroController::class, 'eliminar']);

Route::post('consultas/exportar', [AtencionMedicaController::class, 'exportar']);
Route::post('consultas/exportarCertificado', [AtencionMedicaController::class, 'exportarCertificado']);
