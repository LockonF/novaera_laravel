<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions;


class ProyectoController extends Controller
{
    public function storeByPerson(Request $request)
    {
        try{
            $user = AuthenticateController::checkUser();
            $new_proyecto = new Proyecto($request->all());
            $user->load('Persona');
            $user->Persona->Proyecto()->save($new_proyecto,['Owner'=>1]);
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

    public function showProjects()
    {
        try{
            $user = AuthenticateController::checkUser();
            $user->load('Persona');
            $user->Persona->load('Proyecto');
            return response()->json($user->Persona->Proyecto,200);
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

    public function editProject(Request $request, $id)
    {
        try{
            $user = AuthenticateController::checkUser();
            $user->load('Persona');
            $proyecto  = $user->Persona->Proyecto()->where('Proyecto.id',$id)->first();
            if($proyecto == null)
            {
                return response()->json(['message'=>'server_error'],500);
            }
            if($proyecto->pivot->Owner!=1 || $proyecto->pivot->idPersona!=$user->Persona->id)
            {
                return response()->json(['message'=>'owner_not_matching'],500);
            }
            else
            {
                $proyecto->fill($request->all());
                $proyecto->save();
                return response()->json($proyecto,200);
            }
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


    public function removeProject($id)
    {
        try{
            $user = AuthenticateController::checkUser();
            $user->load('Persona');
            $proyecto  = $user->Persona->Proyecto()->where('Proyecto.id',$id)->first();
            if($proyecto == null)
            {
                return response()->json(['message'=>'server_error'],500);
            }
            if($proyecto->pivot->Owner!=1 || $proyecto->pivot->idPersona!=$user->Persona->id)
            {
                return response()->json(['message'=>'owner_not_matching'],500);
            }
            else
            {
                $proyecto->delete();
                return response()->json(['message'=>'success'],200);
            }
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
