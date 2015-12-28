<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Models\Descriptor;
use Tymon\JWTAuth\Exceptions;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DescriptorController extends Controller
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */

    public function showAll()
    {
        $descriptores  = Descriptor::with('TipoDescriptor')->get();
        return response()->json(['Descriptor'=>$descriptores]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function show($id)
    {
        $tipoDescriptor  = Descriptor::find($id);
        if($tipoDescriptor!=null)
        {
            return response()->json($tipoDescriptor);
        }
        return response()->json(['message'=>'descriptor_not_found'],404);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(Request $request)
    {
        try{
            AuthenticateController::checkUser('Supervisor');
            $descriptor = new Descriptor($request->all());
            $descriptor->save();
            return response()->json($descriptor);
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
            $descriptor = Descriptor::find($id);
            if($descriptor!=null)
            {
                $descriptor->fill($request->all());
                $descriptor->save();
                return response()->json($descriptor);
            }
            return response()->json(['message'=>'descriptor_not_found'],404);

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
            $descriptor = Descriptor::find($id);
            if($descriptor!=null)
            {
              $descriptor->delete();
              return response()->json(['message'=>'success']);
            }
            return response()->json(['message'=>'descriptor_not_found'],404);

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
