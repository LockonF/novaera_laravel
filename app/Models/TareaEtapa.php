<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TareaEtapa extends Model
{
    protected $table = 'TareaEtapa';
    protected $fillable = ['idEtapa','name','desription','from','to'];

    public function EtapaProyecto()
    {
        return $this->belongsTo('App\Models\EtapaProyecto','idEtapa');
    }

}
