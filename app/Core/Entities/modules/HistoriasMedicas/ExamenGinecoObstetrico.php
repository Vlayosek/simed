<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class ExamenGinecoObstetrico extends Model
{
    protected $table = 'sc_historias_clinicas.examenes_gineco_obstetricos';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'identificacion',
        'tipo_examen',
        'realizo_examen',
        'tiempo',
        'resultado',
        'codigo',
    ];
}
