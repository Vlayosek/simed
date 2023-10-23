<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class EstiloVida extends Model
{
    protected $table = 'sc_historias_clinicas.estilo_vida';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'descripcion',
        'valor',
        'identificacion',
        'tipo_estilo_vida',
        'tiempo_cantidad',
        'estado',
        'codigo',
    ];
}
