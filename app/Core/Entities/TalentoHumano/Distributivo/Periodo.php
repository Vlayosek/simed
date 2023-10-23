<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;


use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    protected $table = 'sc_distributivo_.periodos';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

}
