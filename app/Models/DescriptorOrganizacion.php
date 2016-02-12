<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DescriptorOrganizacion extends Model
{
    protected $table = 'descriptor_organizacion';
    protected $fillable = ['id','FechaInicio','FechaTermino','TipoResultado','NumeroRegistro'];
}
