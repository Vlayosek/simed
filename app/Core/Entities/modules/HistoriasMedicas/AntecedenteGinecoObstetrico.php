<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class AntecedenteGinecoObstetrico extends Model
{
    protected $table = 'sc_historias_clinicas.antecedentes_gineco_obstetricos';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'identificacion',
        'menarquia',
        'ciclos',
        'menstruacion',
        'gestas',
        'partos',
        'cesareas',
        'abortos',
        'hijos_vivos',
        'hijos_muertos',
        'vida_sexual',
        'planificacion_familiar',
        'tipo_planificacion_familiar',
        'codigo',
    ];
}
