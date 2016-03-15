<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Descriptor extends Model
{
    protected $table = 'Descriptor';
    protected $fillable = ['idTipoDescriptor','Titulo','Descripcion'];

    //Relationships

    public function TipoDescriptor()
    {
        return $this->belongsTo('App\Models\TipoDescriptor','idTipoDescriptor');
    }


    public function Persona()
    {
        return $this->belongsToMany('App\Models\Persona','Descriptor_Persona','idDescriptor','idPersona')
            ->withPivot('id','FechaInicio','FechaTermino','TipoResultado','NumeroRegistro')->withTimestamps();
    }


    public function Organizacion()
    {
        return $this->belongsToMany('App\Models\Organizacion','Descriptor_Organizacion','idDescriptor','idOrganizacion')
            ->withPivot('id','FechaInicio','FechaTermino','TipoResultado','NumeroRegistro')->withTimestamps();
    }

    public function ProgramaFondeo()
    {
        return $this->belongsToMany('App\Models\ProgramaFondeo','ProgramaFondeoDescriptor','idDescriptor','idProgramaFondeo')
            ->withPivot('id','observaciones');
    }

    public function Proyecto()
    {
        return $this->belongsToMany('App\Models\Proyecto','ProyectoDescriptor','idDescriptor','idProyecto')
            ->withPivot('id','observaciones');
    }





}
