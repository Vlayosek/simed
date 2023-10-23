<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class Discapacidad extends Model
{
    protected $table = 'sc_historias_clinicas.discapacidades';
    protected $connection = 'pgsql';
    protected $primaryKey = 'id';

    protected $fillable = [
        'persona_id',
        'nombre',
        'numero_carnet',
        'porcentaje',
        'codigo',
        'imagen'
    ];
    public $timestamps = false;
}
