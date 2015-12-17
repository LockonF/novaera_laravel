<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EntidadFederativaController extends Controller
{

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
   public function showMunicipios($id)
   {
       $municipios = Municipio::where('idEntidadFederativa',$id)->get();
       if(!$municipios->isEmpty())
       {
           return response()->json($municipios);

       }
       return response()->json(['message'=>'entidad_federativa_not_found'],404);

   }

}
