<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;

use Illuminate\Database\Eloquent\Model;

class Estudio extends Model
{
    protected $table = 'sc_distributivo_.estudios';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';

    protected $fillable = [
        'instruccion',
        'titulo',
        'institucion',
        'numero_referencia',
        'persona_id',
    ];
    public $timestamps = false;
  
}
