<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoArchivo extends Model
{
    public $table = 'TipoArchivo';
    public $fillable = ['Titulo','Aplicable'];

    public function Archivos()
    {
        return $this->hasMany('App\Model\Archivos','idTipoArchivo');
    }
}
