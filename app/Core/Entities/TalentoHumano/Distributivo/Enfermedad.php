<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;

use Illuminate\Database\Eloquent\Model;

class Enfermedad extends Model
{
    protected $table = 'sc_distributivo_.enfermedades';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';

    protected $fillable = [
        'persona_id',
        'nombre',
        'fecha_diagnostico',
    ];
    public $timestamps = false;
   
  
}
