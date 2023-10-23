<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'sc_distributivo_.cursos';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tipo_capacitacion',
        'nombre',
        'anio',
        'numero_horas',
        'persona_id',

    ];
    public $timestamps = false;
    public function instruccion()
    {
       // return $this->hasOne('App\Core\Entities\Public_prerep\Provincia', 'pro_id', 'canton_id');
    }
  
}
