<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class Diagnostico extends Model
{
    protected $table = 'sc_historias_clinicas.diagnosticos';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'identificacion',
        'descripcion',
        'codigo_cie_id',
        'tipo',
        'codigo',
    ];
}
