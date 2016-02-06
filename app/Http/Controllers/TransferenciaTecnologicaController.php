<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Exceptions\UnauthorizedException;
use App\Models\Archivos;
use App\Models\TransferenciaTecnologica;
use App\Models\Proyecto;
use App\Models\TipoArchivo;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;
use Tymon\JWTAuth\Exceptions;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TransferenciaTecnologicaController extends Controller
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
            $proyecto = Proyecto::validateProyecto($request->idProyecto, $user, $whoIs, $idOrganizacion);
            //$proyecto  = $user->Persona->Proyecto()->where('Proyecto.id',$request->idProyecto)->first();
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
                $proyecto->load('TransferenciaTecnologica');
                $request->TransferenciaTecnologica = $this->processValue($request->TransferenciaTecnologica);
                $transferenciaTecnologica = new TransferenciaTecnologica($request->TransferenciaTecnologica);
                if($request->type!=null)
                {
                    $data['user']=$user;
                    $data['proyecto']=$proyecto;
                    $data['request']=$request;
                    $ruta = $user->username."/".$request->type."_".$request->name;
                    $tipo = TipoArchivo::where('Titulo',$request->type)->first();
                    $archivo = new Archivos(["Ruta"=>$ruta,"idTipoArchivo"=>$tipo->id]);
                    $data['transferenciaTecnologica']=$transferenciaTecnologica;
                    $data['archivo']=$archivo;

                    return DB::transaction(function () use($data) {
                        $proyecto = $data['proyecto'];
                        $proyecto->TransferenciaTecnologica()->save($data['transferenciaTecnologica']);
                        $proyecto = Proyecto::find($proyecto->id);
                        $proyecto->load('TransferenciaTecnologica');
                        $proyecto->TransferenciaTecnologica->load('Archivos');
                        $proyecto->TransferenciaTecnologica->Archivos()->save($data['archivo']);
                        $data['request']->file('file')->move('files/'.$data['user']->username,$data['request']->type."_".$data['request']->name);
                        return response()->json(['TransferenciaTecnologica'=>$proyecto->TransferenciaTecnologica,'Archivo'=>$data['archivo']]);
                    });
                }
                else
                {
                    $proyecto->TransferenciaTecnologica()->save($transferenciaTecnologica);
                    return response()->json(['TransferenciaTecnologica'=>$transferenciaTecnologica]);
                }
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

    public function update(Request $request,$whoIs = 'Persona',$idOrganizacion=null)
    {
        try{

            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $proyecto = Proyecto::validateProyecto($request->idProyecto, $user, $whoIs, $idOrganizacion);
            //$proyecto  = $user->Persona->Proyecto()->where('Proyecto.id',$request->idProyecto)->first();
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
                $transferencia = TransferenciaTecnologica::find($request->TransferenciaTecnologica['id']);
                if($transferencia!=null)
                {
                    $request->TransferenciaTecnologica = $this->processValue($request->TransferenciaTecnologica);
                    $transferencia->fill($request->TransferenciaTecnologica);
                    if($request->type!=null)
                    {
                        $data['user']=$user;
                        $data['request']=$request;
                        $data['transferencia'] = $transferencia;

                        DB::transaction(function () use($data) {
                            $user= $data['user'];
                            $transferencia = $data['transferencia'];
                            $request = $data['request'];

                            //Guardamos la ejecución
                            $transferencia->save();
                            $transferencia->load('Archivos');

                            //Ponemos el tipo de archivo junto con su ruta
                            $tipo = TipoArchivo::where('Titulo',$request->type)->first();
                            $ruta = $user->username."/".$request->type."_".$request->name;

                            //Obtenemos el archivo viejo
                            $storedFile = Archivos::where('idTransferenciaTecnologica',$transferencia->id)->where('idTipoArchivo',$tipo->id)->first();

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
                                $transferencia->Archivos()->save($archivo);
                            }

                            //Movemos el archivo
                            $request->file('file')->move('files/'.$user->username,$request->type."_".$request->name);

                        });
                        $tipo = TipoArchivo::where('Titulo',$request->type)->first();
                        $storedFile = Archivos::where('idTransferenciaTecnologica',$transferencia->id)->where('idTipoArchivo',$tipo->id)->first();
                        return response()->json(
                            ['TransferenciaTecnologica'=>$transferencia,'Archivo'=>$storedFile]);
                    }
                    else
                    {
                        $transferencia->save();
                        return response()->json(['TransferenciaTecnologica'=>$transferencia]);
                    }
                }
                return response()->json(['message'=>'transferenciaTecnologica_not_found'],404);
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
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroy($id,$whoIs = 'Persona',$idOrganizacion=null)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $proyecto = Proyecto::validateProyecto($id, $user, $whoIs, $idOrganizacion);
            $transferencia = TransferenciaTecnologica::find($id);
            if($transferencia==null)
            {
                return response()->json(['message'=>'transferenciaTecnologica_not_found'],404);
            }
            else
            {
                $transferencia->delete();
                return response()->json(['message'=>'success']);
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
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */


    public function show($id)
    {
        try{
            AuthenticateController::checkUser(null);
            $transferencia  = TransferenciaTecnologica::find($id);
            if($transferencia!=null)
            {
                $transferencia->load('Archivos');
                return response()->json($transferencia);
            }
                return response()->json(['message'=>'transferenciaTecnologica_not_found'],500);

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

    public function showAll($idProyecto,$whoIs = 'Persona',$idOrganizacion=null)
    {
        try{
           AuthenticateController::checkUser(null);
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $proyecto = Proyecto::validateProyecto($idProyecto, $user, $whoIs, $idOrganizacion);
            //$proyecto  = Proyecto::where('Proyecto.id',$idProyecto)->first();
            if($proyecto == null)
            {
                return response()->json(['message'=>'server_error'],500);
            }
            else
            {
                $proyecto->load('TransferenciaTecnologica');
                if($proyecto->TransferenciaTecnologica!=null)
                {
                    $proyecto->TransferenciaTecnologica->load('Archivos');
                    return response()->json(['TransferenciaTecnologica'=>$proyecto->TransferenciaTecnologica]);
                }
                return response()->json(['message'=>'transferenciaTecnologica_not_found'],500);
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
                    ->join('TransferenciaTecnologica','Archivos.idTransferenciaTecnologica','=','TransferenciaTecnologica.id')
                    ->join('Proyecto','TransferenciaTecnologica.idProyecto','=','Proyecto.id')
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
