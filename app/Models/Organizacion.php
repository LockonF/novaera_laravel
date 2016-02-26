<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Organizacion extends Model
{
    protected $table = 'Organizacion';
    protected $fillable = ['Titulo','Descripcion','Mision','Vision','RFC','idContacto','RepresentanteLegal'
    ,'RazonSocial','ActaFile','RFCFile','RENIECyTFile'];

    public function Contacto()
    {
        return $this->belongsTo('App\Models\Contacto','idContacto');
    }

    public function Persona(){
        return $this->belongsToMany('App\Models\Persona', 'Persona_Organizacion', 'idOrganizacion', 'idPersona')
            ->withPivot('Puesto','FechaInicio','FechaTermino','Owner','WritePermissions');
    }

    public function Descriptor()
    {
        return $this->belongsToMany('App\Models\Descriptor','Descriptor_Organizacion','idOrganizacion','idDescriptor')
            ->withPivot('id','FechaInicio','FechaTermino','TipoResultado','NumeroRegistro');
    }


    public function Proyecto(){
        return $this->belongsToMany('App\Models\Proyecto', 'Organizacion_Proyecto', 'idOrganizacion', 'idProyecto')->withPivot('Owner');
    }


    /**
     * @param User $user
     * @param string $id
     * @return bool
     */

    public static function doesPersonaBelongTo($user,$id)
    {
        if($user->Persona->Organizacion()->find($id)!=null)
        {
            return true;
        }
        return false;

    }

}
