<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    public $table = 'Persona';
    public $fillable = ['Nombre','ApellidoP','ApellidoM','Notas','Description','idUser','idContacto'];
    public $hidden = ['idUser','idContacto'];

    public function User()
    {
        return $this->belongsTo('App\User','idUser');
    }

    public function Proyecto(){
        return $this->belongsToMany('App\Models\Proyecto', 'Persona_Proyecto', 'idPersona', 'idProyecto')->withPivot('Owner');
    }
}
