<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TRL extends Model
{
    protected $table = 'TRL';
    protected $fillable = ['Descripcion'];


    public function Proyecto()
    {
        return $this->belongsToMany('Proyecto','ProyectoTRL','idTRL','idProyecto')->
        withPivot('id','Descripcion','Fecha');
    }
}
