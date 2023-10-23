<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class AntecedenteMedico extends Model
{
    protected $table = 'sc_historias_clinicas.antecedentes_medicos';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'descripcion',
        'tipo',
        'identificacion',
        'codigo',
    ];
   
    
}
