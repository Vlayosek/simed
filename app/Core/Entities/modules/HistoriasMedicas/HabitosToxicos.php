<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class HabitosToxicos extends Model
{
    protected $table = 'sc_historias_clinicas.habitos_toxicos';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'descripcion',
        'valor',
        'identificacion',
        'tiempo_consumo',
        'cantidad',
        'ex_consumidor',
        'tiempo_abstinencia',
        'estado',
        'codigo',
    ];
}
