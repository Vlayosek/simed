<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'sc_distributivo_.logs';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
