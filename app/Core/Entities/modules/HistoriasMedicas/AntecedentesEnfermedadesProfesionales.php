<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class AntecedentesEnfermedadesProfesionales extends Model
{
    protected $table = 'sc_historias_clinicas.antecedentes_enfermedades_profesionales';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'calificado_enfermedad',
        'especificar_enfermedad',
        'fecha_enfermedad',
        'observaciones_enfermedad',
        'identificacion',
        'estado',
        'codigo',
    ];

}
