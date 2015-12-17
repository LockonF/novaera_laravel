<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntidadFederativa extends Model
{
    protected $table = 'EntidadFederativa';
    protected $fillable = ['Clave','Nombre','Abrev'];

    public function Pais()
    {
        return $this->belongsTo('App\Models\Pais','idPais');
    }

    public function Municipio()
    {
        return $this->hasMany('App\Models\Municipio','idEstado');
    }



}
