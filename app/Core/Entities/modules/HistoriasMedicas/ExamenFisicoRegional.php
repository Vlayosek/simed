<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class ExamenFisicoRegional extends Model
{
    protected $table = 'sc_historias_clinicas.examenes_fisicos_regionales';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'detalles',
        'descripcion',
        'identificacion',
        'estado',
        'codigo',
    ];
}
