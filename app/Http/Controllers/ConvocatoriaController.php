<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Models\Convocatoria;
use App\Models\Modalidad;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ConvocatoriaController extends Controller
{

    /**
     * @param $idConvocatoria
     * @return \Illuminate\Http\JsonResponse
     */

    public function detachFromAll($idConvocatoria)
    {
        try {
            AuthenticateController::checkUser('Supervisor');
            $convocatoria = Convocatoria::find($idConvocatoria);
            if ($convocatoria == null) {
                return response()->json(['message' => 'convocatoria_not_found'], 500);
            }
            return DB::transaction(function () use ($convocatoria) {

                $convocatoria->Modalidad()->detach();
                $convocatoria->ProgramaAsociado = null;
                $convocatoria->save();
                $convocatoria->load('Modalidad');
                return response()->json($convocatoria);
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
     * @param $idConvocatoria
     * @param $idModalidad
     * @return \Illuminate\Http\JsonResponse
     */

    public function detachFromModaliad($idConvocatoria,$idModalidad)
    {
        try {
            AuthenticateController::checkUser('Supervisor');
            $convocatoria = Convocatoria::find($idConvocatoria);
            if ($convocatoria == null) {
                return response()->json(['message' => 'convocatoria_not_found'], 500);
            }
            $modalidad = Modalidad::find($idModalidad);
            if ($modalidad == null) {
                return response()->json(['message' => 'modalidad_not_found'], 500);
            }
            $convocatoria->Modalidad()->detach($idModalidad);
            $convocatoria->load('Modalidad');
            return response()->json($convocatoria);
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

    public function addToModalidad(Request $request)
    {
        try{
            AuthenticateController::checkUser('Supervisor');

            return DB::transaction(function() use($request) {
                $convocatoria = Convocatoria::find($request->idConvocatoria);
                if ($convocatoria == null) {
                    return response()->json(['message' => 'convocatoria_not_found'], 500);
                }
                foreach($request->Modalidad as $modalidad) {
                    $storedModalidad = Modalidad::find($modalidad);
                    if ($storedModalidad == null) {
                        return response()->json(['message' => 'modalidad_not_found'], 500);
                    }
                    if ($convocatoria->ProgramaAsociado == null) {
                        $convocatoria->ProgramaAsociado = $storedModalidad->idProgramaFondeo;
                        $convocatoria->save();
                    }
                    if ($convocatoria->ProgramaAsociado != $storedModalidad->idProgramaFondeo) {
                        DB::rollBack();
                        return response()->json(['message' => 'can_only_add_convocatoria_to_one_programafondeo'],500);
                    }
                    $convocatoria->Modalidad()->save($storedModalidad);
                }
                $convocatoria->load('Modalidad');
                return response()->json($convocatoria);


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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(Request $request)
    {
        try{
            $user = AuthenticateController::checkUser('Supervisor');
            $requisitos = json_encode($request->Requisitos);
            $convocatoria = new Convocatoria($request->all());
            $convocatoria->Requisitos = $requisitos;
            $convocatoria->save();
            return response()->json($convocatoria);
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
            AuthenticateController::checkUser('Supervisor');
            $convocatoria = Convocatoria::find($id);
            if($convocatoria!=null)
            {
                $convocatoria->delete();
                return response()->json('success',200);
            }
            return response()->json(['message'=>'convocatoria_not_found'],404);

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

    public function update(Request $request, $id)
    {
        try{
            AuthenticateController::checkUser('Supervisor');
            $convocatoria = Convocatoria::find($id);
            if($convocatoria!=null)
            {
                $requisitos = json_encode($request->Requisitos);
                $convocatoria->fill($request->all());
                $convocatoria->Requisitos = $requisitos;
                $convocatoria->save();
                return response()->json($convocatoria,200);
            }
            return response()->json(['message'=>'convocatoria_not_found'],404);

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
            $user = AuthenticateController::checkUser();
            $convocatoria = Convocatoria::all();
            return response()->json(['Convocatoria'=>$convocatoria]);
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

    public function showModalidades($id)
    {
        try {
            $user = AuthenticateController::checkUser();
            $convocatoria = Convocatoria::find($id);
            if($convocatoria == null)
                return response()->json(['message'=>'convocatoria_not_found']);
            $convocatoria->load('Modalidad');
            return response()->json($convocatoria);
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


}
