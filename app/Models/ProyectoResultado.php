<?php

namespace App\Models;

use App\Exceptions\InvalidAccessException;
use App\Exceptions\NotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProyectoResultado extends Model
{
    protected $table = 'ProyectoResultado';
    protected $fillable = [
        'idProyectoTRL'
        ,'Tipo',
        'Nombre',
        'Resumen',
        'Status',
        'Fecha',
        'FechaAprobacion',
        'NumeroRegistro',
        'PaisesProteccion',
        'PlanDeExplotacion',
        'AreaDeAplicacion',
        'Avance'];

    public function ProyectoTRL()
    {
        return $this->belongsTo('App\Models\ProyectoTRL','idProyectoTRL');
    }

    public function ResultadoDescriptor()
    {
        return $this->hasMany('App\Models\ResultadoDescriptor','idResultado');
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
    public static function validateResultadoProyecto($idProyectoResultado,$user,$type = 'Persona',$idOrganizacion = null,$strict=1)
    {
        $user->load('Persona');
        $proyectoResultado = ProyectoResultado::find($idProyectoResultado);
        if($proyectoResultado==null)
        {
            throw new NotFoundException;
        }

        $query =
            DB::table('ProyectoResultado')
            ->join('ProyectoTRL','ProyectoResultado.idProyectoTRL','=','ProyectoTRL.id')
            ->join('Proyecto','ProyectoTRL.idProyecto','=','Proyecto.id')
            ->where('ProyectoResultado.id',$idProyectoResultado)
            ->select('ProyectoResultado.id');
        if($query->first()==null)
        {
            throw new NotFoundException;
        }

        if($type=='Persona')
        {
            $query
                ->join('Persona_Proyecto','Persona_Proyecto.idProyecto','=','Proyecto.id')
                ->where('Persona_Proyecto.idPersona',$user->Persona->id);

            if($strict==1)
            {
                $query->where('Persona_Proyecto.WritePermissions',$strict);
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
        }

        $result = $query->first();
        if($result==null)
            throw new InvalidAccessException;

        return $proyectoResultado;


    }




}
