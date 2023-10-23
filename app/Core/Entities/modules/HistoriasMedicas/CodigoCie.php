<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class CodigoCie extends Model
{
    protected $table = 'sc_historias_clinicas.codigos_cie';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
}
