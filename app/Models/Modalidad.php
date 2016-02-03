<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modalidad extends Model
{
    protected $table = 'Modalidad';
    protected $fillable = ['Nombre','Montos','CriteriosEvaluacion','Entregables','FigurasApoyo','idProgramaFondeo'];

    /*Relationships*/

    public function ProgramaFondeo()
    {
        return $this->belongsTo('App\Models\ProgramaFondeo','idProgramaFondeo');
    }

    public function Convocatoria()
    {
        return $this->belongsToMany('App\Models\Convocatoria','Convocatoria_Modalidad','idModalidad','idConvocatoria');
    }


}
