<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;


use Illuminate\Database\Eloquent\Model;

class Asignacion_area extends Model
{
    protected $table = 'sc_distributivo_.asignaciones_areas';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

    public function area()
    {
        return $this->hasOne('App\Core\Entities\TalentoHumano\Distributivo\Area', 'id', 'area_id');
    }
    public function persona()
    {
        return $this->hasOne('App\Core\Entities\TalentoHumano\Distributivo\Persona', 'identificacion', 'identificacion');
    }
}
