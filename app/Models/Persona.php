<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    public $table = 'Persona';
    public $fillable = ['Nombre','ApellidoP','ApellidoM','Notas','Description','idUser','idContacto'];
    public $hidden = ['idUser','idContacto'];

    /*Relationships*/

    public function User()
    {
        return $this->belongsTo('App\User','idUser');
    }

    public function Proyecto(){
        return $this->belongsToMany('App\Models\Proyecto', 'Persona_Proyecto', 'idPersona', 'idProyecto')->withPivot('Owner');
    }


    public function Organizacion(){
        return $this->belongsToMany('App\Models\Organizacion', 'Persona_Organizacion', 'idPersona', 'idOrganizacion')
            ->withPivot('Owner','FechaInicio','FechaTermino','Owner');
    }


    public function Contacto()
    {
        return $this->hasOne('App\Models\Contacto','idPersona');
    }

}
