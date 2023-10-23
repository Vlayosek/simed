<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class AntecedentesAccidentesTrabajo extends Model
{
    protected $table = 'sc_historias_clinicas.antecedentes_accidentes_trabajo';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'calificado_accidente',
        'especificar_accidente',
        'fecha_accidente',
        'observaciones_accidente',
        'identificacion',
        'estado',
        'codigo',
    ];

}
