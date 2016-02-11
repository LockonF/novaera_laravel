<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Models\ProgramaFondeo;
use App\Models\ProgramaFondeoDescriptor;
use App\Models\Descriptor;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;
use Tymon\JWTAuth\Exceptions;

class ProgramaFondeoController extends Controller
{


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showConvocatoriasAsociadas($id)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $convocatorias = ProgramaFondeo::Convocatorias_Asociadas($id);
            if($convocatorias==null)
            {
                return response()->json(['convocatoria_not_found'],500);
            }
            return response()->json(['Convocatoria'=>$convocatorias]);
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
            $programaFondeo = ProgramaFondeo::with('Modalidad')->find($id);
            if($programaFondeo!=null)
            return response()->json($programaFondeo);
            return response()->json(['message'=>'programa_fondeo_not_found'],404);
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
            $programaFondeo = ProgramaFondeo::get();
            return response()->json(['ProgramaFondeo'=>$programaFondeo]);
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

    public function showModalidades($id)
    {
        try{
            $programaFondeo = ProgramaFondeo::with('Modalidad')->find($id);
            if($programaFondeo!=null)
            {
                return response()->json($programaFondeo->Modalidad);
            }
            return response()->json(['message'=>'programa_fondeo_not_found'],404);
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
            $user = AuthenticateController::checkUser('Supervisor');

            return DB::transaction(function() use($request){
                $programaFondeo = new ProgramaFondeo($request->all());
                $programaFondeo->save();
                $programaFondeo->Archivos = new \stdClass;
                if($request->DescripcionFile!=null)
                {
                    $programaFondeo->Archivos->DescripcionFile = 'fondeos/'.$programaFondeo->id.'/'.'Descripcion_'.$request->DescripcionFile->getClientOriginalName();
                    $request->DescripcionFile->move('files/fondeos/'.$programaFondeo->id,"Acta_".$request->DescripcionFile->getClientOriginalName());
                }
                else
                {
                    $programaFondeo->Archivos->DescripcionFile = null;
                }
                if($request->RubrosDeApoyoFile!=null)
                {
                    $programaFondeo->Archivos->RubrosDeApoyoFile = 'fondeos/'.$programaFondeo->id.'/'.'RubrosDeApoyo_'.$request->RubrosDeApoyoFile->getClientOriginalName();
                    $request->RubrosDeApoyoFile->move('files/fondeos/'.$programaFondeo->id,"Acta_".$request->RubrosDeApoyoFile->getClientOriginalName());
                }
                else
                {
                    $programaFondeo->Archivos->RubrosDeApoyoFile = null;
                }
                if($request->CriteriosDeElegibilidadFile!=null)
                {
                    $programaFondeo->Archivos->CriteriosDeElegibilidadFile = 'fondeos/'.$programaFondeo->id.'/'.'CriteriosDeElegibilidadFile_'.$request->CriteriosDeElegibilidadFile->getClientOriginalName();
                    $request->CriteriosDeElegibilidadFile->move('files/fondeos/'.$programaFondeo->id,"Acta_".$request->CriteriosDeElegibilidadFile->getClientOriginalName());
                }
                else
                {
                    $programaFondeo->Archivos->CriteriosDeElegibilidadFile = null;
                }

                $programaFondeo->Archivos = json_encode($programaFondeo->Archivos);
                $programaFondeo->save();

                return response()->json($programaFondeo);


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

    public function destroy($id)
    {
        try{
             AuthenticateController::checkUser('Supervisor');
             $programaFondeo =ProgramaFondeo::find($id);
             if($programaFondeo!=null)
             {
                 $programaFondeo->delete();
                 return response()->json(['message'=>'success']);
             }
             return response()->json(['message'=>'programa_fondeo_not_found'],404);
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
            AuthenticateController::checkUser('Supervisor');
                return DB::transaction(function() use($request,$id){
                    $programaFondeo =ProgramaFondeo::find($id);
                    if($programaFondeo!=null) {

                        if($programaFondeo->Archivos==null)
                        {
                            $programaFondeo->Archivos = new \stdClass;
                        }
                        else
                        {
                            $programaFondeo->Archivos = json_decode($programaFondeo->Archivos);
                        }
                        if ($request->DescripcionFile != null) {
                            if($programaFondeo->Archivos->DescripcionFile!=null)
                            {
                                try{
                                    Storage::delete($programaFondeo->Archivos->DescripcionFile);
                                }catch(FileNotFoundException $e){
                                    DB::rollBack();
                                    return response()->json(['file_not_found'],500);
                                }
                            }

                            $programaFondeo->Archivos->DescripcionFile = 'fondeos/' . $programaFondeo->id . '/' . 'Descripcion_' . $request->DescripcionFile->getClientOriginalName();
                            $request->DescripcionFile->move('files/fondeos/' . $programaFondeo->id, "Descripcion_" . $request->DescripcionFile->getClientOriginalName());
                        }
                        if ($request->RubrosDeApoyoFile != null) {
                            if($programaFondeo->Archivos->RubrosDeApoyoFile!=null)
                            {
                                try{
                                    Storage::delete($programaFondeo->Archivos->RubrosDeApoyoFile);
                                }catch(FileNotFoundException $e){
                                    DB::rollBack();
                                    return response()->json(['file_not_found'],500);
                                }
                            }
                            $programaFondeo->Archivos->RubrosDeApoyoFile = 'fondeos/' . $programaFondeo->id . '/' . 'RubrosDeApoyo_' . $request->RubrosDeApoyoFile->getClientOriginalName();
                            $request->RubrosDeApoyoFile->move('files/fondeos/' . $programaFondeo->id, "RubrosDeApoyo_" . $request->RubrosDeApoyoFile->getClientOriginalName());
                        }
                        if ($request->CriteriosDeElegibilidadFile != null) {
                            if ($programaFondeo->Archivos->CriteriosDeElegibilidadFile != null)
                            {

                                try {
                                    Storage::delete($programaFondeo->Archivos->CriteriosDeElegibilidadFile);
                                } catch (FileNotFoundException $e) {
                                    DB::rollBack();
                                    return response()->json(['file_not_found'], 500);
                                }
                            }
                            $programaFondeo->Archivos->CriteriosDeElegibilidadFile = 'fondeos/' . $programaFondeo->id . '/' . 'CriteriosDeElegibilidadFile_' . $request->CriteriosDeElegibilidadFile->getClientOriginalName();
                            $request->CriteriosDeElegibilidadFile->move('files/fondeos/' . $programaFondeo->id, "CriteriosDeElegibilidadFile_" . $request->CriteriosDeElegibilidadFile->getClientOriginalName());
                        }
                        $programaFondeo->fill($request->all());
                        $programaFondeo->Archivos = json_encode($programaFondeo->Archivos);
                        $programaFondeo->save();
                        return response()->json($programaFondeo);
                    }
                    return response()->json(['message'=>'programa_fondeo_not_found'],404);

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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function addDescriptor(Request $request)
    {
        try{
            AuthenticateController::checkUser(null);
            $programaFondeo =ProgramaFondeo::find($request->idProgramaFondeo);
            if($programaFondeo==null)
            {
                return response()->json(['message'=>'programa_fondeo_not_found'],404);
            }
            $descriptor = Descriptor::find($request->idDescriptor);
            $programaFondeo->Descriptor()->save($descriptor,$request->all());
            $descriptores = [];
            foreach($programaFondeo->Descriptor as $descriptor)
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
            $programaFondeo =ProgramaFondeo::find($id);
            if($programaFondeo==null)
            {
                return response()->json(['message'=>'programa_fondeo_not_found'],404);
            }
            $descriptores = [];
            foreach($programaFondeo->Descriptor as $descriptor)
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

    public function modDescriptor(Request $request,$id)
    {
        try{
            $user = AuthenticateController::checkUser('Supervisor');

            $programa_descriptor = ProgramaFondeoDescriptor::find($id);

            if($programa_descriptor==null)
            {
                return response()->json(['programa_descriptor_not_found'],404);
            }

            $programa_descriptor->observaciones = $request->observaciones;
            $programa_descriptor->save();

            $programaFondeo = ProgramaFondeo::find($programa_descriptor->idProgramaFondeo);
            $programaFondeo->load('Descriptor');
            foreach($programaFondeo->Descriptor as $descriptor)
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

    public function detachDescriptor($idPrograma,$id)
    {
        try{
            $user = AuthenticateController::checkUser('Supervisor');
            $programaFondeo =ProgramaFondeo::find($idPrograma);
            if($programaFondeo==null)
            {
                return response()->json(['message'=>'programa_fondeo_not_found'],404);
            }

                $descriptor = ProgramaFondeoDescriptor::find($id);
                $descriptor->delete();


                $programaFondeo->load('Descriptor');
                foreach($programaFondeo->Descriptor as $descriptor)
                {
                    $descriptores[] = $descriptor;
                }
                return response()->json(['Descriptor'=>$descriptores]);

            return response()->json(['message'=>'descriptor_not_found'],404);
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
