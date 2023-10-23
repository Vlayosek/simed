<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class Recomendacion extends Model
{
    protected $table = 'sc_historias_clinicas.recomendaciones';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'identificacion',
        'codigo',
        'recomendacion',
    ];
}
