<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class ExamenGeneralEspecifico extends Model
{
    protected $table = 'sc_historias_clinicas.examenes_generales_especificos';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'descripcion',
        'fecha',
        'resultados',
        'identificacion',
        'estado',
        'codigo',
        'imagen',
    ];
}
