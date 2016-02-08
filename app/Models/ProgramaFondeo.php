<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProgramaFondeo extends Model
{
    protected $table = 'ProgramaFondeo';
    protected $fillable = ['Titulo','PublicoObjetivo','FondoTotal','CriteriosElegibilidad'];

    public function Modalidad()
    {
        return $this->hasMany('App\Models\Modalidad','idProgramaFondeo');
    }

    public static function Convocatorias_Asociadas($id)
    {
        $convocatoria =DB::table('ProgramaFondeo')
            ->join('Modalidad','Modalidad.idProgramaFondeo','=','ProgramaFondeo.id')
            ->join('Convocatoria_Modalidad','Convocatoria_Modalidad.idModalidad','=','Modalidad.id')
            ->join('Convocatoria','Convocatoria_Modalidad.idConvocatoria','=','Convocatoria.id')
            ->select('Convocatoria.*')
            ->where('ProgramaFondeo.id',$id)
            ->get();
        return $convocatoria;
    }

    public function Descriptor()
    {
        return $this->belongsToMany('App\Models\Descriptor','ProgramaFondeoDescriptor','idProgramaFondeo','idDescriptor')
            ->withPivot('id','observaciones');
    }

}
