<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthenticateController;
use Tymon\JWTAuth\Exceptions;

class PersonaController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $persona = new Persona($request->all());
            $user->Persona()->save($persona);
            return response()->json(['message'=>'success'],200);
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
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            if($user->Persona==null)
            {
                return response()->json(['message'=>'persona_not_found'],404);
            }
            return response()->json($user->Persona,200);
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error'],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            if($user->Persona==null)
            {
                return response()->json(['message'=>'persona_not_found'],404);
            }
            $user->Persona->fill($request->all());
            $user->Persona->save();
            return response()->json($user->Persona,200);
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error'],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $user->Persona->delete();
            return response()->json(['message'=>'success'],200);
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error'],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }
}
