<?php

namespace App\Http\Controllers;

use App\Models\ParqueTecnologico;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ParqueTecnologicoController extends Controller
{
    public function showAll()
    {
        return response()->json(['ParqueTecnologico'=>ParqueTecnologico::all()]);
    }
}
