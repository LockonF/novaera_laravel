<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProyectoDescriptor extends Model
{
    protected $table = 'proyectodescriptor';
    protected $fillable = ['observaciones'];
}
