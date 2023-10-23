<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class EvaluacionMedicaRetiro extends Model
{
    protected $table = 'sc_historias_clinicas.evaluacion_medica_retiro';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'identificacion',
        'codigo',
        'evaluacion_realizada',
        'observaciones',
        'condicion_diagnostico',
        'salud_relacionada'
    ];
}
