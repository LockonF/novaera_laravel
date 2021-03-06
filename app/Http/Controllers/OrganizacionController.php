<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Models\DescriptorOrganizacion;
use App\Models\Organizacion;
use App\Models\Persona;
use App\Models\Descriptor;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;
use Tymon\JWTAuth\Exceptions;


class OrganizacionController extends Controller
{


    public function valiateOrganizaciones(Request $request)
    {
        try {
            AuthenticateController::checkUser('Supervisor');
            return DB::transaction(function() use ($request)
            {
                foreach($request->Organizacion as $id)
                {
                    $organizacion = Organizacion::find($id);
                    if($organizacion==null)
                    {
                        DB::rollBack();
                        return response()->json(['message'=>'organizacion_'.$id.'not_found'],500);

                    }
                    $organizacion->isValidated = 1;
                    $organizacion->save();

                }
                $organizaciones = Organizacion::all();
                foreach($organizaciones as $organizacion)
                {$organizacion->Archivos = json_decode($organizacion->Archivos);}
                return response()->json(['Organizacion'=>$organizaciones]);
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



    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function showOneGeneral($id)
    {
        try {
            AuthenticateController::checkUser(null);
            $organizacion = Organizacion::find($id);
            $organizacion->Archivos = json_decode($organizacion->Archivos);
            if ($organizacion == null) {
                return response()->json(['message' => 'organizacion_not_found'],404);
            }
            return response()->json($organizacion);
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
     * Función para mostrar todas las organizaciones
     * @return \Illuminate\Http\JsonResponse
     * @throws Exceptions\JWTException
     * @throws Exceptions\TokenExpiredException
     * @throws Exceptions\TokenInvalidException
     */

    public function showAllGeneral()
    {
        try{
            AuthenticateController::checkUser(null);
            $organizaciones = Organizacion::all();
            foreach($organizaciones as $organizacion)
            {$organizacion->Archivos = json_decode($organizacion->Archivos);}
            return response()->json(['Organizacion'=>$organizaciones]);
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
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $organizaciones = $user->Persona->Organizacion()->get();
            foreach($organizaciones as $organizacion)
            {
                $organizacion->Archivos = json_decode($organizacion->Archivos);
            }

            return response()->json(['Organizacion'=>$organizaciones]);

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
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $organizacion = $user->Persona->Organizacion()
                ->where('Persona_Organizacion.idPersona',$user->Persona->id)
                ->where('Persona_Organizacion.idOrganizacion',$id)->first();

            if($organizacion == null) {
                $organizacion->Archivos = json_decode($organizacion->Archivos);
                return response()->json(['message' => 'organizacion_not_found'],404);
            }
            return response()->json($organizacion);

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
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            if($user->Persona!=null)
            {
                $organizacion = new Organizacion($request->Organizacion);
                $personaData = $request->Datos;
                $personaData['Owner'] = 1;
                $personaData['WritePermissions']=1;

                $user->Persona->Organizacion()->save($organizacion,$personaData);
                return response()->json($organizacion);
            }
            return response()->json(['message'=>'persona_not_found'],404);
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
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');

                $record= $user->Persona->Organizacion()->where('Organizacion.id',$id)->where('idPersona',$user->Persona->id)->where('Owner',1)->first();
                if($record!=null)
                {
                    $record->delete();
                    return response()->json(['message'=>'success']);
                }
                return response()->json(['message'=>'only owner can delete'],500);

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
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');

            $record= $user->Persona->Organizacion()->where('Organizacion.id',$id)->where('idPersona',$user->Persona->id)->where('Owner',1)->first();
            if($record!=null)
            {
                $record->fill($request->all());
                $record->save();
                return response()->json($record);
            }
            return response()->json(['message'=>'only owner can update'],500);

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

    public function upload(Request $request)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $record= $user->Persona->Organizacion()->where('Organizacion.id',$request->idOrganizacion)->where('idPersona',$user->Persona->id)->where('Owner',1)->first();
            if($record!=null)
            {
                $record->Archivos = json_decode($record->Archivos);
                return DB::transaction(function() use($record,$request){
                    if($request->ActaFile!=null)
                    {
                        if($record->Archivos->ActaFile!=null)
                        {
                            try{
                                Storage::delete($record->Archivos->ActaFile);
                            }catch(FileNotFoundException $e){}
                        }
                        $record->Archivos->ActaFile = 'files/Organizaciones/'.$record->id.'/'.'Acta_'.$request->ActaFile->getClientOriginalName();
                        $request->ActaFile->move('files/Organizaciones/'.$record->id,"Acta_".$request->ActaFile->getClientOriginalName());
                    }
                    if($request->RFCFile!=null)
                    {
                        if($record->Archivos->RFCFile!=null)
                        {
                            try{
                                Storage::delete($record->Archivos->RFCFile);
                            }catch(FileNotFoundException $e){}
                        }
                        $record->Archivos->RFCFile = 'files/Organizaciones/'.$record->id.'/'.'RFC_'.$request->RFCFile->getClientOriginalName();
                        $request->RFCFile->move('files/Organizaciones/'.$record->id,"RFC_".$request->RFCFile->getClientOriginalName());
                    }
                    if($request->RENIECyTFile!=null)
                    {
                        if($record->Archivos->RENIECyTFile!=null)
                        {
                            try{
                                Storage::delete($record->Archivos->RENIECyTFile);
                            }catch(FileNotFoundException $e){}
                        }
                        $record->Archivos->RENIECyTFile = 'files/Organizaciones/'.$record->id.'/'.'RENIECyT_'.$request->RENIECyTFile->getClientOriginalName();
                        $request->RENIECyTFile->move('files/Organizaciones/'.$record->id,"RENIECyT_".$request->RENIECyTFile->getClientOriginalName());
                    }
                    $record->Archivos = json_encode($record->Archivos);
                    $record->save();

                    return response()->json($record);
                });

            }
            return response(['organizacion_not_found'],404)->json();

        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */


    public function addPersona(Request $request)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $persona = Persona::find($request->idPersona);
            $organizacion= $user->Persona->Organizacion()->where('Organizacion.id',$request->idOrganizacion)->where('idPersona',$user->Persona->id)->where('Owner',1)->first();
            if($persona==null)
            {
                return response()->json(['message'=>'persona_not_found'],500);
            }
            if($organizacion==null)
            {
                return response()->json(['message'=>'organizacion_not_found'],500);
            }
            $organizacion->Persona()->save($persona,$request->Datos);
            return response()->json(['message'=>'success']);

        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
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
     * @throws Exceptions\JWTException
     * @throws Exceptions\TokenExpiredException
     * @throws Exceptions\TokenInvalidException
     */

    public function showPersonasOrganizacion($id)
    {
        try {
            $user = AuthenticateController::checkUser(null);
            $organizacion = $user->Persona->Organizacion()->find($id);
            if ($organizacion == null) {
                return response()->json(['message' => 'organizacion_not_found'], 404);
            }
            $organizacion->load('Persona');
            foreach ($organizacion->Persona as $persona) {
                $persona->load('Contacto');
            }
            return response()->json(['Persona' => $organizacion->Persona]);
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }

    /**
     * @param $idOrganizacion
     * @param $idPersona
     * @return \Illuminate\Http\JsonResponse
     */

    public function removePersonaOrganizacion($idOrganizacion,$idPersona)
    {
        try
        {
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
                $organizacion= $user->Persona->Organizacion()->where('Organizacion.id',$idOrganizacion)->where('idPersona',$user->Persona->id)->where('Owner',1)->first();
            if($organizacion!=null) {
                $isdone = DB::table('Persona_Organizacion')
                    ->where('idOrganizacion', $idOrganizacion)
                    ->where('idPersona', $idPersona)
                    ->where('Owner', 0)
                    ->delete();
                if($isdone)
                    return response()->json(['message'=>'success']);
                return response()->json(['message'=>'persona_not_found'],404);
            }
            return response()->json(['organizacion_not_found_or_not_owner'],500);
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }

    public function addDescriptor(Request $request)
    {
        try{
            AuthenticateController::checkUser(null);
            $organizacion =Organizacion::find($request->idOrganizacion);
            if($organizacion==null)
            {
                return response()->json(['message'=>'organizacion_not_found'],404);
            }
            $descriptor = Descriptor::find($request->idDescriptor);
            $organizacion->Descriptor()->save($descriptor,$request->all());
            $descriptores = [];
            foreach($organizacion->Descriptor as $descriptor)
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
            $organizacion =Organizacion::find($id);
            if($organizacion==null)
            {
                return response()->json(['message'=>'organizacion_not_found'],404);
            }
            $descriptores = [];
            foreach($organizacion->Descriptor as $descriptor)
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

    public function updateDescriptor(Request $request, $id)
    {
        try{
            $user = AuthenticateController::checkUser('Supervisor');
            $descriptorOrganizacion = DescriptorOrganizacion::find($request->id);

            if($descriptorOrganizacion ==null)
            {
                return response()->json(['descriptor_organizacion_not_found'],404);
            }

            $descriptorOrganizacion->FechaInicio = $request->FechaInicio;
            $descriptorOrganizacion->FechaTermino = $request->FechaTermino;
            $descriptorOrganizacion->TipoResultado = $request->TipoResultado;
            $descriptorOrganizacion->NumeroRegistro = $request->NumeroRegistro;
            $descriptorOrganizacion->save();

            $organizacion = Organizacion::find($request->idOrganizacion);
            $organizacion->load('Descriptor');
            foreach($organizacion->Descriptor as $descriptor)
            {
                $descriptores[] = $descriptor;
            }
            return response()->json(['Descriptor'=>$descriptores]);

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


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function detachDescriptor($idOrganizacion, $id)
    {
        try{
            $user = AuthenticateController::checkUser('Supervisor');
            $organizacion =Organizacion::find($idOrganizacion);
            if($organizacion ==null)
            {
                return response()->json(['message'=>'organizacion_not_found'],404);
            }
            $dorg = DescriptorOrganizacion::find($id);
            $dorg->delete();
            $organizacion->load('Descriptor');
            $descriptores = [];
            foreach($organizacion->Descriptor as $descriptor)
            {
                $descriptores[] = $descriptor;
            }
            return response()->json(['Descriptor'=>$descriptores]);
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
