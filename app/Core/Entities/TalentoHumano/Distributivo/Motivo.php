<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;


use Illuminate\Database\Eloquent\Model;

class Motivo extends Model
{
    protected $table = 'sc_distributivo_.motivos';
    protected $connection = 'pgsql_presidencia';
}
