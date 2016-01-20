<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModalidadCriterios extends Model
{
    protected $table = 'Modalidad_Criterios';
    protected $fillable = ['Descripcion','Nombre','idModalidad'];

    public function Modalidad()
    {
        return $this->belongsTo('App\Models\Modalidad','idModalidad');
    }
}
