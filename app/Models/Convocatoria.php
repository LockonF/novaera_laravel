<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Convocatoria extends Model
{
    protected $table = 'Convocatoria';
    protected $fillable = ['Nombre','FechaInicio','FechaTermino','Requisitos','MontosMaximosTotales','idProgramaFondeo'];

    /*
     * Relaciones
     */

    public function ProgramaFondeo()
    {
        return $this->belongsTo('App\Models\ProgramaFondeo','idProgramaFondeo');
    }

    public function Modalidad()
    {
        return $this->hasMany('App\Models\Modalidad','idConvocatoria');
    }




}
