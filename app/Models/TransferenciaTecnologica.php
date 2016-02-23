<?php

namespace App\Models;

use App\Exceptions\InvalidAccessException;
use App\Exceptions\NotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TransferenciaTecnologica extends Model
{
    protected $table = 'TransferenciaTecnologica';
    protected $fillable = ['idProyecto','ProductosDePropiedad',
    'ProcesosDeTransferencia','ValuacionTecnologica'];

    public function Proyecto()
    {
        return $this->belongsTo('App\Models\Proyecto','idProyecto');
    }

    public function Archivos()
    {
        return $this->hasMany('App\Models\Archivos','idTransferenciaTecnologica');
    }

    public static function ValidateTransferencia($id,$user,$whoIs='Persona',$idOrganizacion=null,$strict=1)
    {
        if($id==null || $whoIs==null || $user==null)
            throw new NotFoundException;
        if(TransferenciaTecnologica::find($id)==null)
        {
            throw new NotFoundException;
        }
        $query =DB::table('TransferenciaTecnologica')
            ->join('Proyecto','TransferenciaTecnoligca.idProyecto','=','Proyecto.id')
            ->select('TransferenciaTecnologica.id');
        if($whoIs=='Persona')
        {
            $query->join('Persona_Proyecto','Persona_Proyecto.idProyecto','=','Proyecto.id')
                ->where('Persona_Proyecto.idPersona',$user->Persona->id);
            if($strict==1)
                $query->where('Persona_Proyecto.WritePermissions',1);

        }if($whoIs=='Organizacion')
        {
            $query->join('Organizacion_Proyecto','Organizacion_Proyecto.idProyecto','=','Proyecto.id')
                ->join('Persona_Organizacion','Organizacion_Proyecto.idOrganizacion','=','Persona_Organizacion.idOrganizacion')
                ->where('Persona_Organizacion.idPersona',$user->Persona->id);
            if($strict==1)
                $query->where('Persona_Organizacion.WritePermissions',1);
        }

        $result = $query->first();
        if($result!=null)
        {
            return TransferenciaTecnologica::find($id);
        }
        throw new InvalidAccessException;

    }


}
