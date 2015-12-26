<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    public $table = 'Proyecto';
    public $fillable = [
        'Titulo','Descripcion', 'Antecedentes',
        'Justificacion','Objetivos', 'Alcances',
        'idEjecucion','idImpacto','idModeloNegocio'];

    //Relationships

    public function Persona(){
        return $this->belongsToMany('App\Models\Persona', 'Persona_Proyecto', 'idProyecto', 'idPersona')->withPivot('Owner');
    }

    public function Modalidad(){
        return $this->belongsToMany('App\Models\Modalidad', 'Proyecto_Modalidad', 'idProyecto', 'idModalidad')->
        withPivot('id','Solicitud','MontoSolicitado','MontoApoyado','TRLInicial','TRLFinal','FechaRegistro','FechaCierre','Resultado');
    }

    public function Impacto()
    {
        return $this->hasOne('App\Models\Impacto','idProyecto');
    }

    public function Ejecucion()
    {
        return $this->hasOne('App\Models\Ejecucion','idProyecto');
    }
    public function ModeloNegocio()
    {
        return $this->hasOne('App\Models\ModeloNegocio','idProyecto');
    }

    public function TRL()
    {
        return $this->belongsToMany('App\Models\TRL','ProyectoTRL','idProyecto','idTRL')->
        withPivot('id','Descripcion','Fecha','created_at','updated_at');
    }

}
