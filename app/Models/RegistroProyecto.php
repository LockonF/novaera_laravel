<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
