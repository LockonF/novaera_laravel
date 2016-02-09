<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidAccessException;
use App\Exceptions\NotFoundException;
use App\Models\Proyecto;
use App\Models\ProyectoResultado;
use App\Models\ResultadoDescriptor;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use Tymon\JWTAuth\Exceptions;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProyectoResultadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showDescriptor($idResultado,$whoIs='Persona',$idOrganizacion=null)
    {

        try{

            $user = AuthenticateController::checkUser();
            $resultado = ProyectoResultado::validateResultadoProyecto($idResultado,$user,$whoIs,$idOrganizacion,0);
            return response()->json(['ResultadoRescriptor'=>$resultado->ResultadoDescriptor]);
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        }catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_resultado_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$whoIs='Persona',$idOrganizacion=null)
    {
        try{

            $user = AuthenticateController::checkUser();
            $resultado = ProyectoResultado::validateResultadoProyecto($request->idResultado,$user,$whoIs,$idOrganizacion);
            if($resultado==null)
                return response()->json(['proyecto_resultado_not_found'],500);
            $resultadoDescriptor = new ResultadoDescriptor($request->all());

            $resultado->ResultadoDescriptor()->save($resultadoDescriptor);
            $resultado->load('ResultadoDescriptor');
            return response()->json(['ResultadoRescriptor'=>$resultado->ResultadoDescriptor]);
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        }catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }

    }

    public function update(Request $request, $id,$whoIs='Persona',$idOrganizacion=null)
    {
        try{

            $user = AuthenticateController::checkUser();
            $resultado = ProyectoResultado::validateResultadoProyecto($request->idResultado,$user,$whoIs,$idOrganizacion);

            $resultadoDescriptor = ResultadoDescriptor::find($id);
            if($resultadoDescriptor==null)
                return response()->json(['resultado_descriptor_not_found'],500);
            $resultadoDescriptor->fill($request->all());
            $resultadoDescriptor->save();
            return response()->json($resultadoDescriptor);
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        }catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $resultadoDescriptor = ResultadoDescriptor::find($id);
            if($resultadoDescriptor==null)
                return response()->json(['resultado_descriptor_not_found'],500);

            $idProyectoResultado = $resultadoDescriptor->idResultado;
            $resultadoDescriptor->delete();
            $proyectoResultado = ProyectoResultado::find($idProyectoResultado);
            $proyectoResultado->load('ResultadoDescriptor');
            return response()->json(['ResultadoDescriptor'=>$proyectoResultado->ResultadoDescriptor]);
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        }catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }
    }
}
