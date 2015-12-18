<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Impacto extends Model
{
    protected $table = 'ImpactoYComercializacion';
    protected $fillable = ['ImpactoAmbiental','ImpactoCientifico','ImpactoTecnologico','ImpactoSocial',
                            'ImpactoEconomico','PropuestaDeValor','SegmentosDeClientes','SolucionPropuesta',
                            'Metricas','SolucionActual','idProyecto'];

    public function Proyecto()
    {
        return $this->belongsTo('App\Models\Proyecto','idProyecto');
    }
}
