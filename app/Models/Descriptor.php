<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Descriptor extends Model
{
    protected $table = 'Descriptor';
    protected $fillable = ['idTipoDescriptor','Titulo','Descripcion',
    'CatalogoDescriptorescol'];

    public function TipoDescriptor()
    {
        return $this->belongsTo('App\Models\TipoDescriptor','idTipoDescriptor');
    }


}
