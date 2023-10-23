<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;


use Illuminate\Database\Eloquent\Model;

class Tipo_contrato extends Model
{
    protected $table = 'sc_distributivo_.tipos_contratos';
    protected $connection = 'pgsql_presidencia';
}
