<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Models\Convocatoria;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ConvocatoriaController extends Controller
{

    public function addToModalidad(Request $request)
    {
        AuthenticateController::checkUser('Supervisor');
        $convocatoria = Convocatoria::find($request->idConvocatoria);
        if($convocatoria==null)
        {

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
            $convocatoria = new Convocatoria($request->all());
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
                $convocatoria->fill($request->all());
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


}
