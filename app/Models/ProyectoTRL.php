<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProyectoTRL extends Model
{
    protected $table = 'ProyectoTRL';
    protected $fillable = ['idProyecto','idTRL','Descripcion','Fecha'];

    public function Proyecto()
    {
        return $this->belongsTo('App\Models\Proyecto','idProyecto');
    }
}
