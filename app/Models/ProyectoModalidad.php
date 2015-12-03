<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProyectoModalidad extends Model
{
    public $table = 'Proyecto_Modalidad';
    public $fillable = ['id','idModalidad','idProyecto','Solicitud','MontoSolicitado','MontoApoyado',
    'TRLInicial','TRLFinal','FechaRegistro','FechaCierre','Resultado'];
}
