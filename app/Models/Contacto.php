<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    protected $table = 'Contacto';
    protected $fillable = ['CorreoElectronico','TelefonoLocal','TelefonoCelular','TelefonoOficina','Fax','PaginaWeb','idDireccion'];

    public function Persona()
    {
        return $this->belongsTo('App\Models\Persona','idPersona');
    }

    public function Direccion()
    {
        return $this->hasOne('App\Models\Direccion','idContacto');
    }

    public function Organizacion()
    {
        return $this->hasMany('App\Models\Organizacion','idContacto');
    }
}
