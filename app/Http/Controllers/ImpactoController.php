<?php

namespace App\Http\Controllers;

use App\Models\Archivos;
use App\Models\Impacto;
use App\Models\Proyecto;
use App\Models\TipoArchivo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions;
use Illuminate\Database\QueryException;
use App\Exceptions\UnauthorizedException;

class ImpactoController extends Controller
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
            $proyecto->load('Impacto');
                if($proyecto->Impacto==null)
                {
                    $request->Impacto = $this->processValue($request->Impacto);
                    $impacto = new Impacto($request->Impacto);
                    if($request->type!=null)
                    {
                        $data['user']=$user;
                        $data['proyecto']=$proyecto;
                        $data['request']=$request;
                        $ruta = "files/".$user->username."/".$request->type."_".$request->name;
                        $tipo = TipoArchivo::where('Titulo',$request->type)->first();
                        $archivo = new Archivos(["Ruta"=>$ruta,"idTipoArchivo"=>$tipo->id]);
                        $data['impacto']=$impacto;
                        $data['archivo']=$archivo;

                        return DB::transaction(function () use($data) {
                            $proyecto = $data['proyecto'];
                            $proyecto->Impacto()->save($data['impacto']);
                            $proyecto = Proyecto::find($proyecto->id);
                            $proyecto->load('Impacto');
                            $proyecto->Impacto->load('Archivos');
                            $proyecto->Impacto->Archivos()->save($data['archivo']);
                            $data['request']->file('file')->move('files/'.$data['user']->username,$data['request']->type."_".$data['request']->name);
                            return response()->json(['Impacto'=>$proyecto->Impacto,'Archivo'=>$data['archivo']]);
                        });
                    }
                    else
                    {
                        $proyecto->Impacto()->save($impacto);
                        return response()->json(['Impacto'=>$impacto]);
                    }

                }
                return response()->json(['message'=>'impacto_already_exists'],500);

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
            $proyecto->load('Impacto');
                if($proyecto->Impacto!=null)
                {
                    $request->Impacto = $this->processValue($request->Impacto);
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
                            $proyecto->Impacto->fill($request->Impacto);
                            $proyecto->Impacto->save();
                            $proyecto->Impacto->load('Archivos');

                            //Ponemos el tipo de archivo junto con su ruta
                            $tipo = TipoArchivo::where('Titulo',$request->type)->first();
                            $ruta = "files/".$user->username."/".$request->type."_".$request->name;

                            //Obtenemos el archivo viejo
                            $storedFile = Archivos::where('idImpacto',$proyecto->Impacto->id)->where('idTipoArchivo',$tipo->id)->first();

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
                                $proyecto->Impacto->Archivos()->save($archivo);
                            }

                            //Movemos el archivo
                            $request->file('file')->move('files/'.$user->username,$request->type."_".$request->name);

                        });
                        $proyecto->load('Impacto');
                        $tipo = TipoArchivo::where('Titulo',$request->type)->first();
                        $storedFile = Archivos::where('idImpacto',$proyecto->Impacto->id)->where('idTipoArchivo',$tipo->id)->first();
                        return response()->json(
                            ['Impacto'=>$proyecto->Impacto,'Archivo'=>$storedFile]);
                    }
                    else
                    {
                        $proyecto->Impacto->fill($request->Impacto);
                        $proyecto->Impacto->save();
                        return response()->json(['Impacto'=>$proyecto->Impacto]);
                    }


                }
                return response()->json(['message'=>'impacto_not_found'],404);

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
        try {
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $proyecto = Proyecto::validateProyecto($idProyecto, $user, $whoIs, $idOrganizacion);
            $proyecto->load('Impacto');

            if ($proyecto->Impacto != null) {
                $proyecto->Impacto->load('Archivos');
                return response()->json($proyecto->Impacto);
            }
            return response()->json(['message' => 'impacto_not_found'], 500);
        } catch (QueryException $e) {
            return response()->json(['message' => 'server_error', 'exception' => $e->getMessage()], 500);
        } catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (UnauthorizedException $e) {
            return response()->json(['unauthorized'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
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
                    ->join('ImpactoYComercializacion','Archivos.idImpacto','=','ImpactoYComercializacion.id')
                    ->join('Proyecto','ImpactoYComercializacion.idProyecto','=','Proyecto.id')
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
