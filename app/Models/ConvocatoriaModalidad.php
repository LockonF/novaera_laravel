<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConvocatoriaModalidad extends Model
{
    protected $table= 'Convocatoria_Modalidad';
    protected $fillable = ['idConvocatoria','idModalidad'];


    public function Modaliad()
    {
        return $this->belongsTo('App\Models\Modalidad','idModalidad');
    }
}
