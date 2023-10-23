<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class Tipo_evaluacion extends Model
{
    protected $table = 'sc_historias_clinicas.tipos_evaluaciones';
    protected $connection = 'pgsql';
    protected $primaryKey = 'id';

    protected $fillable = [
        'descripcion',
        'seccion',
        'campos',
    ];
    public $timestamps = false;

}
