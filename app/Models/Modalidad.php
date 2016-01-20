<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modalidad extends Model
{
    protected $table = 'Modalidad';
    protected $fillable = ['Nombre','Montos','CriteriosEvaluacion','Entregables','FigurasApoyo','idConvocatoria'];

    /*Relationships*/

    public function Convocatoria()
    {
        return $this->belongsTo('App\Models\Convocatoria','idConvocatoria');
    }

    public function Proyecto()
    {
        return $this->belongsToMany('App\Models\Proyecto', 'Proyecto_Modalidad', 'idProyecto', 'idModalidad')->
        withPivot('id','Solicitud','MontoSolicitado','MontoApoyado','TRLInicial','TRLFinal','FechaRegistro','FechaCierre','Resultado');
    }

    public function Criterios()
    {
        return $this->hasMany('App\Models\ModalidadCriterios','idModalidad');
    }


}
