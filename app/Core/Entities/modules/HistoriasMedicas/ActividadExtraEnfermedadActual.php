<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class ActividadExtraEnfermedadActual extends Model
{
    protected $table = 'sc_historias_clinicas.actividades_extras_enfermedades_actuales';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'descripcion',
        'tipo',
        'identificacion',
        'codigo'
    ];
   
    
}