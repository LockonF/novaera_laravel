<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EtapaProyecto extends Model
{
    protected $table = 'EtapaProyecto';
    protected $fillable = ['idProyecto','name','description'];

    public function tasks()
    {
        return $this->hasMany('App\Models\TareaEtapa','idEtapa');
    }
    public function Proyecto()
    {
        return $this->belongsTo('App\Models\Proyecto','idProyecto');
    }

}
