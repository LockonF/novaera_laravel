<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProyectoResultado extends Model
{
    protected $table = 'ProyectoResultado';
    protected $fillable = ['idProyectoTRL'
        ,'Tipo',
        'Nombre',
        'Resumen',
        'Status',
        'Fecha',
        'NumeroRegistro',
        'PaisesProteccion',
        'PlanDeExplotacion',
        'AreaDeAplicacion',
        'Avance'];

    public function ProyectoTRL()
    {
        return $this->belongsTo('App\Models\ProyectoTRL','idProyectoTRL');
    }



}
