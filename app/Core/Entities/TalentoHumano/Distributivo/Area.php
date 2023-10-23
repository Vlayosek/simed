<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;


use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'sc_distributivo_.areas';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

    public function area_padre()
    {
        return $this->hasOne('App\Core\Entities\TalentoHumano\Distributivo\Area', 'id', 'area_id');
    }

    public function grupo_personas()
    {
        return $this->hasMany('App\Core\Entities\Horarios\Grupos\GrupoPersona', 'area_id', 'id')->where('estado','ACT');
    }
}
