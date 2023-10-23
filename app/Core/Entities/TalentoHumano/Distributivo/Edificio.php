<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;


use Illuminate\Database\Eloquent\Model;

class Edificio extends Model
{
    protected $table = 'sc_distributivo_.edicicios';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'direccion',
        'numero_telefono',
    ];
    public function area_edificio()
    {
        return $this->hasMany('App\Core\Entities\TalentoHumano\Distributivo\Area_edificio', 'edificio_id', 'id')->where('estado','ACT');
    }
}
