<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use App\Models\Persona;
use App\Models\ProgramaFondeo;
use App\Models\Proyecto;
use App\Models\ProyectoModalidad;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions;

class ContactoController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(Request $request)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');

            $persona = $user->Persona;
            $new_contacto = new Contacto($request->all());
            $user->Persona->Contacto()->save($new_contacto);

            return response()->json($new_contacto,200);
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
            $user->load('Persona');
            $user->Persona->load('Contacto');
            if($user->Persona->Contacto!=null)
            {
                return response()->json($user->Persona->Contacto);
            }
            return response()->json(['message'=>'contacto_not_found'],404);
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */


    public function update(Request $request)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $user->Persona->load('Contacto');
            if($user->Persona->Contacto!=null)
            {
                $user->Persona->Contacto->fill($request->all());
                $user->Persona->Contacto->save();
                return response()->json($user->Persona->Contacto);
            }
            return response()->json(['message'=>'contacto_not_found'],404);
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

    public function destroy()
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $user->Persona->load('Contacto');
            if($user->Persona->Contacto!=null)
            {
                $user->Persona->Contacto->delete();
                return response()->json(['message'=>'success']);
            }
            return response()->json(['message'=>'contacto_not_found'],404);
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
