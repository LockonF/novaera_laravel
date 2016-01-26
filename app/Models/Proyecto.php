<?php

namespace App\Models;

use App\Exceptions\NotFoundException;
use App\User;
use App\Exceptions\InvalidAccessException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Proyecto extends Model
{
    public $table = 'Proyecto';
    public $fillable = [
        'Titulo','Descripcion', 'Antecedentes',
        'Justificacion','Objetivos', 'Alcances',
        'idEjecucion','idImpacto','idModeloNegocio'];


    /**
     * @param int $idProyecto
     * @param User $user
     * @param string $type
     * @param int|null $idOrganizacion
     * @param int $strict
     * @return Proyecto
     * @throws InvalidAccessException
     * @throws NotFoundException
     */
    public static function validateProyecto($idProyecto,$user,$type = 'Persona',$idOrganizacion = null,$strict=1)
    {
        if($type=='Persona')
        {
            $proyecto  = $user->Persona->Proyecto()
                ->where('Proyecto.id',$idProyecto)
                ->first();
            if($proyecto == null)
            {
                throw new NotFoundException;
            }
            if(($proyecto->pivot->Owner!=1 || $proyecto->pivot->idPersona!=$user->Persona->id) && $strict==1)
            {
                throw new InvalidAccessException;
            }

        }
        if($type=='Organizacion')
        {
            $proyecto =
                DB::table('Persona')
                ->join('Persona_Organizacion','Persona.id','=','Persona_Organizacion.idPersona')
                ->join('Organizacion','Organizacion.id','=','Persona_Organizacion.idOrganizacion')
                ->join('Organizacion_Proyecto','Organizacion.id','=','Organizacion_Proyecto.idOrganizacion')
                ->join('Proyecto','Proyecto.id','=','Organizacion_Proyecto.idProyecto')
                ->where('Persona.id',$user->Persona->id)
                ->where('Organizacion.id',$idOrganizacion)
                ->where('Proyecto.id',$idProyecto)
                ->where('Persona_Organizacion.WritePermissions',$strict)
                ->select('Proyecto.id')
                ->first();
            if($proyecto==null)
            {
                throw new InvalidAccessException;
            }

            return Proyecto::find($proyecto->id);
        }
        return $proyecto;
    }




    //Relationships

    public function Persona(){
        return $this->belongsToMany('App\Models\Persona', 'Persona_Proyecto', 'idProyecto', 'idPersona')->withPivot('Owner');
    }

    public function Organizacion(){
        return $this->belongsToMany('App\Models\Organizacion', 'Organizacion_Proyecto', 'idProyecto', 'idOrganizacion')->withPivot('Owner');
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

    public function TransferenciaTecnologica()
    {
        return $this->hasMany('App\Models\TransferenciaTecnologica','idProyecto');
    }

    public function EtapaProyecto()
    {
        return $this->hasMany('App\Models\EtapaProyecto','idProyecto');
    }


    public function ProyectoTRL()
    {
        return $this->hasMany('App\Models\ProyectoTRL','idProyecto');
    }

    public function TRL()
    {
        return $this->belongsToMany('App\Models\TRL','ProyectoTRL','idProyecto','idTRL')->
        withPivot('id','Descripcion','Fecha','created_at','updated_at');
    }

}
