<?php

use App\Http\Controllers\HistoriasMedicas\AntecedentesAccidentesTrabajoController;
use App\Http\Controllers\HistoriasMedicas\AntecedentesEnfermedadesProfesionalesController;
use App\Http\Controllers\HistoriasMedicas\AntecedentesTrabajoController;
use App\Http\Controllers\HistoriasMedicas\EstiloVidaController;
use App\Http\Controllers\HistoriasMedicas\ExamenFisicoRegionalController;
use App\Http\Controllers\HistoriasMedicas\ExamenGeneralEspecificoController;
use App\Http\Controllers\HistoriasMedicas\HabitosToxicosController;
use App\Http\Controllers\HistoriasMedicas\EvaluacionMedicaRetiroController;
use Illuminate\Support\Facades\Route;

/**
 * * HABITOS
 */
Route::post('consultaComboHabitos', [HabitosToxicosController::class, 'consultaComboHabitos']);
Route::post('guardarHabitos', [HabitosToxicosController::class, 'guardarHabitos']);
Route::post('editarHabito', [HabitosToxicosController::class, 'editarHabito']);
Route::post('eliminarHabito', [HabitosToxicosController::class, 'eliminarHabito']);

/**
 * * ESTILO DE VIDA
 */
Route::post('consultaComboEstiloVida', [EstiloVidaController::class, 'consultaComboEstiloVida']);
Route::post('guardarEstiloVida', [EstiloVidaController::class, 'guardarEstiloVida']);
Route::post('editarEstiloVida', [EstiloVidaController::class, 'editarEstiloVida']);
Route::post('eliminarEstiloVida', [EstiloVidaController::class, 'eliminarEstiloVida']);

/**
 * * EXAMEN FISICO REGIONAL
 */
Route::post('consultaComboExamenFisicoRegional', [ExamenFisicoRegionalController::class, 'consultaComboExamenFisicoRegional']);
Route::post('guardarExamenFisicoRegional', [ExamenFisicoRegionalController::class, 'guardarExamenFisicoRegional']);
Route::post('editarExamenFisicoRegional', [ExamenFisicoRegionalController::class, 'editarExamenFisicoRegional']);
Route::post('eliminarExamenFisicoRegional', [ExamenFisicoRegionalController::class, 'eliminarExamenFisicoRegional']);

/**
 * *
 */
Route::post('consultaComboExamenGeneralEspecifico', [ExamenGeneralEspecificoController::class, 'consultaComboExamenGeneralEspecifico']);
Route::post('guardarExamenGeneralEspecifico', [ExamenGeneralEspecificoController::class, 'guardarExamenGeneralEspecifico']);
Route::post('editarExamenGeneralEspecifico', [ExamenGeneralEspecificoController::class, 'editarExamenGeneralEspecifico']);
Route::post('eliminarExamenGeneralEspecifico', [ExamenGeneralEspecificoController::class, 'eliminarExamenGeneralEspecifico']);

/**
 * * ANTECEDENTES DE TRABAJO
 */
Route::post('consultaCombosAntecedentesTrabajo', [AntecedentesTrabajoController::class, 'consultaCombosAntecedentesTrabajo']);
Route::post('guardarAntecedentesTrabajo', [AntecedentesTrabajoController::class, 'guardarAntecedentesTrabajo']);
Route::post('editarAntecedentesTrabajo', [AntecedentesTrabajoController::class, 'editarAntecedentesTrabajo']);
Route::post('eliminarAntecedentesTrabajo', [AntecedentesTrabajoController::class, 'eliminarAntecedentesTrabajo']);
Route::post('consultaCombosAreas', [AntecedentesTrabajoController::class, 'getAreas']);

Route::post('guardarAntecedenteAccidentesTrabajo', [AntecedentesAccidentesTrabajoController::class, 'guardarAntecedenteAccidentesTrabajo']);
Route::post('editarAntecedenteAccidentesTrabajo', [AntecedentesAccidentesTrabajoController::class, 'editarAntecedenteAccidentesTrabajo']);
Route::post('eliminarAntecedenteAccidentesTrabajo', [AntecedentesAccidentesTrabajoController::class, 'eliminarAntecedenteAccidentesTrabajo']);

Route::post('guardarAntecedentesEnfermedadesProfesionales', [AntecedentesEnfermedadesProfesionalesController::class, 'guardarAntecedentesEnfermedadesProfesionales']);
Route::post('editarAntecedentesEnfermedadesProfesionales', [AntecedentesEnfermedadesProfesionalesController::class, 'editarAntecedentesEnfermedadesProfesionales']);
Route::post('eliminarAntecedentesEnfermedadesProfesionales', [AntecedentesEnfermedadesProfesionalesController::class, 'eliminarAntecedentesEnfermedadesProfesionales']);

/**
 * * EVALUACION MEDICA DE RETIRO
 */

Route::post('guardarEvaluacionMedicaRetiro', [EvaluacionMedicaRetiroController::class, 'guardarEvaluacionMedicaRetiro']);
Route::post('editarEvaluacionMedicaRetiro', [EvaluacionMedicaRetiroController::class, 'editarEvaluacionMedicaRetiro']);
Route::post('eliminarEvaluacionMedicaRetiro', [EvaluacionMedicaRetiroController::class, 'eliminarEvaluacionMedicaRetiro']);
