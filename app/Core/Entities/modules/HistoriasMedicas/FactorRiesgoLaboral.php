<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class FactorRiesgoLaboral extends Model
{
    protected $table = 'sc_historias_clinicas.factores_riesgos_laborales';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'descripcion',
        'identificacion',
        'codigo',
    ];
   
    
}
