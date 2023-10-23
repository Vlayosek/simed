<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;


use Illuminate\Database\Eloquent\Model;

class Filtro extends Model
{
    protected $table = 'sc_distributivo_.filtros';
    protected $connection = 'pgsql_presidencia';
}
