<?php

namespace App\Http\Controllers;

use App\Models\Archivos;
use App\Models\Ejecucion;
use App\Models\Proyecto;
use App\Models\TipoArchivo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;
use Tymon\JWTAuth\Exceptions;
use Illuminate\Database\QueryException;
use App\Exceptions\UnauthorizedException;

class EjecucionController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');

            $proyecto  = $user->Persona->Proyecto()->where('Proyecto.id',$request->idProyecto)->first();
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
                $proyecto->load('Ejecucion');
                if($proyecto->Ejecucion==null)
                {
                    $request->Ejecucion = $this->processValue($request->Ejecucion);
                    $ejecucion = new Ejecucion($request->Ejecucion);
                    if($request->type!=null)
                    {
                        $data['user']=$user;
                        $data['proyecto']=$proyecto;
                        $data['request']=$request;
                        $ruta = $user->username."/".$request->type."_".$request->name;
                        $tipo = TipoArchivo::where('Titulo',$request->type)->first();
                        $archivo = new Archivos(["Ruta"=>$ruta,"idTipoArchivo"=>$tipo->id]);
                        $data['ejecucion']=$ejecucion;
                        $data['archivo']=$archivo;

                        return DB::transaction(function () use($data) {
                            $proyecto = $data['proyecto'];
                            $proyecto->Ejecucion()->save($data['ejecucion']);
                            $proyecto = Proyecto::find($proyecto->id);
                            $proyecto->load('Ejecucion');
                            $proyecto->Ejecucion->load('Archivos');
                            $proyecto->Ejecucion->Archivos()->save($data['archivo']);
                            $data['request']->file('file')->move('files/'.$data['user']->username,$data['request']->type."_".$data['request']->name);
                            return response()->json(['Ejecucion'=>$proyecto->Ejecucion,'Archivo'=>$data['archivo']]);
                        });
                    }
                    else
                    {
                        $proyecto->Ejecucion()->save($ejecucion);
                        return response()->json(['Ejecucion'=>$ejecucion]);
                    }

                }
                return response()->json(['message'=>'ejecucion_already_exists'],500);
            }
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

    public function update(Request $request)
    {
        try{

            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $proyecto  = $user->Persona->Proyecto()->where('Proyecto.id',$request->idProyecto)->first();
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
                $proyecto->load('Ejecucion');
                if($proyecto->Ejecucion!=null)
                {
                    $request->Ejecucion = $this->processValue($request->Ejecucion);
                    if($request->type!=null)
                    {
                        $data['user']=$user;
                        $data['proyecto']=$proyecto;
                        $data['request']=$request;

                        DB::transaction(function () use($data) {
                            $user= $data['user'];
                            $proyecto=$data['proyecto'];
                            $request=$data['request'];


                            //Guardamos la ejecución
                            $proyecto->Ejecucion->fill($request->Ejecucion);
                            $proyecto->Ejecucion->save();
                            $proyecto->Ejecucion->load('Archivos');

                            //Ponemos el tipo de archivo junto con su ruta
                            $tipo = TipoArchivo::where('Titulo',$request->type)->first();
                            $ruta = $user->username."/".$request->type."_".$request->name;

                            //Obtenemos el archivo viejo
                            $storedFile = Archivos::where('idEjecucion',$proyecto->Ejecucion->id)->where('idTipoArchivo',$tipo->id)->first();

                            if($storedFile!=null)
                            {
                                //Borramos el archivo viejo
                                try{
                                    Storage::delete($storedFile->Ruta);
                                }catch(FileNotFoundException $e){}

                                //Actualizamos la información
                                $storedFile->Ruta = $ruta;
                                $storedFile->save();
                            }
                            else
                            {
                                $archivo = new Archivos(["Ruta"=>$ruta,"idTipoArchivo"=>$tipo->id]);
                                $proyecto->Ejecucion->Archivos()->save($archivo);
                            }

                            //Movemos el archivo
                            $request->file('file')->move('files/'.$user->username,$request->type."_".$request->name);

                        });
                        $proyecto->load('Ejecucion');
                        $tipo = TipoArchivo::where('Titulo',$request->type)->first();
                        $storedFile = Archivos::where('idEjecucion',$proyecto->Ejecucion->id)->where('idTipoArchivo',$tipo->id)->first();
                        return response()->json(
                            ['Ejecucion'=>$proyecto->Ejecucion,'Archivo'=>$storedFile]);
                    }
                    else
                    {
                        $proyecto->Ejecucion->fill($request->Ejecucion);
                        $proyecto->Ejecucion->save();
                        return response()->json(['Ejecucion'=>$proyecto->Ejecucion]);
                    }


                }
                return response()->json(['message'=>'ejecucion_not_found'],404);
            }
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



    public function show($idProyecto)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $proyecto  = $user->Persona->Proyecto()->where('Proyecto.id',$idProyecto)->first();
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



                $proyecto->load('Ejecucion');

                if($proyecto->Ejecucion!=null)
                {
                    $proyecto->Ejecucion->load('Archivos');
                    return response()->json($proyecto->Ejecucion);
                }
                return response()->json(['message'=>'ejecucion_not_found'],500);

            }
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
     * @param $idProyecto
     * @return \Illuminate\Http\JsonResponse
     */


    public function showFileRoutes($idProyecto){
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $proyecto  = $user->Persona->Proyecto()->where('Proyecto.id',$idProyecto)->first();
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

                $results = DB::table('TipoArchivo')
                    ->join('Archivos','Archivos.idTipoArchivo','=','TipoArchivo.id')
                    ->join('Ejecucion','Archivos.idEjecucion','=','Ejecucion.id')
                    ->join('Proyecto','Ejecucion.idProyecto','=','Proyecto.id')
                    ->where('Proyecto.id',$idProyecto)
                    ->select('Archivos.Ruta as Ruta','TipoArchivo.Titulo as Tipo')
                    ->get();

                if($results!=null)
                    return response()->json(['Archivos'=>$results]);
                return response()->json(['message'=>'archivos_not_found'],404);

            }
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

    private function processValue($array)
    {

        foreach($array as $key=>$item)
        {
            if($item=="null") {
                $newArr[$key] = null;
            }
            else{
                $newArr[$key] = $item;
            }
        }
        return $newArr;
    }



}
