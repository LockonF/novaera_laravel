<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramaFondeo extends Model
{
    protected $table = 'ProgramaFondeo';
    protected $fillable = ['Titulo','PublicoObjetivo','FondoTotal','CriteriosElegibilidad'];

}
