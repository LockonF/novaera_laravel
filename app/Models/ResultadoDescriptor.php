<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultadoDescriptor extends Model
{
    protected $table = 'ResultadoDescriptor';
    protected $fillable = ['idDescriptor','idResultado','FechaRegistro','FechaAprobacion','PCT'];

    public function ProyectoResultado()
    {
        return $this->belongsTo('App\Models\ProyectoResultado','idResultado');
    }
}
