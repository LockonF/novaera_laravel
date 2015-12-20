<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archivos extends Model
{
    protected $table = 'Archivos';
    protected $fillable = ['Ruta','idOrganizacionLegal','idEjecucion','idTareaEtapa','idTipoArchivo'];

    public function Ejecucion()
    {
        return $this->belongsTo('App\Models\Ejecucion','idEjecucion');
    }
    public function TipoArchivo()
    {
        return $this->belongsTo('App\Models\TipoArchivo','idTipoArchivo');
    }

}
