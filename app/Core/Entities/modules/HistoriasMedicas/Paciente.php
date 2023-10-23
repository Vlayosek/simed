<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'sc_historias_clinicas.pacientes';
    protected $connection = 'pgsql';
    protected $primaryKey = 'id';

    protected $fillable = [
        'identificacion',
        'area',
        'cargo',
        'fecha_ingreso',
        'apellidos',
        'nombres',
        'genero',
        'edad',
        'religion',
        'tipo_sangre',
        'lateralidad',
        'orientacion_sexual',
        'identidad_genero',
        'codigo',
        'actividad_relevante',
        'puesto_trabajo_ciuo',
        'fecha_salida',
        'tiempo_meses',
        'causa_salida',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'motivo_consulta'
    ];
    public $timestamps = false;
}
