<?php

namespace App\Core\Entities\TalentoHumano\Distributivo;

use Illuminate\Database\Eloquent\Model;
//use OwenIt\Auditing\Contracts\Auditable;

//class Historia_laboral extends Model implements Auditable
class Historia_laboral extends Model
{
    //use \OwenIt\Auditing\Auditable;

    protected $table = 'sc_distributivo_.historias_laborales';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';

    protected $fillable = [
        'persona_id',
        'numero_accion_personal',
        'fecha_accion_personal',
        'numero_partida_presupuestaria',
        'motivo_id',
        'motivo_otro',
        'tipo_documento_legal_id',
        'numero_documento_legal',
        'explicacion',
        'historia_laboral_id',
        'ciudad_id',
        'area_id',
        'area_id_padre',
        'cargo_id',
        'denominacion_id',
        'fecha_ingreso',
        'fecha_salida',
        'es_principal',
        'es_jefe_inmediato',
        'tipo_contrato_id',
        'tipo_proceso_id',
        'observacion',
        'estado',
        'edificio_id',
        'horario_id'
    ];



    public $timestamps = false;


    public function area()
    {
        return $this->hasOne('App\Core\Entities\TalentoHumano\Distributivo\Area', 'id', 'area_id');
    }

    public function edificio()
    {
        return $this->hasOne('App\Core\Entities\TalentoHumano\Distributivo\Edificio', 'id', 'edificio_id');
    }

    public function horario()
    {
        return $this->hasOne('App\Core\Entities\TalentoHumano\Biometrico\Horario', 'id', 'horario_id');
    }

    public function cargo()
    {
        return $this->hasOne('App\Core\Entities\TalentoHumano\Distributivo\Cargo', 'id', 'cargo_id');
    }

    public function denominacion()
    {
        return $this->hasOne('App\Core\Entities\TalentoHumano\Distributivo\Denominacion', 'id', 'denominacion_id');
    }

    public function tipo_contrato()
    {
        return $this->hasOne('App\Core\Entities\TalentoHumano\Distributivo\Tipo_contrato', 'id', 'tipo_contrato_id');
    }

    public function persona()
    {
        return $this->hasOne('App\Core\Entities\TalentoHumano\Distributivo\Persona', 'id', 'persona_id');
    }

    public function movimiento()
    {
        return $this->hasOne('App\Core\Entities\TalentoHumano\Distributivo\Motivo', 'id', 'motivo_id');
    }

}
