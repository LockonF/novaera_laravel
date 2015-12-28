<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDescriptor extends Model
{
    protected $table = 'TipoDescriptor';
    protected $fillable = ['Nombre','Aplicable','Activo'];

    public function Descriptor()
    {
        return $this->hasMany('App\Models\Descriptor','idTipoDescriptor');
    }

}
