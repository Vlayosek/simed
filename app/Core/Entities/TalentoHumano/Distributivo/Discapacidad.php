<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;

use Illuminate\Database\Eloquent\Model;

class Discapacidad extends Model
{
    protected $table = 'sc_distributivo_.discapacidades';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';

    protected $fillable = [
        'persona_id',
        'nombre',
        'numero_carnet',
        'porcentaje',
    ];
    public $timestamps = false;
   
  
}
