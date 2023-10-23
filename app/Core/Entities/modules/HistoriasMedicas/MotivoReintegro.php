<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class MotivoReintegro extends Model
{
    protected $table = 'sc_historias_clinicas.motivos_reintegros';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'identificacion',
        'codigo',
        'descripcion',
    ];
}
