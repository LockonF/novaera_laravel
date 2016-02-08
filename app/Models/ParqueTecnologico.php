<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParqueTecnologico extends Model
{
    protected $table = 'ParqueTecnologico';
    protected $fillable = ['Nombre'];

    public function RegistroProyecto()
    {
        return $this->hasMany('App\Models\RegistroProyecto','idParque');
    }
}
