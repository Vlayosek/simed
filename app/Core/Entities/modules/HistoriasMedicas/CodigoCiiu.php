<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class CodigoCiiu extends Model
{
    protected $table = 'sc_historias_clinicas.codigos_ciiu';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
}
