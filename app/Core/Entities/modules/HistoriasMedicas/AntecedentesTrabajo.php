<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class AntecedentesTrabajo extends Model
{
    protected $table = 'sc_historias_clinicas.antecedentes_trabajo';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'descripcion',
        'identificacion',
        'empresa',
        'puesto_trabajo',
        'actividades_desempenadas',
        'tiempo_trabajo',
        'observaciones',
        'estado',
        'codigo'
     
    ];
   
    
}
