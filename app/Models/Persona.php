<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    public $table = 'Persona';
    public $fillable = ['Nombre','ApellidoP','ApellidoM','Notas','Description','idUser','idContacto','isValidated'];
    public $hidden = ['idUser','idContacto'];

    /*Relationships*/

    public function User()
    {
        return $this->belongsTo('App\User','idUser');
    }

    public function Proyecto(){
        return $this->belongsToMany('App\Models\Proyecto', 'Persona_Proyecto', 'idPersona', 'idProyecto')->withPivot('Owner');
    }

    public function Descriptor()
    {
        return $this->belongsToMany('App\Models\Descriptor','Descriptor_Persona','idPersona','idDescriptor')
            ->withPivot('id','FechaInicio','FechaTermino','TipoResultado','NumeroRegistro')->withTimestamps();
    }

    public function Organizacion(){
        return $this->belongsToMany('App\Models\Organizacion', 'Persona_Organizacion', 'idPersona', 'idOrganizacion')
            ->withPivot('Puesto','FechaInicio','FechaTermino','Owner','WritePermissions');
    }

    public function Contacto()
    {
        return $this->hasOne('App\Models\Contacto','idPersona');
    }

}
