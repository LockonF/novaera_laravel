<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    public $table = 'Proyecto';
    public $fillable = [
        'Titulo','Descripcion', 'Antecedentes',
        'Justificacion','Objetivos', 'Alcances',
        'idEjecucion','idImpacto','idModeloNegocio'];
}
