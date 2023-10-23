<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;

use Illuminate\Database\Eloquent\Model;

class DeclaracionJuramentada extends Model
{
    protected $table = 'sc_distributivo_.declaraciones_juramentadas';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';

    protected $fillable = [
        'fecha_declaracion',
        'nombre_archivo',
        'descripcion_archivo',
        'persona_id'
    ];
    public $timestamps = false;
    public function persona()
    {
        return $this->hasOne('App\Core\Entities\TalentoHumano\Distributivo\Persona', 'id', 'persona_id');
    }
}
