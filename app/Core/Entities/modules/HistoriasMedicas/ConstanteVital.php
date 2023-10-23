<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class ConstanteVital extends Model
{
    protected $table = 'sc_historias_clinicas.constantes_vitales';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'identificacion',
        'codigo',
        'presion_arterial',
        'temperatura',
        'frecuencia_cardiaca',
        'saturacion_oxigeno',
        'frecuencia_respiratoria',
        'peso',
        'talla',
        'indice_masa_corporal',
        'perimetro_abdominal',
    ];
}
