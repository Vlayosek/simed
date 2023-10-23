<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;


use Illuminate\Database\Eloquent\Model;

class Denominacion extends Model
{
    protected $table = 'sc_distributivo_.denominaciones';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

}
