<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RegistroProyecto extends Model
{
    protected $table = 'RegistroProyecto';
    protected $fillable = ['idProyecto','idConvocatoriaModalidad','idParque','Solicitud',
    'MontoSolicitado','MontoSolicitado','idTRLInicial','idTRLFinal','FechaRegistro','FechaCierre'
    ,'Requisitos','Resultado','Validado'];

    public function Proyecto()
    {
        return $this->belongsTo('App\Models\Proyecto','idProyecto');
    }

    public function ParqueTecnologico()
    {
        return $this->belongsTo('App\Models\ParqueTecnologico','idParque');
    }

    public function ConvocatoriaModalidad()
    {
        return $this->belongsTo('App\Models\ConvocatoriaModaliad','idConvocatoriaModalidad');
    }


    public static function getByConvocatoria($id)
    {
        $query = DB::table('Proyecto')
            ->join('RegistroProyecto','RegistroProyecto.idProyecto','=','Proyecto.id')
            ->join('ParqueTecnologico','ParqueTecnologico.id','=','RegistroProyecto.idParque')
            ->join('Convocatoria_Modalidad','Convocatoria_Modalidad.id','=','RegistroProyecto.idConvocatoriaModalidad')
            ->join('Modalidad','Convocatoria_Modalidad.idModalidad','=','Modalidad.id')
            ->join('Convocatoria','Convocatoria_Modalidad.idConvocatoria','=','Convocatoria.id')
            ->join('ProgramaFondeo','Modalidad.idProgramaFondeo','=','ProgramaFondeo.id')
            ->select('RegistroProyecto.*','Modalidad.Nombre as Modalidad','Convocatoria.Nombre as Convocatoria','ParqueTecnologico.Nombre as Parque','Proyecto.*','ProgramaFondeo.Titulo as ProgramaFondeo')
            ->where('Convocatoria.id',$id)
            ->get();

        return $query;
    }


}
