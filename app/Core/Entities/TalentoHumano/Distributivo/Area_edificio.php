<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;


use Illuminate\Database\Eloquent\Model;

class Area_edificio extends Model
{
    protected $table = 'sc_distributivo_.areas_edificios';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

    public function area()
    {
        return $this->hasOne('App\Core\Entities\TalentoHumano\Distributivo\Area', 'id', 'area_id');
    }
    public function edificio()
    {
        return $this->hasOne('App\Core\Entities\TalentoHumano\Distributivo\Edificio', 'id', 'edificio_id');
    }
}
