<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DescriptorOrganizacion extends Model
{
    protected $table = 'Descriptor_Organizacion';
    protected $fillable = ['id','FechaInicio','FechaTermino','TipoResultado','NumeroRegistro'];
}
