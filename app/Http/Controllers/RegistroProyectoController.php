<?php

namespace App\Http\Controllers;

use App\Models\ConvocatoriaModalidad;
use App\Exceptions\NotFoundException;
use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\RegistroProyecto;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions;

class RegistroProyectoController extends Controller
{




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$whoIs='Persona',$idOrganizacion=null)
    {
        try {
            $user = AuthenticateController::checkUser(null);
            Proyecto::validateProyecto($request->idProyecto,$user,$whoIs,$idOrganizacion);

            $convocatoriaMod = ConvocatoriaModalidad::find($request->idConvocatoriaModalidad);
            if($convocatoriaMod ==null)
            {
                return response()->json(['message'=>'convocatoria_modalidad_not_found']);
            }
            $convocatoria = Convocatoria::find($convocatoriaMod->idConvocatoria);
            $registro = new RegistroProyecto($request->all());
            $requisitos = json_decode($convocatoria->Requisitos);
            foreach($requisitos as $requisito)
            {
                $requisito->validated = false;
            }
            $registro->Requisitos = json_encode($requisitos);
            $registro->Validado = 0;
            $registro->MontoApoyado = 0.00;
            $registro->FechaRegistro = date('Y-m-d');
            $registro->save();
            return response()->json($registro);
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
        }
    }


    /**
     * @param Request $request
     * @param $id
     * @param string $whoIs
     * @param null $idOrganizacion
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\InvalidAccessException
     */

    public function validateRequisitos(Request $request,$id,$whoIs='Persona',$idOrganizacion=null)
    {
        try{
            $user = AuthenticateController::checkUser('Supervisor');
            $registro = RegistroProyecto::find($id);
            if($registro==null)
            {
                return response()->json(['message'=>'registro_proyecto_not_found']);
            }
            Proyecto::validateProyecto($registro->idProyecto,$user,$whoIs,$idOrganizacion);

            $requisitos = json_encode($request->Requisitos);
            $registro->Requisitos = $requisitos;
            $registro->save();
            return response()->json($registro);
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
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\InvalidAccessException
     */

    public function delete($id,$whoIs='Persona',$idOrganizacion=null)
    {
        try {
            $user = AuthenticateController::checkUser(null);
            $registro = RegistroProyecto::find($id);
            if ($registro == null) {
                return response()->json(['message' => 'registro_proyecto_not_found']);
            }
            Proyecto::validateProyecto($registro->idProyecto, $user, $whoIs, $idOrganizacion);
            $registro->delete();
            return response()->json(['message'=>'success']);


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
        }
    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */


    public function showAll($id,$whoIs='Persona',$idOrganizacion=null)
    {
        try
        {
            $user = AuthenticateController::checkUser(null);
            $proyecto = Proyecto::validateProyecto($id,$user,$whoIs,$idOrganizacion);
            $proyecto->load('RegistroProyecto');
            return response()->json(['RegistroProyecto'=>$proyecto->RegistroProyecto]);
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
        }
    }


}
