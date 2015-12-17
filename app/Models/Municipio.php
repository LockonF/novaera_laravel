<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = 'Municipio';
    protected $fillable = ['Clave','Nombre','Sigla','idEstado'];



    public function Direccion()
    {
        return $this->hasMany('\App\Models\Direccion','idMunicipio');
    }

    public function EntidadFederativa()
    {
        return $this->belongsTo('App\Models\EntidadFederativa','idEstado');
    }

}
