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
            $user->load('Persona');
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




    /**
     * @param $user
     * @param string $type
     * @param null $idOrganizacion
     * @param int $strict
     * @return array
     * @throws InvalidAccessException
     * @throws NotFoundException
     */
    public static function validateAllProyectos($user,$type = 'Persona',$idOrganizacion = null,$strict=0)
    {
        $query = DB::table('Proyecto')
            ->join('RegistroProyecto','RegistroProyecto.idProyecto','=','Proyecto.id')
            ->join('ParqueTecnologico','ParqueTecnologico.id','=','RegistroProyecto.idParque')
            ->join('Convocatoria_Modalidad','Convocatoria_Modalidad.id','=','RegistroProyecto.idConvocatoriaModalidad')
            ->join('Modalidad','Convocatoria_Modalidad.idModalidad','=','Modalidad.id')
            ->join('Convocatoria','Convocatoria_Modalidad.idConvocatoria','=','Convocatoria.id')
            ->select('RegistroProyecto.*','Modalidad.Nombre as Modalidad','Convocatoria.Nombre as Convocatoria','ParqueTecnologico.Nombre as Parque');

        if($type=='Persona')
        {
            $user->load('Persona');
            $query
                    ->join('Persona_Proyecto','Persona_Proyecto.idProyecto','=','Proyecto.id')
                    ->where('Persona_Proyecto.idPersona',$user->Persona->id);
             if($strict==1)
             {
                 $query->where('Persona_Proyecto.WritePermissions',$strict);
             }

            $proyectos =$query->get();

        }
        if($type=='Organizacion')
        {
            $query
                ->join('Organizacion_Proyecto','Organizacion_Proyecto.idProyecto','=','Proyecto.id')
                ->join('Organizacion','Organizacion_Proyecto.idOrganizacion','=','Organizacion.id')
                ->join('Persona_Organizacion','Persona_Organizacion.idOrganizacion','=','Organizacion.id')
                ->where('Persona_Organizacion.idPersona',$user->load('Persona'))
                ->where('Organizacion.id',$idOrganizacion);
            if($strict==1)
            {
                $query->where('Persona_Organizacion.WritePermissions',$strict);
            }
                $proyectos = $query->get();

            if($proyectos==null)
            {
                throw new InvalidAccessException;
            }
            $tempProyectos = [];
            foreach($proyectos as $proyecto)
            {
                $tempProyectos[] = Proyecto::with('RegistroProyecto')->find($proyecto);
            }


            return $tempProyectos;
        }
        return $proyectos;
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

    public function RegistroProyecto()
    {
        return $this->hasMany('App\Models\RegistroProyecto','idProyecto');
    }

    public function Descriptor()
    {
        return $this->belongsToMany('App\Models\Descriptor','ProyectoDescriptor','idProyecto','idDescriptor')
            ->withPivot('id','observaciones');
    }

}
