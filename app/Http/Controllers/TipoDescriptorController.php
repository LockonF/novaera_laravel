<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Models\Descriptor;
use App\Models\TipoDescriptor;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TipoDescriptorController extends Controller
{

    /**
     * @param $classification
     * @return \Illuminate\Http\JsonResponse
     */
    public function showByClassification($classification)
    {
        $tipoDescriptor = TipoDescriptor::where('Aplicable',$classification)->get();
        return response()->json(['TipoDescriptor'=>$tipoDescriptor]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function showAssociatedDescriptor($id)
    {
        $descriptores = Descriptor::where('Activo',1)->where('idTipoDescriptor',$id);
        return response()->json(['Descriptor'=>$descriptores]);
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */

    public function showAll()
    {
        $tipoDescriptores  = TipoDescriptor::all();
        return response()->json(['TipoDescriptor'=>$tipoDescriptores]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function show($id)
    {
        $tipoDescriptor  = TipoDescriptor::find($id);
        $tipoDescriptor->load('Descriptor');
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
            $descriptor = new TipoDescriptor($request->all());
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
            $descriptor = TipoDescriptor::find($id);
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
            $descriptor = TipoDescriptor::find($id);
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
