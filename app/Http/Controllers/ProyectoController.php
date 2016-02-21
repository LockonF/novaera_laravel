<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidAccessException;
use App\Exceptions\NotFoundException;
use App\Models\Impacto;
use App\Models\Organizacion;
use App\Models\Persona;
use App\Models\Proyecto;
use App\Models\Descriptor;
use App\Models\ProyectoDescriptor;
use App\Models\ProyectoModalidad;
use App\Models\ProyectoResultado;
use App\Models\ProyectoTRL;
use App\Models\TRL;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions;


class ProyectoController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeByPerson(Request $request,$type='Persona',$idOrganizacion=null)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $new_proyecto = new Proyecto($request->all());
            $user->load('Persona');
            if($user->Persona==null)
            {
                return response()->json(['message'=>'associated_persona_not_found'],500);
            }
            if($type=='Persona')
            {
                $user->Persona->Proyecto()->save($new_proyecto,['Owner'=>1,'WritePermissions'=>1]);
            }
            else
            {
                $organizacion = $user->Persona->Organizacion()->where('idOrganizacion',$idOrganizacion)->first();
                if($organizacion==null)
                {
                    return response()->json(['message'=>'associated_organizacion_not_found'],500);
                }
                $organizacion->Proyecto()->save($new_proyecto,['Owner'=>1]);
            }


            return response()->json($new_proyecto,200);
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }
    }


    /**
     * @param string $type
     * @param null $idOrganizacion
     * @return \Illuminate\Http\JsonResponse
     */


    public function showByDate($type='Persona',$idOrganizacion=null)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            if($type=='Persona')
            {
                $proyectos = $user->Persona->Proyecto()->orderBy('created_at','desc')->get();
                return response()->json(['Proyectos'=>$proyectos],200);
            }
            else
            {
                $organizacion = $user->Persona->Organizacion()->find($idOrganizacion);
                if($organizacion==null)
                {
                    return response()->json(['message'=>'organizacion_not_found'],500);
                }
                $proyectos = $organizacion->Proyecto()->orderBy('created_at','desc')->get();
                return response()->json(['Proyectos'=>$proyectos]);
            }

        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }
    }





    /**
     * @return \Illuminate\Http\JsonResponse
     */

    public function showProjects($type='Persona',$idOrganizacion=null)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            if($type=='Persona')
            {
                $user->Persona->load('Proyecto');
                return response()->json(['Proyectos'=>$user->Persona->Proyecto],200);
            }
            else
            {
                $organizacion = $user->Persona->Organizacion()->find($idOrganizacion);
                if($organizacion==null)
                {
                    return response()->json(['message'=>'organizacion_not_found'],500);
                }
                $organizacion->load('Proyecto');
                return response()->json(['Proyectos'=>$organizacion->Proyecto]);
            }

        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }
    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function showOneProject($type,$id,$idOrganizacion=null)
    {

        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $proyecto = Proyecto::validateProyecto($id,$user,$type,$idOrganizacion);
                return response()->json($proyecto);
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        } catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }

    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function editProject(Request $request, $type = 'Persona',$id=null,$idOrganizacion=null)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $proyecto = Proyecto::validateProyecto($id,$user,$type,$idOrganizacion);
            $proyecto->fill($request->all());
            $proyecto->save();
            return response()->json($proyecto,200);

        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }
        catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function removeProject($type='Persona',$id=null,$idOrganizacion=null)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $proyecto = Proyecto::validateProyecto($id,$user,$type,$idOrganizacion);
            $proyecto->delete();
            return response()->json(['message'=>'success'],200);

        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCollaborator(Request $request)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $persona =   Persona::find($request->idPersona);
            $proyecto  = $user->Persona->Proyecto()->where('Proyecto.id',$request->idProyecto)->first();
            if($proyecto == null || $persona == null)
            {
                return response()->json(['message'=>'server_error'],500);
            }
            if($proyecto->pivot->Owner!=1)
            {
                return response()->json(['message'=>'owner_not_matching'],500);
            }
            else
            {
                $persona->Proyecto()->save($proyecto);
                return response()->json(['message'=>'success'],200);
            }
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function removeCollaborator(Request $request)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $persona =   Persona::find($request->idPersona);
            $proyecto  = $user->Persona->Proyecto()->where('Proyecto.id',$request->idProyecto)->first();
            if($proyecto == null || $persona == null)
            {
                return response()->json(['message'=>'server_error'],500);
            }
            if($proyecto->pivot->Owner!=1)
            {
                return response()->json(['message'=>'owner_not_matching'],500);
            }
            else
            {
                $proyecto_persona = $proyecto->Persona()->where('Persona.id',$request->idPersona)->where('Owner',0)->first();
                if($proyecto_persona==null)
                {
                    return response()->json(['message'=>'server_error'],200);
                }
                $proyecto_persona->delete();
                return response()->json(['message'=>'success'],200);

            }
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function addToModalidad(Request $request)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $proyecto  = $user->Persona->Proyecto()->where('Proyecto.id',$request->idProyecto)->first();
            if($proyecto == null)
            {
                return response()->json(['message'=>'server_error'],500);
            }
            if($proyecto->pivot->Owner!=1)
            {
                return response()->json(['message'=>'owner_not_matching'],500);
            }
            else
            {
                $proyecto->Modalidad()->save($proyecto,$request->all());
                $proyecto->load('Modalidad');

                return response()->json($proyecto->Modalidad);
            }
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFromModalidad(Request $request)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $proyecto  = $user->Persona->Proyecto()->with('Modalidad')->where('Proyecto.id',$request->idProyecto)->first();
            if($proyecto == null)
            {
                return response()->json(['message'=>'proyecto_not_found'],404);
            }
            if($proyecto->pivot->Owner!=1)
            {
                return response()->json(['message'=>'owner_not_matching'],500);
            }
            else
            {
                $proyecto_modalidad = ProyectoModalidad::where('id',$request->id)->first();
                if($proyecto_modalidad!=null)
                {
                    $proyecto_modalidad->delete();
                    return response()->json(['message'=>'success'],200);
                }
                return response()->json(['message'=>'proyecto_modalidad_not_found'],404);

            }
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function showModalidades($id)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $proyecto  = $user->Persona->Proyecto()->with('Modalidad')->where('Proyecto.id',$id)->first();
            if($proyecto == null)
            {
                return response()->json(['message'=>'proyecto_not_found'],404);
            }
            else
            {
                $proyecto->load('Modalidad');
                return response()->json($proyecto->Modalidad,200);

            }
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function showEtapas($id,$whoIs = 'Persona',$idOrganizacion=null)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $proyecto = Proyecto::validateProyecto($id, $user, $whoIs, $idOrganizacion);
            $proyecto = Proyecto::find($id);
            if($proyecto == null)
            {
                return response()->json(['message'=>'proyecto_not_found'],404);
            }
            else
            {
                $proyecto->load('EtapaProyecto');
                foreach($proyecto->EtapaProyecto as $etapa)
                {
                    $etapa->load('tasks');
                }
                return response()->json(['EtapaProyecto'=>$proyecto->EtapaProyecto],200);

            }
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }
    }



    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function addTRL(Request $request,$whoIs='Persona',$idOrganizacion=null)
    {

        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $proyecto = Proyecto::validateProyecto($request->idProyecto, $user, $whoIs, $idOrganizacion);

            $proyecto->load('TRL');
            $trl = TRL::where('id', $request->idTRL)->first();
            if ($trl == null) {
                return response()->json(['message' => 'trl_not_found'], 404);
            }
            $proyecto->TRL()->save($trl, $request->Info);
            $proyecto = Proyecto::find($proyecto->id);
            $proyecto->load('TRL');
            $data = [];
            foreach ($proyecto->TRL as $trl) {
                $data[] = $trl->pivot;
            }
            return response()->json(['TRL' => $data]);
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function viewTRL($id,$whoIs='Persona',$idOrganizacion=null)
    {

        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $proyecto = Proyecto::validateProyecto($id, $user, $whoIs, $idOrganizacion);
            $proyecto->load('TRL');
            $data = [];
            $i = 0;
            foreach ($proyecto->TRL as $trl) {
                $data[$i] = (object) $trl->pivot['attributes'];
                $data[$i]->TRL = $trl['attributes'];
                $i++;

            }

            return response()->json(['TRL' => $data]);
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function deleteTRLs(Request $request,$whoIs='Persona',$idOrganizacion=null)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $proyecto = Proyecto::validateProyecto($request->idProyecto, $user, $whoIs, $idOrganizacion);
            return DB::transaction(function () use ($request) {
                foreach ($request->ProyectoTRL as $TRL) {
                    ProyectoTRL::find($TRL['id'])->delete();
                }
                $trls = ProyectoTRL::where('idProyecto', $request->idProyecto)->get();

                return response()->json(['TRL' => $trls]);
            });
        }catch (QueryException $e)
        {return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }

    }


    /**
     * @param Request $request
     * @param string $whoIs
     * @param null $idOrganizacion
     * @return \Illuminate\Http\JsonResponse
     */

    public function addResult(Request $request,$whoIs = 'Persona',$idOrganizacion=null)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $proyecto = Proyecto::validateProyecto($request->idProyecto, $user, $whoIs, $idOrganizacion);
            $resultado  = new ProyectoResultado($request->Resultado);
            $resultado->PaisesProteccion = json_encode($resultado->PaisesProteccion);
            $resultado->save();

            $proyecto->load('ProyectoTRL');
            foreach ($proyecto->ProyectoTRL as $TRL) {
                $TRL->load('ProyectoResultado');
            }
            return response()->json($proyecto->ProyectoTRL);
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function editResult(Request $request)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $proyecto  = $user->Persona->Proyecto()->where('Proyecto.id',$request->idProyecto)->first();
            if($proyecto == null)
            {
                return response()->json(['message'=>'proyecto_not_found'],500);
            }
            if($proyecto->pivot->Owner!=1)
            {
                return response()->json(['message'=>'owner_not_matching'],500);
            }
            else
            {
                $resultado  = ProyectoResultado::find($request->id);

                if($resultado==null)
                {
                    return response()->json(['message'=>'resultado_not_found'],404);
                }
                $resultado->fill($request->all());
                $resultado->save();
                return response()->json($resultado);

            }
        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }
    }






    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */


    public function showResults($id,$type=null,$whoIs='Persona',$idOrganizacion=null)
    {
        try
        {

            $user = AuthenticateController::checkUser(null);
            $user->load('Persona');
            $proyecto = Proyecto::validateProyecto($id, $user, $whoIs, $idOrganizacion);
            $results = DB::table('ProyectoResultado')
                ->join('ProyectoTRL', 'ProyectoResultado.idProyectoTRL', '=', 'ProyectoTRL.id')
                ->select('ProyectoResultado.*')
                ->where('ProyectoTRL.idProyecto',$id);

            if ($type != null && $type!='Todos')
            {
                $results->where('ProyectoResultado.Tipo', $type);
            }
            $results = $results->get();
            return response()->json(['Resultado' => $results]);

        }
        catch (QueryException $e)
        {
        return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        }catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }catch (InvalidAccessException $e) {
            return response()->json(['invalid_write_permissions'], $e->getStatusCode());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function addDescriptor(Request $request,$whoIs='Persona',$idOrganizacion=null)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $proyecto =Proyecto::validateProyecto($request->idProyecto,$user,$whoIs,$idOrganizacion);
            $descriptor = Descriptor::find($request->idDescriptor);

            $proyecto->Descriptor()->save($descriptor,$request->all());
            $descriptores = [];
            foreach($proyecto->Descriptor as $descriptor)
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
        }catch (NotFoundException $e) {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function showAllDescriptor($id,$whoIs='Persona',$idOrganizacion=null)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $proyecto =Proyecto::validateProyecto($id,$user,$whoIs,$idOrganizacion);
            if($proyecto==null)
            {
                return response()->json(['message'=>'proyecto_not_found'],404);
            }
            $descriptores = [];
            foreach($proyecto->Descriptor as $descriptor)
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

    public function updateDescriptor(Request $request,$id,$whoIs='Persona',$idOrganizacion=null)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $proyecto = Proyecto::validateProyecto($request->idProyecto,$user,$whoIs,$idOrganizacion);
            $proyectoDescriptor = ProyectoDescriptor::find($id);
            if($proyectoDescriptor==null)
            {
                return response()->json(['descriptor_proyecto_not_found'],404);
            }

            $proyectoDescriptor->fill($request->all());
            $proyectoDescriptor->save();

            return response()->json($proyectoDescriptor);

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

    public function detachDescriptor($idProyecto,$id,$whoIs='Persona',$idOrganizacion=null)
    {
        try{
            $user = AuthenticateController::checkUser(null);
            $proyecto =Proyecto::validateProyecto($idProyecto,$user,$whoIs,$idOrganizacion);
            if($proyecto ==null)
            {
                return response()->json(['message'=>'proyecto_fondeo_not_found'],404);
            }
            $proyectodes = ProyectoDescriptor::find($id);
            $proyectodes->delete();
            $proyecto->load('Descriptor');
            $descriptores = [];
            foreach($proyecto->Descriptor as $descriptor)
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
