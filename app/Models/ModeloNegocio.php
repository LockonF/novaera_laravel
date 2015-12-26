<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModeloNegocio extends Model
{
    protected $table = 'ModeloNegocio';
    protected $fillable = ['idProyecto','Canales','VentajaCompetitiva',
    'Problematica','Costos','Ingresos','ActividadesClave','RelacionesCliente',
    'RecursosClave','AliadosClave'];

    public function Proyecto()
    {
        return $this->belongsTo('App\Models\Proyecto','idProyecto');
    }

    public function Archivos()
    {
        return $this->hasMany('App\Models\Archivos','idModeloNegocio');
    }

}
