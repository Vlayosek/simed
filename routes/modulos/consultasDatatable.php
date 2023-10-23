<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HistoriasMedicas\ActividadesExtrasEnfermedadesActualesController;
use App\Http\Controllers\HistoriasMedicas\AntecedentesAccidentesTrabajoController;
use App\Http\Controllers\HistoriasMedicas\AntecedentesEnfermedadesProfesionalesController;
use App\Http\Controllers\HistoriasMedicas\AntecedentesFamiliaresController;
use App\Http\Controllers\HistoriasMedicas\AntecedentesMedicosController;
use App\Http\Controllers\HistoriasMedicas\AntecedentesTrabajoController;
use App\Http\Controllers\HistoriasMedicas\AptitudMedicaController;
use App\Http\Controllers\HistoriasMedicas\AtencionMedicaController;
use App\Http\Controllers\HistoriasMedicas\ConstanteVitalController;
use App\Http\Controllers\HistoriasMedicas\DiagnosticoController;
use App\Http\Controllers\HistoriasMedicas\EstiloVidaController;
use App\Http\Controllers\HistoriasMedicas\EvaluacionMedicaRetiroController;
use App\Http\Controllers\HistoriasMedicas\ExamenFisicoRegionalController;
use App\Http\Controllers\HistoriasMedicas\ExamenGeneralEspecificoController;
use App\Http\Controllers\HistoriasMedicas\FactoresRiesgosPuestosController;
use App\Http\Controllers\HistoriasMedicas\GinecoObstetricosController;
use App\Http\Controllers\HistoriasMedicas\HabitosToxicosController;
use App\Http\Controllers\HistoriasMedicas\MotivoReintegroController;
use App\Http\Controllers\HistoriasMedicas\RecomendacionController;
use App\Http\Controllers\HistoriasMedicas\ReproductivoMasculinoController;
use App\Http\Controllers\HistoriasMedicas\RevisionOrganosController;
use App\Http\Controllers\HistoriasMedicas\SeccionController;
use App\Http\Controllers\TalentoHumano\RepositorioController;


Route::controller(RepositorioController::class)
    ->prefix('consultaDatosBase')
    ->as('consultaDatosBase.')
    ->group(function () {

        Route::get('/seccion/datatableSecciones/', [SeccionController::class, 'datatableSecciones']);
        Route::get('/datatablAntecedentesQuirurgicos/{identificacion}/{id}', [AntecedentesMedicosController::class, 'datatablAntecedentesQuirurgicos']);
        Route::get('/datatablAntecedentesPersonales/{identificacion}/{id}', [AntecedentesMedicosController::class, 'datatablAntecedentesPersonales']);
        Route::get('/datatableDiscapacidad/{identificacion}/{id}', [RepositorioController::class, 'datatableDiscapacidad']);
        Route::get('/datatableHabitos/{identificacion}/{id}', [HabitosToxicosController::class, 'datatableHabitos']);
        Route::get('/datatableEstiloVida/{identificacion}/{id}', [EstiloVidaController::class, 'datatableEstiloVida']);
        Route::get('/datatableExamenFisicoRegional/{identificacion}/{id}', [ExamenFisicoRegionalController::class, 'datatableExamenFisicoRegional']);
        Route::get('/datatableExamenGeneralEspecifico/{identificacion}/{id}', [ExamenGeneralEspecificoController::class, 'datatableExamenGeneralEspecifico']);
        Route::get('/datatableAntecedentesFamiliar/{identificacion}/{id}', [AntecedentesFamiliaresController::class, 'datatableAntecedentesFamiliar']);
        Route::get('/datatableFactoresRiesgosos/{identificacion}/{id}', [FactoresRiesgosPuestosController::class, 'datatableFactoresRiesgosos']);
        Route::get('/cargarDatatableRevisionOrganos/{identificacion}/{id}', [RevisionOrganosController::class, 'cargarDatatableRevisionOrganos']);
        Route::get('/cargarDatatableExtraLaborales/{identificacion}/{id}', [ActividadesExtrasEnfermedadesActualesController::class, 'cargarDatatableExtraLaborales']);
        Route::get('/cargarDatatableEnfermedadActual/{identificacion}/{id}', [ActividadesExtrasEnfermedadesActualesController::class, 'cargarDatatableEnfermedadActual']);



        Route::get('/datatableAntecedentesTrabajo/{identificacion}/{id}', [AntecedentesTrabajoController::class, 'datatableAntecedentesTrabajo']);
        Route::get('/datatableAntecedentesAccidentesTrabajo/{identificacion}/{id}', [AntecedentesAccidentesTrabajoController::class, 'datatableAntecedentesAccidentesTrabajo']);
        Route::get('/datatableAntecedentesEnfermedadesProfesionales/{identificacion}/{id}', [AntecedentesEnfermedadesProfesionalesController::class, 'datatableAntecedentesEnfermedadesProfesionales']);
        Route::get('/datatableEvaluacionMedicaRetiro/{identificacion}/{id}', [EvaluacionMedicaRetiroController::class, 'datatableEvaluacionMedicaRetiro']);

        Route::get('/datatableAntecedentesGineco/{identificacion}/{id}', [GinecoObstetricosController::class, 'datatableAntecedentesGineco']);
        Route::get('/datatableExamenesGineco/{identificacion}/{id}', [GinecoObstetricosController::class, 'datatableExamenesGineco']);
        Route::get('/datatableAntecedentesReproductivosMasculinos/{identificacion}/{id}', [ReproductivoMasculinoController::class, 'datatableAntecedentesReproductivosMasculinos']);
        Route::get('/datatableExamenesReproductivosMasculinos/{identificacion}/{id}', [ReproductivoMasculinoController::class, 'datatableExamenesReproductivosMasculinos']);
        Route::get('/datatableDiagnosticos/{identificacion}/{id}', [DiagnosticoController::class, 'datatableDiagnosticos']);
        Route::get('/datatableAptitudesMedicas/{identificacion}/{id}', [AptitudMedicaController::class, 'datatableAptitudesMedicas']);
        Route::get('/datatableRecomendaciones/{identificacion}/{id}', [RecomendacionController::class, 'datatableRecomendaciones']);
        Route::get('/datatableConsultaAtenciones/{fecha_inicio}/{fecha_fin}/{paciente_id}', [AtencionMedicaController::class, 'datatableConsultaAtenciones']);

        Route::get('/datatableConstantesVitales/{identificacion}/{id}', [ConstanteVitalController::class, 'datatableConstantesVitales']);
        Route::get('/datatableMotivosReintegros/{identificacion}/{id}', [MotivoReintegroController::class, 'datatableMotivosReintegros']);
    });
