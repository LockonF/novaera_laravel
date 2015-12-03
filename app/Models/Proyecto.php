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

    public function Persona(){
        return $this->belongsToMany('App\Models\Persona', 'Persona_Proyecto', 'idProyecto', 'idPersona')->withPivot('Owner');
    }

    public function Modalidad(){
        return $this->belongsToMany('App\Models\Modalidad', 'Proyecto_Modalidad', 'idProyecto', 'idModalidad')->
        withPivot('id','Solicitud','MontoSolicitado','MontoApoyado','TRLInicial','TRLFinal','FechaRegistro','FechaCierre','Resultado');
    }

}
