<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;


use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'sc_distributivo_.cargos';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

}
