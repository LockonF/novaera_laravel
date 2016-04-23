<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProyectoTRL extends Model
{
    protected $table = 'ProyectoTRL';
    protected $fillable = ['id','idProyecto','idTRL','Descripcion','Fecha'];

    public function Proyecto()
    {
        return $this->belongsTo('App\Models\Proyecto','idProyecto');
    }

    public function ProyectoResultado()
    {
        return $this->hasMany('App\Models\ProyectoResultado','idProyectoTRL');
    }



    public static function countByTRL($user,$type='Persona',$idOrganizacion=null,$strict=1)
    {

        if($type=='Persona')
        {
            $user->load('Persona');
            $idProyectos  = $user->Persona->Proyecto()
                ->where('Persona_Proyecto.WritePermissions',$strict)
                ->select('Proyecto.id')
                ->lists('Proyecto.id')->toArray();
        }
        if($type=='Organizacion') {
            $idProyectos =
                DB::table('Persona')
                    ->join('Persona_Organizacion', 'Persona.id', '=', 'Persona_Organizacion.idPersona')
                    ->join('Organizacion', 'Organizacion.id', '=', 'Persona_Organizacion.idOrganizacion')
                    ->join('Organizacion_Proyecto', 'Organizacion.id', '=', 'Organizacion_Proyecto.idOrganizacion')
                    ->join('Proyecto', 'Proyecto.id', '=', 'Organizacion_Proyecto.idProyecto')
                    ->where('Persona.id', $user->Persona->id)
                    ->where('Organizacion.id', $idOrganizacion)
                    ->where('Persona_Organizacion.WritePermissions', $strict)
                    ->lists('Proyecto.id')->toArray();
        }

        $totalTRLRegisters = TRL::count();
        $trls =TRL::lists('Nivel')->toArray();
        $countArray = array_fill(0,$totalTRLRegisters,0);
        $maximum = DB::table('ProyectoTRL')
            ->select(DB::raw('MAX(ProyectoTRL.idTRL) as Max'))
            ->groupBy('ProyectoTRL.idProyecto')
            ->havingRaw('ProyectoTRL.idProyecto in'.'('.implode(",",$idProyectos).')')
            ->get();

        foreach($maximum as $counter)
        {
            $countArray[$counter->Max-1] = $countArray[$counter->Max-1]+1;
        }

        $results['Labels']=$trls;
        $results['Data']=$countArray;
        array_walk($results['Labels'],function(&$item){
            $item = 'TRL '.$item;
        });




        return $results;


    }

}
