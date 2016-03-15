<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DescriptorPersona extends Model
{
    protected $table = 'Descriptor_Persona';
    protected $fillable = ['FechaInicio','FechaTermino','TipoResultado','NumeroRegistro'];
}
