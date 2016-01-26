<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organizacion extends Model
{
    protected $table = 'Organizacion';
    protected $fillable = ['Titulo','Descripcion','Mision','Vision'];


    public function Persona(){
        return $this->belongsToMany('App\Models\Persona', 'Persona_Organizacion', 'idOrganizacion', 'idPersona')
            ->withPivot('Owner','FechaInicio','FechaTermino','Owner');
    }

    public function Descriptor()
    {
        return $this->belongsToMany('App\Models\Descriptor','Descriptor_Organizacion','idOrganizacion','idDescriptor')
            ->withPivot('FechaInicio','FechaTermino','TipoResultado','NumeroRegistro');
    }


    public function Proyecto(){
        return $this->belongsToMany('App\Models\Proyecto', 'Organizacion_Proyecto', 'idOrganizacion', 'idProyecto')->withPivot('Owner');
    }
}
