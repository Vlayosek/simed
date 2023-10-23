<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;

use Illuminate\Database\Eloquent\Model;

class Cuenta_bancaria extends Model
{
    protected $table = 'sc_distributivo_.cuentas_bancarias';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tipo_cuenta',
        'nombre_banco',
        'numero_cuenta',
        'persona_id',
        'eliminado'
    ];
    public $timestamps = false;
}
