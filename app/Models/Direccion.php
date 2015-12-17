<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    //
    protected $table = 'Direccion';
    protected $fillable = ['Calle','NumExt','NumInt','Colonia','CP','idMunicipio'];
    protected $hidden = ['created_at','updated_at'];


    public function Municipio()
    {
        return $this->belongsTo('\App\Models\Municipio','idMunicipio');
    }

    public function Contacto()
    {
        return $this->belongsTo('\App\Models\Contacto','idContacto');
    }
}
