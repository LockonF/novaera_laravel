<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Models\Organizacion;
use App\Models\Persona;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions;


class OrganizacionController extends Controller
{




    public function showOneGeneral($id)
    {
        try {
            AuthenticateController::checkUser(null);
            $organizacion = Organizacion::find($id);
            if ($organizacion == null) {
                return response()->json(['message' => 'organizacion_not_found'],404);
            }
            return response()->json($organizacion);
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }catch(UnauthorizedException $e)
        {
            return response()->json(['unauthorized'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }

    /**
     * Función para mostrar todas las organizaciones
     * @return \Illuminate\Http\JsonResponse
     * @throws Exceptions\JWTException
     * @throws Exceptions\TokenExpiredException
     * @throws Exceptions\TokenInvalidException
     */

    public function showAllGeneral()
    {
        try{
            AuthenticateController::checkUser(null);
            return response()->json(['Organizacion'=>Organizacion::all()]);
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }catch(UnauthorizedException $e)
        {
            return response()->json(['unauthorized'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

    }



    /**
     * @return \Illuminate\Http\JsonResponse
     */


    public function showAll()
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            return response()->json(['Organizacion'=>$user->Persona->Organizacion()->get()]);

        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }catch(UnauthorizedException $e)
        {
            return response()->json(['unauthorized'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function show($id)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $organizacion = $user->Persona->Organizacion()
                ->where('Persona_Organizacion.idPersona',$user->Persona->id)
                ->where('Persona_Organizacion.idOrganizacion',$id)->first();

            if($organizacion == null) {
                return response()->json(['message' => 'organizacion_not_found'],404);
            }
            return response()->json($organizacion);

        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }catch(UnauthorizedException $e)
        {
            return response()->json(['unauthorized'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */


    public function store(Request $request)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            if($user->Persona!=null)
            {
                $organizacion = new Organizacion($request->Organizacion);
                $personaData = $request->Datos;
                $personaData['Owner'] = 1;

                $user->Persona->Organizacion()->save($organizacion,$personaData);
                return response()->json($organizacion);
            }
            return response()->json(['message'=>'persona_not_found'],404);
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }catch(UnauthorizedException $e)
        {
            return response()->json(['unauthorized'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroy($id)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');

                $record= $user->Persona->Organizacion()->where('Organizacion.id',$id)->where('idPersona',$user->Persona->id)->where('Owner',1)->first();
                if($record!=null)
                {
                    $record->delete();
                    return response()->json(['message'=>'success']);
                }
                return response()->json(['message'=>'only owner can delete'],500);

        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }catch(UnauthorizedException $e)
        {
            return response()->json(['unauthorized'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */


    public function update(Request $request, $id)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');

            $record= $user->Persona->Organizacion()->where('Organizacion.id',$id)->where('idPersona',$user->Persona->id)->where('Owner',1)->first();
            if($record!=null)
            {
                $record->fill($request->all());
                $record->save();
                return response()->json($record);
            }
            return response()->json(['message'=>'only owner can update'],500);

        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }catch(UnauthorizedException $e)
        {
            return response()->json(['unauthorized'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function upload(Request $request)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $request->file('file')->move('files/'.$user->username,$request->type."_".$request->name);
            return response()->json();

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


    public function addPersona(Request $request)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $persona = Persona::find($request->idPersona);
            $organizacion= $user->Persona->Organizacion()->where('Organizacion.id',$request->idOrganizacion)->where('idPersona',$user->Persona->id)->where('Owner',1)->first();
            if($persona==null)
            {
                return response()->json(['message'=>'persona_not_found'],500);
            }
            if($organizacion==null)
            {
                return response()->json(['message'=>'organizacion_not_found'],500);
            }
            $organizacion->Persona()->save($persona,$request->Datos);
            return response()->json(['message'=>'success']);

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
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws Exceptions\JWTException
     * @throws Exceptions\TokenExpiredException
     * @throws Exceptions\TokenInvalidException
     */

    public function showPersonasOrganizacion($id)
    {
        $user = AuthenticateController::checkUser(null);
        $organizacion= $user->Persona->Organizacion()->find($id);
        if($organizacion==null)
        {
            return response()->json(['message'=>'organizacion_not_found'],404);
        }
        $organizacion->load('Persona');
        return response()->json(['Persona'=>$organizacion->Persona]);
    }

    public function removePersonaOrganizacion($idOrganizacion,$idPersona)
    {
        try
        {
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
                $organizacion= $user->Persona->Organizacion()->where('Organizacion.id',$idOrganizacion)->where('idPersona',$user->Persona->id)->where('Owner',1)->first();
            if($organizacion!=null) {
                $isdone = DB::table('Persona_Organizacion')
                    ->where('idOrganizacion', $idOrganizacion)
                    ->where('idPersona', $idPersona)
                    ->where('Owner', 0)
                    ->delete();
                if($isdone)
                    return response()->json(['message'=>'success']);
                return response()->json(['message'=>'persona_not_found'],404);
            }
            return response()->json(['organizacion_not_found_or_not_owner'],500);
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
