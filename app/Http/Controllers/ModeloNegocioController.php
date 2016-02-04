<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Models\Archivos;
use App\Models\ModeloNegocio;
use App\Models\Proyecto;
use App\Models\TipoArchivo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;
use Tymon\JWTAuth\Exceptions;
use App\Http\Requests;

use App\Http\Controllers\Controller;

class ModeloNegocioController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request,$whoIs = 'Persona',$idOrganizacion=null)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $proyecto = Proyecto::validateProyecto($request->idProyecto,$user,$whoIs,$idOrganizacion);

            $proyecto->load('ModeloNegocio');
            if($proyecto->ModeloNegocio==null)
            {
                $request->ModeloNegocio = $this->processValue($request->ModeloNegocio);
                $modeloNegocio = new ModeloNegocio($request->ModeloNegocio);
                if($request->type!=null)
                {
                    $data['user']=$user;
                    $data['proyecto']=$proyecto;
                    $data['request']=$request;
                    $ruta = "files/".$user->username."/".$request->type."_".$request->name;
                    $tipo = TipoArchivo::where('Titulo',$request->type)->first();
                    $archivo = new Archivos(["Ruta"=>$ruta,"idTipoArchivo"=>$tipo->id]);
                    $data['modeloNegocio']=$modeloNegocio;
                    $data['archivo']=$archivo;

                    return DB::transaction(function () use($data) {
                        $proyecto = $data['proyecto'];
                        $proyecto->ModeloNegocio()->save($data['modeloNegocio']);
                        $proyecto = Proyecto::find($proyecto->id);
                        $proyecto->load('ModeloNegocio');
                        $proyecto->ModeloNegocio->load('Archivos');
                        $proyecto->ModeloNegocio->Archivos()->save($data['archivo']);
                        $data['request']->file('file')->move('files/'.$data['user']->username,$data['request']->type."_".$data['request']->name);
                        return response()->json(['ModeloNegocio'=>$proyecto->ModeloNegocio,'Archivo'=>$data['archivo']]);
                    });
                }
                else
                {
                    $proyecto->ModeloNegocio()->save($modeloNegocio);
                    return response()->json(['ModeloNegocio'=>$modeloNegocio]);
                }

            }
            return response()->json(['message'=>'modeloNegocio_already_exists'],500);

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

    public function update(Request $request,$whoIs = 'Persona',$idOrganizacion=null)
    {
        try{

            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $proyecto = Proyecto::validateProyecto($request->idProyecto,$user,$whoIs,$idOrganizacion);
            $proyecto->load('ModeloNegocio');
            if($proyecto->ModeloNegocio!=null)
            {
                $request->ModeloNegocio = $this->processValue($request->ModeloNegocio);
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
                        $proyecto->ModeloNegocio->fill($request->ModeloNegocio);
                        $proyecto->ModeloNegocio->save();
                        $proyecto->ModeloNegocio->load('Archivos');

                        //Ponemos el tipo de archivo junto con su ruta
                        $tipo = TipoArchivo::where('Titulo',$request->type)->first();
                        $ruta = "files/".$user->username."/".$request->type."_".$request->name;

                        //Obtenemos el archivo viejo
                        $storedFile = Archivos::where('idModeloNegocio',$proyecto->ModeloNegocio->id)->where('idTipoArchivo',$tipo->id)->first();

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
                            $proyecto->ModeloNegocio->Archivos()->save($archivo);
                        }

                        //Movemos el archivo
                        $request->file('file')->move('files/'.$user->username,$request->type."_".$request->name);

                    });
                    $proyecto->load('ModeloNegocio');
                    $tipo = TipoArchivo::where('Titulo',$request->type)->first();
                    $storedFile = Archivos::where('idModeloNegocio',$proyecto->ModeloNegocio->id)->where('idTipoArchivo',$tipo->id)->first();
                    return response()->json(
                        ['ModeloNegocio'=>$proyecto->ModeloNegocio,'Archivo'=>$storedFile]);
                }
                else
                {
                    $proyecto->ModeloNegocio->fill($request->ModeloNegocio);
                    $proyecto->ModeloNegocio->save();
                    return response()->json(['ModeloNegocio'=>$proyecto->ModeloNegocio]);
                }


            }
            return response()->json(['message'=>'modeloNegocio_not_found'],404);

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

    public function show($idProyecto=null,$whoIs='Persona',$idOrganizacion=null)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $proyecto  = $user->Persona->Proyecto()->where('Proyecto.id',$idProyecto)->first();
            $proyecto = Proyecto::validateProyecto($idProyecto, $user, $whoIs, $idOrganizacion);
            $proyecto->load('ModeloNegocio');

            if($proyecto->ModeloNegocio!=null)
            {
                $proyecto->ModeloNegocio->load('Archivos');
                return response()->json($proyecto->ModeloNegocio);
            }
            return response()->json(['message'=>'modeloNegocio_not_found'],500);
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
                    ->join('ModeloNegocio','Archivos.idModeloNegocio','=','ModeloNegocio.id')
                    ->join('Proyecto','ModeloNegocio.idProyecto','=','Proyecto.id')
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
        $newArr = null;
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
