<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ejecucion extends Model
{
    protected $table = 'Ejecucion';
    protected $fillable = ['Requisitos','AnalisisEntornoP','FactibilidadTecnicaP','FactibilidadEconomicaP',
    'FactibilidadComercialP','BenchmarkComercialP','BenchmarkTecnologicoP','RecursosHumanosP','RecursosFinancierosP',
    'RecursosTecnologicosP','RecursosMaterialesP','idProyecto'];

    public function Proyecto()
    {
        return $this->belongsTo('App\Models\Proyecto','idProyecto');
    }

    public function Archivos()
    {
        return $this->hasMany('App\Models\Archivos','idEjecucion');
    }
}
