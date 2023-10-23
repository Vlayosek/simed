<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class AptitudMedica extends Model
{
    protected $table = 'sc_historias_clinicas.aptitudes_medicas';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'identificacion',
        'codigo',
        'aptitud',
        'observacion',
        'limitacion',
    ];
}
