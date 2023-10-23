<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class CodigoHistoria extends Model
{
    protected $table = 'sc_historias_clinicas.codigos_historias';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'codigo',
        'anio',
        'incremental'
     
    ];
   
    
}
