<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modalidad extends Model
{
    protected $table = 'Modalidad';
    protected $fillable = ['Nombre','Montos','CriteriosEvaluacion','Entregables','FigurasApoyo','idConvocatoria'];

    public function Convocatoria()
    {
        return $this->belongsTo('App\Models\Convocatoria','idConvocatoria');
    }

}
