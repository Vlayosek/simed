<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $table = 'sc_distributivo_.provincias';
    protected $connection = 'pgsql_presidencia';
}
