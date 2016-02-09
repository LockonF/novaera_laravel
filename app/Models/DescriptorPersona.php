<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DescriptorPersona extends Model
{
    protected $table = 'descriptor_persona';
    protected $fillable = ['FechaInicio','FechaTermino','TipoResultado','NumeroRegistro'];
}
