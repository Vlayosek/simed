<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class AntecedenteFamiliar extends Model
{
    protected $table = 'sc_historias_clinicas.antecedentes_familiares';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'descripcion',
        'detalle',
        'identificacion',
        'codigo'
    ];
   
    
}