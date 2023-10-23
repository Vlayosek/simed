<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class AntecedenteReproductivoMasculino extends Model
{
    protected $table = 'sc_historias_clinicas.antecedentes_reproductivos_masculinos';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'identificacion',
        'planificacion_familiar',
        'tipo_planificacion_familiar',
        'hijos_vivos',
        'hijos_muertos',
        'codigo',
    ];
}
