<?php

namespace App\Http\Controllers;

use App\Models\Direccion;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Exceptions\UnauthorizedException;
use Tymon\JWTAuth\Exceptions;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DireccionController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     * @throws \Tymon\JWTAuth\Exceptions\TokenExpiredException
     * @throws \Tymon\JWTAuth\Exceptions\TokenInvalidException
     */
    public function store(Request $request)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Contacto');
            $contacto = $user->Contacto[0];
            if($user->Contacto ==null)
            {
                return response()->json(['message'=>'contacto_not_found'],500);
            }

            $hasOther = Direccion::where('idContacto',$contacto->id)->count();
            if($hasOther==0)
            {
                $direccion = new Direccion($request->all());
                $contacto->Direccion()->save($direccion);
                return response()->json($direccion);
            }
            return response()->json(['message'=>'direccion_already_exists'],500);




        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }


    public  function update(Request $request)
    {
        try {
            $user = AuthenticateController::checkUser(null);
            $user->load('Contacto');
            $contacto = $user->Contacto[0];
            if ($user->Contacto == null) {
                return response()->json(['message' => 'contacto_not_found'], 500);
            }
            $contacto->load('Direccion');
            if ($contacto->Direccion == null)
            {
                return response()->json(['message' => 'direccion_not_found'], 500);
            }
            $contacto->Direccion->fill($request->all());
            $contacto->Direccion->save();
            return response()->json($contacto->Direccion);


        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }



    /**
     * @return \Illuminate\Http\JsonResponse
     */

    public function show()
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Contacto');
            $contacto = $user->Contacto[0];
            if($user->Contacto ==null)
            {
                return response()->json(['message'=>'contacto_not_found'],500);
            }

            $contacto->load('Direccion');
            if($contacto->Direccion!=null)
            {
                return response()->json($contacto->Direccion);
            }

            return response()->json(['message'=>'direccion_not_found'],404);

        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }



}
