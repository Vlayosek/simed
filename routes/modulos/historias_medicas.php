<?php

use App\Http\Controllers\HistoriasMedicas\ActividadesExtrasEnfermedadesActualesController;
use App\Http\Controllers\HistoriasMedicas\AntecedentesFamiliaresController;
use App\Http\Controllers\HistoriasMedicas\AntecedentesMedicosController;
use App\Http\Controllers\HistoriasMedicas\AtencionMedicaController;
use App\Http\Controllers\HistoriasMedicas\DiagnosticoController;
use App\Http\Controllers\HistoriasMedicas\FactoresRiesgosPuestosController;
use App\Http\Controllers\HistoriasMedicas\PacienteController;
use App\Http\Controllers\HistoriasMedicas\RegistroController;
use App\Http\Controllers\HistoriasMedicas\RevisionOrganosController;
use App\Http\Controllers\HistoriasMedicas\SeccionController;
use App\Http\Controllers\TalentoHumano\RepositorioController;
use Illuminate\Support\Facades\Route;

Route::controller(SeccionController::class)
    ->prefix('seccion')
    ->as('seccion.')
    ->middleware(['auth', 'role:DOCTOR'])
    ->group(function () {
        Route::get('index', [SeccionController::class, 'index']);
        Route::post('editarRegistro', [SeccionController::class, 'editarRegistro']);
        Route::post('guardarSeccion', [SeccionController::class, 'guardarSeccion']);
        Route::post('eliminarRegistro', [SeccionController::class, 'eliminarRegistro']);
    });

Route::controller(RegistroController::class)
    ->prefix('historias')
    ->as('historias.')
    ->middleware(['auth', 'role:DOCTOR'])
    ->group(function () {
        Route::get('registro', [RegistroController::class, 'registro']);
        Route::get('editar/{id}', [RegistroController::class, 'editar']);
        Route::post('eliminarHistoria', [RegistroController::class, 'eliminarHistoria']);
        Route::post('editarRegistro', [RegistroController::class, 'editarRegistro']);

        Route::get('consultas', [RegistroController::class, 'consultas'])->name('consultas');
        Route::post('buscarFuncionario', [RegistroController::class, 'buscarFuncionario']);
        Route::post('buscarSeccionesTiposEvaluaciones', [RegistroController::class, 'buscarSeccionesTiposEvaluaciones']);

        Route::post('consultaCombosRegistro', [RegistroController::class, 'consultaCombosRegistro']);

        Route::post('eliminarAntecedentes', [AntecedentesMedicosController::class, 'eliminarAntecedentes']);
        Route::post('guardarDiscapacidad', [RepositorioController::class, 'guardarDiscapacidad']);
        Route::post('editarPersonaDiscapacidad', [RepositorioController::class, 'editarPersonaDiscapacidad']);
        Route::post('eliminarPersonaDiscapacidad', [RepositorioController::class, 'eliminarPersonaDiscapacidad']);
        Route::post('descargarPdf64', [RepositorioController::class, 'descargarPdf64']);

        /* Route::get('registro/{tab}', [RegistroController::class, 'tabopcion'])->name('registro');
    Route::post('registro/buscarFuncionario', [RegistroController::class, 'buscarFuncionario']); */

        /*CONSULTA DE COMBOS DE SELECCIÓN */
        Route::post('registro/consultaCombosRegistro', [RegistroController::class, 'consultaCombosRegistro']);
        /*CONSULTA DE COMBOS DE SELECCIÓN */
        Route::post('guardarAntecedentesPersonalesQuirurgicos', [AntecedentesMedicosController::class, 'guardarAntecedentesPersonalesQuirurgicos']);

        /* ANTECEDENTES FAMILIARES */
        Route::post('consultaCombosRegistroAntecedenteFamiliar', [AntecedentesFamiliaresController::class, 'consultaCombosRegistroAntecedenteFamiliar']);
        Route::post('guardarAntecedenteFamiliar', [AntecedentesFamiliaresController::class, 'guardarAntecedenteFamiliar']);
        Route::post('editarAntecedenteFamiliar', [AntecedentesFamiliaresController::class, 'editarAntecedenteFamiliar']);
        Route::post('eliminarAntecedenteFamiliar', [AntecedentesFamiliaresController::class, 'eliminarAntecedenteFamiliar']);
        /* ANTECEDENTES FAMILIARES */

        /* FACTORES DE RIEGOS DE TRABAJO ACTUAL */
        Route::post('consultaCombosRegistroFactoresRiegososPuestos', [FactoresRiesgosPuestosController::class, 'consultaCombosRegistroFactoresRiegososPuestos']);
        Route::post('guardarFactoresRiesgosos', [FactoresRiesgosPuestosController::class, 'guardarFactoresRiesgosos']);
        Route::post('editarFactorRiesgoLaboral', [FactoresRiesgosPuestosController::class, 'editarFactorRiesgoLaboral']);
        Route::post('eliminarFactorRiesgoLaboral', [FactoresRiesgosPuestosController::class, 'eliminarFactorRiesgoLaboral']);

        /* FACTORES DE RIEGOS DE TRABAJO ACTUAL */

        /* REVISION DE ORGANOS */
        Route::post('consultaCombosRegistroRevisionOrganos', [RevisionOrganosController::class, 'consultaCombosRegistroRevisionOrganos']);
        Route::post('guardarRevisionOrganos', [RevisionOrganosController::class, 'guardarRevisionOrganos']);
        Route::post('editarRevisionOrganos', [RevisionOrganosController::class, 'editarRevisionOrganos']);
        Route::post('eliminarRevisionOrganos', [RevisionOrganosController::class, 'eliminarRevisionOrganos']);

        /* REVISION DE ORGANOS */

        /* ACFTIVIDADES EXTRAS Y ENFERMEDADES ACTUALES */
        Route::post('guardarActividadExtraEnfermedadActual', [ActividadesExtrasEnfermedadesActualesController::class, 'guardarActividadExtraEnfermedadActual']);
        Route::post('eliminarActividadExtraEnfermedadActual', [ActividadesExtrasEnfermedadesActualesController::class, 'eliminarActividadExtraEnfermedadActual']);

        /* REVISION DE ORGANOS */
        /* PACIENTE */

        Route::post('guardarPaciente', [PacienteController::class, 'guardarPaciente']);
        Route::post('guardarAtencionMedica', [AtencionMedicaController::class, 'guardarAtencionMedica']);
        Route::post('getCargaDatosCiuo', [PacienteController::class, 'getCargaDatosCiuo'])->name('getCargaDatosCiuo');
        Route::post('getCargaDatosCiiu', [AtencionMedicaController::class, 'getCargaDatosCiiu'])->name('getCargaDatosCiiu');

        require __DIR__ . '/consultasDatatable.php';
        require __DIR__ . '/jonathan.php';
        require __DIR__ . '/vladimir.php';
    });
Route::post('getCargaDatosCie', [DiagnosticoController::class, 'getCargaDatosCie'])->name('getCargaDatosCie');
Route::post('getCargarDatosFuncionario', [RegistroController::class, 'getCargarDatosFuncionario'])->name('getCargarDatosFuncionario');
