<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;

use Illuminate\Database\Eloquent\Model;

class Enfermedad_discapacidad extends Model
{
    protected $table = 'sc_distributivo_.enfermedades_discapacidades';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';

    protected $fillable = [
        'discapacidad_nombre',
        'discapacidad_numero_carnet',
        'discapacidad_porcentaje',
        'enfermedad_nombre',
        'enfermedad_numero_carnet',
        'enfermedad_porcentaje',
        'catastrofica_nombre',
        'accidente_laboral',
        'parte_cuerpo_afectado',
        'persona_id',
        
    ];
    public $timestamps = false;
   
  
}
