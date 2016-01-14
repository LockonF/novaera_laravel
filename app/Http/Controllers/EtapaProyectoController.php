<?php

namespace App\Http\Controllers;

use App\Models\EtapaProyecto;
use App\Models\TareaEtapa;
use Illuminate\Http\Request;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions;
use Illuminate\Database\QueryException;
use App\Exceptions\UnauthorizedException;


class EtapaProyectoController extends Controller
{


    public function store(Request $request)
    {
        try{
            AuthenticateController::checkUser(null);

            return DB::transaction(function() use ($request){
                $etapas = EtapaProyecto::where('idProyecto',$request->idProyecto)->delete();
                $savedEtapas = [];
                foreach($request->EtapaProyecto as $etapa)
               {

                   $tasks = [];
                   $etapa['idProyecto']=$request->idProyecto;
                   $newEtapa = new EtapaProyecto($etapa);
                   $newEtapa->save();
                   foreach($etapa['tasks'] as $task)
                   {
                       $tasks[] = new TareaEtapa($task);
                   }
                   $newEtapa->tasks()->saveMany($tasks);
                   $newEtapa->load('tasks');
                   $savedEtapas[] = $newEtapa;
                   $newEtapa = null;

               }
                return response()->json(['EtapaProyecto'=>$savedEtapas]);

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

}
