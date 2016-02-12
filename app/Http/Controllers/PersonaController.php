<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Models\Descriptor;
use App\Models\Persona;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\DescriptorPersona;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthenticateController;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions;

class PersonaController extends Controller
{

    public function lookup($name)
    {
        try{
            AuthenticateController::checkUser();
            $personas = Persona::where('Nombre','like',$name.'%')->
            orWhere('ApellidoP','like',$name.'%')->
            orWhere('ApellidoM','like',$name.'%')->get();
            return response()->json(['Persona'=>$personas]);
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
            return response()->json($persona,200);
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
    public function show($who=null)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            if($who=='Current')
            {
                $user->load('Persona');
                if ($user->Persona == null) {
                    return response()->json(['message' => 'persona_not_found'], 404);
                }
                return response()->json(['Persona' => $user->Persona], 200);
            }
            else {
                $persona = Persona::get();
                return response()->json(['Personas'=>$persona]);
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


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */


    public function validatePerson(Request $request)
    {
        try{
            $user = AuthenticateController::checkUser('Supervisor');
            return DB::transaction(function() use ($request)
            {
               foreach($request->all() as $persona)
               {
                   $storedPersona = Persona::find($persona['id']);
                   $storedPersona->isValidated = 1;
                   $storedPersona->save();
               }

                $personas = Persona::where('isValidated',0)->get();
                return response()->json(['Persona'=>$personas]);
            });

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

    public function showNotValidated()
    {
        try{
            $user = AuthenticateController::checkUser('Supervisor');
            $personas = Persona::where('isValidated',0)->get();
            return response()->json(['Persona'=>$personas]);

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




    public function addDescriptor(Request $request)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $persona = $user->Persona;
            if($persona==null)
            {
                return response()->json(['message'=>'persona_not_found'],404);
            }
            $descriptor = Descriptor::find($request->idDescriptor);
            $persona->Descriptor()->save($descriptor,$request->all());

            $descriptores = [];
            foreach($persona->Descriptor as $descriptor)
            {
                $descriptores[] = $descriptor;
            }
            return response()->json(['Descriptor'=>$descriptores]);
        }catch (QueryException $e)
        {
            return response()->json(['message'=>$e->getMessage(),'sql'=>$e->getSql()],500);
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
     */

    public function showAllDescriptor($id = null)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            if($id!= null) {
                $persona = Persona::where('id',$id)->get()->first();
            }
            else{
                $user->load('Persona');
                $persona = $user->Persona;
            }
            if($persona==null)
            {
                return response()->json(['message'=>'persona_not_found'],404);
            }
            $descriptores = [];
            foreach($persona->Descriptor as $descriptor)
            {
                $descriptores[] = $descriptor;
            }
            return response()->json(['Descriptor'=>$descriptores]);
        }catch (QueryException $e)
        {
            return response()->json(['message'=>$e->getMessage(),'sql'=>$e->getSql()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }


    public function updateDescriptor(Request $request, $id)
    {
        try{
            $user = AuthenticateController::checkUser('Supervisor');
            $descriptorPersona = DescriptorPersona::find($request->id);

            if($descriptorPersona ==null)
            {
                return response()->json(['descriptor_organizacion_not_found'],404);
            }

            $descriptorPersona->FechaInicio = $request->FechaInicio;
            $descriptorPersona->FechaTermino = $request->FechaTermino;
            $descriptorPersona->TipoResultado = $request->TipoResultado;
            $descriptorPersona->NumeroRegistro = $request->NumeroRegistro;
            $descriptorPersona->save();

            $persona = Persona::find($request->idPersona);
            $persona->load('Descriptor');
            foreach($persona->Descriptor as $descriptor)
            {
                $descriptores[] = $descriptor;
            }
            return response()->json(['Descriptor'=>$descriptores]);

        }catch (QueryException $e) {
            return response()->json(['message'=>$e->getMessage(),'sql'=>$e->getSql()],500);
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
     */

    public function detachDescriptor($id, $idPersona = null)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            if($idPersona == null)
            {
                $user->load('Persona');
                $persona = $user->Persona;
            }
            else{
                $persona = Persona::where('id',$idPersona)->get()->first();
            }
            if($persona==null)
            {
                return response()->json(['message'=>'persona_not_found'],404);
            }
            //return response()->json(['message'=>DescriptorPersona::find($id)],404);
            $descriptor = DescriptorPersona::find($id);
            if($descriptor!=null)
            {
                $descriptor->delete();
                $descriptores = [];
                foreach($persona->Descriptor as $descriptor)
                {
                    $descriptores[] = $descriptor;
                }
                return response()->json(['Descriptor'=>$descriptores]);
            }
            return response()->json(['message'=>'descriptor_not_found'],404);
        }catch (QueryException $e)
        {
            return response()->json(['message'=>$e->getMessage(),'sql'=>$e->getSql()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }
}
