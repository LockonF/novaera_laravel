<?php

namespace App\Http\Controllers;


use App\Exceptions\UnauthorizedException;
use App\Models\Modalidad;
use App\Models\ModalidadCriterios;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ModalidadController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try{
            AuthenticateController::checkUser('Supervisor');
                $modalidad = new Modalidad($request->all());
                $modalidad->save();
                return response()->json($modalidad);
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
            $modalidad = Modalidad::find($id);
            if($modalidad!=null)
            {
                $modalidad->delete();
                return response()->json(['message'=>'success']);
            }
            return response()->json(['message'=>'modalidad_not_found'],404);
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

    public function update(Request $request,$id)
    {
        try{
            AuthenticateController::checkUser('Supervisor');
            $modalidad = Modalidad::find($id);
            if($modalidad!=null)
            {
                $modalidad->fill($request->all());
                $modalidad->save();
                return response()->json($modalidad);
            }
            return response()->json(['message'=>'modalidad_not_found'],404);
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

    public function showConvocatorias($id)
    {
        try{
            AuthenticateController::checkUser();
            $modalidad = Modalidad::find($id);
            if($modalidad==null)
            {
                return response()->json(['modalidad_not_found'],500);
            }
            $modalidad->load('Convocatoria');
            foreach($modalidad->Convocatoria as $convocatoria)
            {
                $convocatoria->Requisitos  =json_decode($convocatoria->Requisitos);
            }

            return response()->json(['Convocatoria'=>$modalidad->Convocatoria]);

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
    public function show()
    {
        try{
            AuthenticateController::checkUser();
            $modalidad = Modalidad::all();
            return response()->json(['Modalidad'=>$modalidad]);

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
