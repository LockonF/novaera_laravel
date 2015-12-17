<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MunicipioController extends Controller
{

    public function getSelectedInfo($id)
    {
        try
        {
            $data =
                DB::table('Municipio')
                    ->join('EntidadFederativa','Municipio.idEntidadFederativa','=','EntidadFederativa.id')
                    ->join('Pais','EntidadFederativa.idPais','=','Pais.id')
                    ->select('Pais.id as Pais','EntidadFederativa.id as Estado','Municipio.id as Ciudad')
                    ->where('Municipio.id',$id)
                    ->get();
            if($data==null)
            {
                return response()->json(['message'=>'municipio_not_found'],404);
            }
            return response()->json($data[0]);
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }

    }

}
