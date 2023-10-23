<?php

use App\Http\Controllers\HistoriasMedicas\GenerarArchivoController;

ob_start();

require __DIR__ . '/modulos/adminC.php';
require __DIR__ . '/modulos/getData.php';
require __DIR__ . '/modulos/general.php';
require __DIR__ . '/modulos/historias_medicas.php';


Route::get('storage/{carpeta}/{carpeta2}/{archivo}/{local}', [GenerarArchivoController::class, 'descargar']);
