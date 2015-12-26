<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferenciaTecnologica extends Model
{
    protected $table = 'TransferenciaTecnologica';
    protected $fillable = ['idProyecto','ProductosDePropiedad',
    'ProcesosDeTransferencia','ValuacionTecnologica'];

    public function Proyecto()
    {
        return $this->belongsTo('App\Models\Proyecto','idProyecto');
    }

    public function Archivos()
    {
        return $this->hasMany('App\Models\Archivos','idTransferenciaTecnologica');
    }


}
