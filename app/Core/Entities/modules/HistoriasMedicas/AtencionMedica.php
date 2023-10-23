<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class AtencionMedica extends Model
{
    protected $table = 'sc_historias_clinicas.atenciones_medicas';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'codigo',
        'historia_clinica',
        'numero_archivo',
        'institucion',
        'ruc',
        'codigo_ciiu',
        'identificacion',
        'motivo_consulta',
        'tipo_evaluacion',
        'establecimiento_salud',

    ];

}
