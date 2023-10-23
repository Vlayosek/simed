<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;

use Illuminate\Database\Eloquent\Model;

class Carga_familiar extends Model
{
    protected $table = 'sc_distributivo_.familiares';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';

    protected $fillable = [
        'persona_id',
        'parentesco',
        'identificacion',
        'apellidos_nombres',
        'fecha_nacimiento',
        'genero',
        'carnet_conadis',
        'enfermedad_catastrofica',
        'discapacidad',
        'porcentaje',
        'usuario_inserta',
        'fecha_inserta',
        'usuario_modifica',
        'fecha_modifica',
        'telefono',
        
    ];
    public $timestamps = false;
   
  
}
