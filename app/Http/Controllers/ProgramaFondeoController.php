<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Models\ProgramaFondeo;
use App\Models\ProgramaFondeoDescriptor;
use App\Models\Descriptor;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions;

class ProgramaFondeoController extends Controller
{


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showConvocatoriasAsociadas($id)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $convocatorias = ProgramaFondeo::Convocatorias_Asociadas($id);
            if($convocatorias==null)
            {
                return response()->json(['convocatoria_not_found'],500);
            }
            return response()->json(['Convocatoria'=>$convocatorias]);
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
            $programaFondeo = ProgramaFondeo::with('Modalidad')->find($id);
            if($programaFondeo!=null)
            return response()->json($programaFondeo);
            return response()->json(['message'=>'programa_fondeo_not_found'],404);
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
            $programaFondeo = ProgramaFondeo::get();
            return response()->json(['ProgramaFondeo'=>$programaFondeo]);
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
        try{
            $programaFondeo = ProgramaFondeo::with('Modalidad')->find($id);
            if($programaFondeo!=null)
            {
                return response()->json($programaFondeo->Modalidad);
            }
            return response()->json(['message'=>'programa_fondeo_not_found'],404);
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
            $programaFondeo = new ProgramaFondeo($request->all());
            $programaFondeo->save();
            return response()->json($programaFondeo);
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
             $programaFondeo =ProgramaFondeo::find($id);
             if($programaFondeo!=null)
             {
                 $programaFondeo->delete();
                 return response()->json(['message'=>'success']);
             }
             return response()->json(['message'=>'programa_fondeo_not_found'],404);
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
            AuthenticateController::checkUser('Supervisor');
            $programaFondeo =ProgramaFondeo::find($id);
            if($programaFondeo!=null)
            {
                $programaFondeo->fill($request->all());
                $programaFondeo->save();
                return response()->json($programaFondeo);
            }
            return response()->json(['message'=>'programa_fondeo_not_found'],404);
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

    public function addDescriptor(Request $request)
    {
        try{
            AuthenticateController::checkUser(null);
            $programaFondeo =ProgramaFondeo::find($request->idProgramaFondeo);
            if($programaFondeo==null)
            {
                return response()->json(['message'=>'programa_fondeo_not_found'],404);
            }
            $descriptor = Descriptor::find($request->idDescriptor);
            $programaFondeo->Descriptor()->save($descriptor,$request->all());
            $descriptores = [];
            foreach($programaFondeo->Descriptor as $descriptor)
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

    public function showAllDescriptor($id)
    {
        try{
            $user = AuthenticateController::checkUser('Supervisor');
            $programaFondeo =ProgramaFondeo::find($id);
            if($programaFondeo==null)
            {
                return response()->json(['message'=>'programa_fondeo_not_found'],404);
            }
            $descriptores = [];
            foreach($programaFondeo->Descriptor as $descriptor)
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

    public function detachDescriptor($idPrograma,$id)
    {
        try{
            $user = AuthenticateController::checkUser('Supervisor');
            $programaFondeo =ProgramaFondeo::find($idPrograma);
            if($programaFondeo==null)
            {
                return response()->json(['message'=>'programa_fondeo_not_found'],404);
            }
            $pfondeo = ProgramaFondeoDescriptor::find($id);
            $pfondeo->delete();
            $programaFondeo->load('Descriptor');
            $descriptores = [];
            foreach($programaFondeo->Descriptor as $descriptor)
            {
                $descriptores[] = $descriptor;
            }
            return response()->json(['Descriptor'=>$descriptores]);

            return response()->json(['message'=>'descriptor_not_found'],404);
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
}
