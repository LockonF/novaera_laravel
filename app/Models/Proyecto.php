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
     * @param $idDescriptor
     * @param $user
     * @param string $type
     * @param null $idOrganizacion
     * @param int $strict
     * @return null
     */

    public static function allByDescriptor($idDescriptor,$user,$type = 'Persona',$idOrganizacion = null,$strict=1)
    {
        $results = DB::table('Proyecto')
            ->join('ProyectoDescriptor','ProyectoDescriptor.idProyecto','=','Proyecto.id')
            ->join('Descriptor','ProyectoDescriptor.idDescriptor','=','Descriptor.id')
            ->where('Descriptor.id',$idDescriptor)
            ->select('Proyecto.id');

        if($type=='Persona')
        {
            $results =
                $results
                ->join('Persona_Proyecto','Proyecto.id','=','Persona_Proyecto.idProyecto')
                ->where('Persona_Proyecto.WritePermissions',$strict)
                ->where('Persona_Proyecto.idPersona',$user->Persona->id)
                ->get();
        }
        if($type=='Organizacion')
        {
            $results=
                $results
                    ->join('Organizacion_Proyecto','Organizacion.id','=','Organizacion_Proyecto.idOrganizacion')
                    ->join('Organizacion','Organizacion.id','=','Persona_Organizacion.idOrganizacion')
                    ->join('Persona_Organizacion','Persona.id','=','Persona_Organizacion.idPersona')
                    ->where('Persona.id',$user->Persona->id)
                    ->where('Organizacion.id',$idOrganizacion)
                    ->where('Persona_Organizacion.WritePermissions',$strict)
                    ->get();

        }
        if(count($results)==0)
            return null;

        foreach($results as $result)
        {
            $ids[]=$result->id;
        }
        return Proyecto::whereIn('id',$ids)->get();

    }

    /**
     * @param $idTipoDescriptor
     * @param $user
     * @param string $type
     * @param null $idOrganizacion
     * @param int $strict
     * @return null
     */

    public static function allByTipoDescriptor($idTipoDescriptor,$user,$type = 'Persona',$idOrganizacion = null,$strict=1)
    {
        $formattedResults = [];
        $results = DB::table('Proyecto')
            ->join('ProyectoDescriptor','ProyectoDescriptor.idProyecto','=','Proyecto.id')
            ->join('Descriptor','ProyectoDescriptor.idDescriptor','=','Descriptor.id')
            ->join('TipoDescriptor','Descriptor.idTipoDescriptor','=','TipoDescriptor.id')
            ->groupBy('Descriptor.id');


        if($type=='Persona')
        {
            $results
                ->join('Persona_Proyecto','Proyecto.id','=','Persona_Proyecto.idProyecto')
                ->where('Persona_Proyecto.WritePermissions','=',$strict)
                ->where('Persona_Proyecto.idPersona','=',$user->Persona->id)
                ->select(DB::raw('count(Descriptor.id) as Data, Descriptor.Titulo as Labels'));
        }
        if($type=='Organizacion')
        {
            $results
                    ->join('Organizacion_Proyecto','Organizacion.id','=','Organizacion_Proyecto.idOrganizacion')
                    ->join('Organizacion','Organizacion.id','=','Persona_Organizacion.idOrganizacion')
                    ->join('Persona_Organizacion','Persona.id','=','Persona_Organizacion.idPersona')
                    ->where('Persona.id','=',$user->Persona->id)
                    ->where('Organizacion.id','=',$idOrganizacion)
                    ->where('Persona_Organizacion.WritePermissions','=',$strict)
                    ->select(DB::raw('count(Descriptor.id) as Data, Descriptor.Titulo as Labels, Persona.id, Organizacion.id, Persona_Organizacion.WritePermissions'));
        }
        $results = $results->get();


        if($results !=null)
        {
            foreach($results as $result)
            {
                $formattedResults['Data'][]=$result->Data;
                $formattedResults['Labels'][]=$result->Labels;
            }
        }

        return $formattedResults;


    }


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
        $proyecto = null;
        if(Proyecto::find($idProyecto)==null)
            throw new NotFoundException;
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
     *
     */
    public static function getOneRegister($user,$id=null,$type = 'Persona',$idOrganizacion = null,$strict=0)
    {
        if(Proyecto::find($id) == null)
        {
            throw new NotFoundException;
        }

        if($type=='Persona')
        {
            $user->load('Persona');
            $query = DB::table('Proyecto')
                ->join('Persona_Proyecto','Persona_Proyecto.idProyecto','=','Proyecto.id')
                ->where('Persona_Proyecto.idPersona',$user->Persona->id);
            if($strict==1)
            {
                $query->where('Persona_Proyecto.WritePermissions',$strict);
            }

            $proyectos =$query->get();
            if($proyectos==null)
            {
                throw new InvalidAccessException;
            }
        }
        if($type=='Organizacion')
        {
            $query =  DB::table('Proyecto')
                ->join('Organizacion_Proyecto','Organizacion_Proyecto.idProyecto','=','Proyecto.id')
                ->join('Organizacion','Organizacion_Proyecto.idOrganizacion','=','Organizacion.id')
                ->join('Persona_Organizacion','Persona_Organizacion.idOrganizacion','=','Organizacion.id')
                ->where('Persona_Organizacion.idPersona',$user->Persona->id)
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
        }

        $proyectos = DB::table('Proyecto')
            ->join('RegistroProyecto','RegistroProyecto.idProyecto','=','Proyecto.id')
            ->join('ParqueTecnologico','ParqueTecnologico.id','=','RegistroProyecto.idParque')
            ->join('Convocatoria_Modalidad','Convocatoria_Modalidad.id','=','RegistroProyecto.idConvocatoriaModalidad')
            ->join('Modalidad','Convocatoria_Modalidad.idModalidad','=','Modalidad.id')
            ->join('Convocatoria','Convocatoria_Modalidad.idConvocatoria','=','Convocatoria.id')
            ->join('ProgramaFondeo','Modalidad.idProgramaFondeo','=','ProgramaFondeo.id')
            ->select('RegistroProyecto.*','Modalidad.Nombre as Modalidad','Convocatoria.Nombre as Convocatoria','ParqueTecnologico.Nombre as Parque','Proyecto.*','ProgramaFondeo.Titulo as ProgramaFondeo')
            ->where('Proyecto.id',$id)
            ->get();




        foreach($proyectos as $proyecto)
        {
            $proyecto->TRLInicial = TRL::find($proyecto->idTRLInicial)->Descripcion;
            $proyecto->TRLFinal = TRL::find($proyecto->idTRLFinal)->Descripcion;
            $proyecto->Requisitos = json_decode($proyecto->Requisitos);
        }
        return $proyectos;
    }


    /**
     * @return mixed
     * @throws NotFoundException
     */

    public static function getAllRegistros()
    {
        $query = DB::table('Proyecto')
            ->join('RegistroProyecto','RegistroProyecto.idProyecto','=','Proyecto.id')
            ->join('ParqueTecnologico','ParqueTecnologico.id','=','RegistroProyecto.idParque')
            ->join('Convocatoria_Modalidad','Convocatoria_Modalidad.id','=','RegistroProyecto.idConvocatoriaModalidad')
            ->join('Modalidad','Convocatoria_Modalidad.idModalidad','=','Modalidad.id')
            ->join('Convocatoria','Convocatoria_Modalidad.idConvocatoria','=','Convocatoria.id')
            ->join('ProgramaFondeo','Modalidad.idProgramaFondeo','=','ProgramaFondeo.id')
            ->select('RegistroProyecto.*','Modalidad.Nombre as Modalidad','Convocatoria.Nombre as Convocatoria','ParqueTecnologico.Nombre as Parque','Proyecto.*','ProgramaFondeo.id as idProgramaFondeo','ProgramaFondeo.Titulo as ProgramaFondeo');
        $proyectos =$query->get();
        if($proyectos==null)
        {
            throw new NotFoundException;
        }
        foreach($proyectos as $proyecto)
        {
            $proyecto->TRLInicial = TRL::find($proyecto->idTRLInicial)->Descripcion;
            $proyecto->TRLFinal = TRL::find($proyecto->idTRLFinal)->Descripcion;
            $proyecto->Requisitos = json_decode($proyecto->Requisitos);
        }
        return $proyectos;

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
            ->join('ProgramaFondeo','Modalidad.idProgramaFondeo','=','ProgramaFondeo.id')
            ->select('RegistroProyecto.*','Modalidad.Nombre as Modalidad','Convocatoria.Nombre as Convocatoria','ParqueTecnologico.Nombre as Parque','Proyecto.*','ProgramaFondeo.Titulo as ProgramaFondeo');

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
            if($proyectos==null)
            {
                throw new InvalidAccessException;
            }
        }
        if($type=='Organizacion')
        {
            $query
                ->join('Organizacion_Proyecto','Organizacion_Proyecto.idProyecto','=','Proyecto.id')
                ->join('Organizacion','Organizacion_Proyecto.idOrganizacion','=','Organizacion.id')
                ->join('Persona_Organizacion','Persona_Organizacion.idOrganizacion','=','Organizacion.id')
                ->where('Persona_Organizacion.idPersona',$user->Persona->id)
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
        }
        foreach($proyectos as $proyecto)
        {
            $proyecto->TRLInicial = TRL::find($proyecto->idTRLInicial)->Descripcion;
            $proyecto->TRLFinal = TRL::find($proyecto->idTRLFinal)->Descripcion;
            $proyecto->Requisitos = json_decode($proyecto->Requisitos);
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
