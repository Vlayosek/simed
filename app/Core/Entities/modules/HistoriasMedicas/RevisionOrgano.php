<?php

namespace App\Core\Entities\modules\HistoriasMedicas;

use Illuminate\Database\Eloquent\Model;

class RevisionOrgano extends Model
{
    protected $table = 'sc_historias_clinicas.revisiones_organos';
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'descripcion',
        'detalle',
        'identificacion',
        'codigo',
    ];
   
    
}