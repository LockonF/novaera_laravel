<?php

namespace App\Http\Controllers;

use App\Models\EntidadFederativa;
use App\Models\Pais;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PaisController extends Controller
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */

   public function showAll()
   {
       return response()->json(Pais::all());
   }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

   public function showEntidades($id)
   {
       $entidades  = EntidadFederativa::where('idPais',$id)->get();
       if(!$entidades->isEmpty())
       {
           return response()->json($entidades);
       }
       return response()->json(['message'=>'pais_not_found'],404);
   }




}
