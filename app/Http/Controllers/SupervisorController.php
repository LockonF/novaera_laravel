<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Models\Organizacion;
use App\Models\Persona;
use App\Models\Proyecto;
use App\Models\TRL;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions;
use App\Http\Controllers\Controller;

class SupervisorController extends Controller
{




    /**
     * @param $idDescriptor
     * @return \Illuminate\Http\JsonResponse
     */
    public function proyectosByDescriptor($idDescriptor){
        try{
            $results = DB::table('Proyecto')
                ->join('ProyectoDescriptor','Proyecto.id','=','ProyectoDescriptor.idProyecto')
                ->join('Descriptor','Descriptor.id','=','ProyectoDescriptor.idDescriptor')
                ->where('Descriptor.id',$idDescriptor)
                ->select('Proyecto.*')
                ->get();

            return response()->json(['Proyecto'=>$results]);

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
        }catch(NotFoundException $e)
        {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }

    /**
     * @param $idTipoDescriptor
     * @return \Illuminate\Http\JsonResponse
     */

    public function countProyectosByTipoDescriptor($idTipoDescriptor)
    {

        try{
            $user = AuthenticateController::checkUser('Supervisor');
            $formattedResults = [];
            $results = DB::table('Proyecto')
                ->join('ProyectoDescriptor','Proyecto.id','=','ProyectoDescriptor.idProyecto')
                ->join('Descriptor','Descriptor.id','=','ProyectoDescriptor.idDescriptor')
                ->join('TipoDescriptor','TipoDescriptor.id','=','Descriptor.idTipoDescriptor')
                ->where('TipoDescriptor.id',$idTipoDescriptor)
                ->groupBy('Descriptor.id')
                ->select(DB::raw('count(Descriptor.id) as Data, Descriptor.Titulo as Labels'))
                ->get();
            if($results !=null)
            {
                foreach($results as $result)
                {
                    $formattedResults['Data'][]=$result->Data;
                    $formattedResults['Labels'][]=$result->Labels;
                }
            }

            return response()->json($formattedResults);


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
        }catch(NotFoundException $e)
        {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }


    /**
     * @param $idTipoDescriptor
     * @return \Illuminate\Http\JsonResponse
     */

    public function countPersonasOrgByTipoDescriptor($idOrganizacion,$idTipoDescriptor)
    {

        try{
            $user = AuthenticateController::checkUser('Supervisor');
            $formattedResults = [];
            $organizacion = Organizacion::find($idOrganizacion);

            $personas = $organizacion->Persona()
                ->select('Persona.id')->lists('id')->toArray();

            $results = DB::table('Persona')
                ->join('Descriptor_Persona','Persona.id','=','Descriptor_Persona.idPersona')
                ->join('Descriptor','Descriptor.id','=','Descriptor_Persona.idDescriptor')
                ->join('TipoDescriptor','TipoDescriptor.id','=','Descriptor.idTipoDescriptor')
                ->where('TipoDescriptor.id',$idTipoDescriptor)
                ->whereIn('Persona.id',$personas)
                ->groupBy('Descriptor.id')
                ->select(DB::raw('count(Persona.id) as Data'),'Descriptor.Titulo as Labels');

            $results=$results    ->get();
            if($results !=null)
            {
                foreach($results as $result)
                {
                    $formattedResults['Data'][]=$result->Data;
                    $formattedResults['Labels'][]=$result->Labels;
                }
            }

            return response()->json($formattedResults);

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
        }catch(NotFoundException $e)
        {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }


    /**
     * @param $idTipoDescriptor
     * @return \Illuminate\Http\JsonResponse
     */

    public function countPersonasByTipoDescriptor($idTipoDescriptor)
    {

        try{
            $user = AuthenticateController::checkUser('Supervisor');
            $formattedResults = [];


            $results = DB::table('Persona')
                ->join('Descriptor_Persona','Persona.id','=','Descriptor_Persona.idPersona')
                ->join('Descriptor','Descriptor.id','=','Descriptor_Persona.idDescriptor')
                ->join('TipoDescriptor','TipoDescriptor.id','=','Descriptor.idTipoDescriptor')
                ->where('TipoDescriptor.id',$idTipoDescriptor)
                ->groupBy('Descriptor.id')
                ->select(DB::raw('count(Persona.id) as Data'),'Descriptor.Titulo as Labels');

            $results=$results    ->get();
            if($results !=null)
            {
                foreach($results as $result)
                {
                    $formattedResults['Data'][]=$result->Data;
                    $formattedResults['Labels'][]=$result->Labels;
                }
            }

            return response()->json($formattedResults);

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
        }catch(NotFoundException $e)
        {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }




    /**
     * @param $idOrganizacion
     * @param $idDescriptor
     * @return \Illuminate\Http\JsonResponse
     */

    public function getOrganizacionPersonasDescriptor($idOrganizacion,$idDescriptor)
    {
        try{
            $user = AuthenticateController::checkUser('Supervisor');
            $personas = DB::table('Persona_Organizacion')
                ->join('Persona','Persona_Organizacion.idPersona','=','Persona.id')
                ->join('Descriptor_Persona','Persona.id','=','Descriptor_Persona.idPersona')
                ->join('Descriptor','Descriptor_Persona.idDescriptor','=','Descriptor.id')
                ->select('Persona.id')
                ->where('Persona_Organizacion.idOrganizacion',$idOrganizacion)
                ->where('Descriptor.id',$idDescriptor)
                ->distinct()
                ->lists('Persona.id');

            $result = Organizacion::find($idOrganizacion)->Persona()->whereIn('Persona.id',$personas)->get();
            return response()->json(['Persona'=>$result]);


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
        }catch(NotFoundException $e)
        {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }

    /**
     * @param $idOrganizacion
     * @param $idDescriptor
     * @return \Illuminate\Http\JsonResponse
     */

    public function getPersonasDescriptor($idDescriptor)
    {
        try{
            $user = AuthenticateController::checkUser('Supervisor');
            $personas = DB::table('Persona')
                ->join('Descriptor_Persona','Persona.id','=','Descriptor_Persona.idPersona')
                ->join('Descriptor','Descriptor_Persona.idDescriptor','=','Descriptor.id')
                ->select('Persona.*')
                ->where('Descriptor.id',$idDescriptor)
                ->distinct()
                ->get();
            return response()->json(['Persona'=>$personas]);


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
        }catch(NotFoundException $e)
        {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }


    /**
     * @param $idOrganizacion
     * @return \Illuminate\Http\JsonResponse
     * @throws Exceptions\JWTException
     * @throws Exceptions\TokenExpiredException
     * @throws Exceptions\TokenInvalidException
     */

    public function getOrganizacionPersonas($idOrganizacion){
        try{
            $user = AuthenticateController::checkUser('Supervisor');
            $organizacion = Organizacion::find($idOrganizacion);
            $organizacion->load('Persona');
            return response()->json(['Persona'=>$organizacion->Persona]);
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
        }catch(NotFoundException $e)
        {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }


    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEjecucion($type,$id)
    {
        try{

            $user = AuthenticateController::checkUser('Supervisor');
            $proyecto = Proyecto::find($id);
            if($proyecto==null)
                throw new NotFoundException;
            switch($type)
            {
                case 'Ejecucion':
                    $proyecto->load('Ejecucion');
                    if ($proyecto->Ejecucion != null) {
                        return response()->json($proyecto->Ejecucion);
                    }
                    break;
                case 'Impacto':
                    $proyecto->load('Impacto');
                    if ($proyecto->Impacto != null) {
                        return response()->json($proyecto->Impacto);
                    }
                    break;
                case 'TransferenciaTecnologica':
                    $proyecto->load('TransferenciaTecnologica');
                    if ($proyecto->TransferenciaTecnologica != null) {
                        return response()->json(['TransferenciaTecnologica'=>$proyecto->TransferenciaTecnologica]);
                    }
                    break;
                case 'ModeloNegocio':
                    $proyecto->load('ModeloNegocio');
                    if ($proyecto->ModeloNegocio != null) {
                        return response()->json($proyecto->ModeloNegocio);
                    }
                    break;
            }
            return response()->json(['message' => $type.'_not_found'], 404);


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
        }catch(NotFoundException $e)
        {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }

    /**
     * @param $idProyecto
     * @return \Illuminate\Http\JsonResponse
     */

    public function showFileRoutes($type,$idProyecto){
        try{
            $user = AuthenticateController::checkUser('Supervisor');
            $user->load('Persona');
            $proyecto = Proyecto::find($idProyecto);
            if($proyecto==null)
                throw new NotFoundException;
            $results = DB::table('TipoArchivo')
                ->join('Archivos','Archivos.idTipoArchivo','=','TipoArchivo.id')
                ->where('Proyecto.id',$idProyecto)
                ->select('Archivos.Ruta as Ruta','TipoArchivo.Titulo as Tipo');
            switch($type)
            {
                case 'Ejecucion':
                    $results
                        ->join('Ejecucion','Archivos.idEjecucion','=','Ejecucion.id')
                        ->join('Proyecto','Ejecucion.idProyecto','=','Proyecto.id');
                    break;
                case 'Impacto':
                    $results
                        ->join('ImpactoYComercializacion','Archivos.idImpacto','=','ImpactoYComercializacion.id')
                        ->join('Proyecto','ImpactoYComercializacion.idProyecto','=','Proyecto.id');
                    break;
                case 'TransferenciaTecnologica':

                    $results
                        ->join('TransferenciaTecnologica','Archivos.idTransferenciaTecnologica','=','TransferenciaTecnologica.id')
                        ->join('Proyecto','TransferenciaTecnologica.idProyecto','=','Proyecto.id');
                    break;
                case 'ModeloNegocio':
                    $results
                        ->join('ModeloNegocio','Archivos.idModeloNegocio','=','ModeloNegocio.id')
                        ->join('Proyecto','ModeloNegocio.idProyecto','=','Proyecto.id');
                    break;
            }
            $results = $results->get();

            if($results!=null)
                return response()->json(['Archivos'=>$results]);
            return response()->json(['message'=>'archivos_not_found'],404);


        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }catch(NotFoundException $e)
        {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
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
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */

    public function showResults($type,$id)
    {
        try
        {

            $user = AuthenticateController::checkUser('Supervisor');
            $user->load('Persona');
            $proyecto = Proyecto::find($id);
            $results = DB::table('ProyectoResultado')
                ->join('ProyectoTRL', 'ProyectoResultado.idProyectoTRL', '=', 'ProyectoTRL.id')
                ->select('ProyectoResultado.*')
                ->where('ProyectoTRL.idProyecto',$id);

            if ($type != null)
            {
                if($type!='Todos')
                {
                    $results->where('ProyectoResultado.Tipo', $type);
                    $results = $results->get();
                    if($type=='Patente')
                    {
                       foreach($results as $result)
                        {
                            $result->PaisesProteccion = json_decode($result->PaisesProteccion);
                        }
                    }

                }
                else
                {
                    $results->where('ProyectoResultado.Tipo','!=','Patente');
                    $results = $results->get();
                }

            }
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
     *
     */
    public function proyectosByTRL($idTRL)
    {
        try{
            $user = AuthenticateController::checkUser('Supervisor');

            $ids= DB::table('ProyectoTRL')
                ->select(DB::raw('MAX(ProyectoTRL.idTRL) as Max,ProyectoTRL.idProyecto'))
                ->groupBy('ProyectoTRL.idProyecto')
                ->having('Max','=',$idTRL)
                ->get();
            foreach($ids as $idProyecto)
            {
                $proyectos[] = Proyecto::find($idProyecto->idProyecto);
            }
            return response()->json(['Proyecto'=>$proyectos]);

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
        }catch(NotFoundException $e)
        {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }





    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function countByTRL()
    {
        try{
            $user = AuthenticateController::checkUser('Supervisor');

            $totalTRLRegisters = TRL::count();
            $trls =TRL::lists('Nivel')->toArray();
            $countArray = array_fill(0,$totalTRLRegisters,0);
            $maximum = DB::table('ProyectoTRL')
                ->select(DB::raw('MAX(ProyectoTRL.idTRL) as Max'))
                ->groupBy('ProyectoTRL.idProyecto')
                ->get();

            foreach($maximum as $counter)
            {
                $countArray[$counter->Max-1] = $countArray[$counter->Max-1]+1;
            }

            $results['Labels']=$trls;
            $results['Data']=$countArray;
            array_walk($results['Labels'],function(&$item){
                $item = 'TRL '.$item;
            });

            return response()->json($results);

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
        }catch(NotFoundException $e)
        {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }

    /**
     * @param $idTipoDescriptor
     * @return \Illuminate\Http\JsonResponse
     */
    public function CountOrganizacionByTipoDescriptor($idTipoDescriptor)
    {
        try{
            $user = AuthenticateController::checkUser('Supervisor');
            $formattedResults = [];
            $results = DB::table('Organizacion')
                ->join('Descriptor_Organizacion','Organizacion.id','=','Descriptor_Organizacion.idOrganizacion')
                ->join('Descriptor','Descriptor.id','=','Descriptor_Organizacion.idDescriptor')
                ->join('TipoDescriptor','TipoDescriptor.id','=','Descriptor.idTipoDescriptor')
                ->where('TipoDescriptor.id',$idTipoDescriptor)
                ->groupBy('Descriptor.id')
                ->select(DB::raw('count(Descriptor.id) as Data, Descriptor.Titulo as Labels'))
                ->get();
            if($results !=null)
            {
                foreach($results as $result)
                {
                    $formattedResults['Data'][]=$result->Data;
                    $formattedResults['Labels'][]=$result->Labels;
                }
            }

            return response()->json($formattedResults);


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
        }catch(NotFoundException $e)
        {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function organizacionesByDescriptor($id)
    {

        try{
            $user = AuthenticateController::checkUser('Supervisor');
            $results = DB::table('Organizacion')
                ->join('Descriptor_Organizacion','Organizacion.id','=','Descriptor_Organizacion.idOrganizacion')
                ->where('Descriptor_Organizacion.idDescriptor',$id)
                ->select('Organizacion.*')
                ->get();

            return response()->json(['Organizacion'=>$results]);


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
        }catch(NotFoundException $e)
        {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }


    /**
     * @param $idModalidad
     * @return \Illuminate\Http\JsonResponse
     */


    public function openConvocatoriaModalidad($idModalidad)
    {

        try{
            AuthenticateController::checkUser('Supervisor');
            $results = DB::table('Convocatoria_Modalidad')
                ->join('Convocatoria','Convocatoria.id','=','Convocatoria_Modalidad.idConvocatoria')
                ->join('Modalidad','Modalidad.id','=','Convocatoria_Modalidad.idModalidad')
                ->where('Modalidad.id',$idModalidad)
                ->whereDate('Convocatoria.FechaTermino','>=', date('Y-m-d'))
                ->select('Convocatoria.*')
                ->get();
            return response()->json(['Convocatoria'=>$results]);
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
        }catch(NotFoundException $e)
        {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }

    /**
     * @param $type
     * @param $id
     * @param null $status
     * @return \Illuminate\Http\JsonResponse
     */


    public function countProyectosByConvocatoriaModalidad($type,$id,$status=null)
    {
        try{
            AuthenticateController::checkUser('Supervisor');
            $selectString = null;
            $formattedResults = [];
            $results = DB::table('Convocatoria_Modalidad')
                ->join('Convocatoria','Convocatoria.id','=','Convocatoria_Modalidad.idConvocatoria')
                ->join('Modalidad','Modalidad.id','=','Convocatoria_Modalidad.idModalidad')
                ->join('RegistroProyecto','RegistroProyecto.idConvocatoriaModalidad','=','Convocatoria_Modalidad.id')
                ->groupBy('Convocatoria.id');

            switch($type)
            {
                case 'Modalidad':
                    $results->where('Modalidad.id',$id);
                    $selectString = 'Convocatoria.Nombre as Labels';
                    break;
                case 'ProgramaFondeo':
                    $results->join('ProgramaFondeo','Modalidad.idProgramaFondeo','=','ProgramaFondeo.id')
                        ->where('ProgramaFondeo.id',$id);
                    $selectString = 'Modalidad.Nombre as Labels';
                    break;

            }
            $results->select(DB::raw('count(RegistroProyecto.id) as Data'),$selectString);


            if($status!=null && $status!='Todos')
            {
                $results->where('RegistroProyecto.Validado',$status);
            }
            $results = $results->get();
            foreach($results as $result)
            {
                $formattedResults['Data'][]=$result->Data;
                $formattedResults['Labels'][]=$result->Labels;
            }


            return response()->json($formattedResults);
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
        }catch(NotFoundException $e)
        {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }

    /**
     * @param $type
     * @param $id
     * @param string $how
     * @param null $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function countFundsByConvocatoriaModalidad($type,$id,$how='Apoyado',$status=null)
    {
        try{
            $selectString = '';
            $user = AuthenticateController::checkUser('Supervisor');
            $formattedResults = [];
            $results = DB::table('Convocatoria_Modalidad')
                ->join('Convocatoria','Convocatoria.id','=','Convocatoria_Modalidad.idConvocatoria')
                ->join('Modalidad','Modalidad.id','=','Convocatoria_Modalidad.idModalidad')
                ->join('RegistroProyecto','RegistroProyecto.idConvocatoriaModalidad','=','Convocatoria_Modalidad.id')
                ->groupBy('Labels');

            switch($type)
            {
                case 'Modalidad':
                    $results->where('Modalidad.id',$id);
                    $selectString = 'Convocatoria.Nombre as Labels';
                    break;
                case 'ProgramaFondeo':
                    $results->join('ProgramaFondeo','Modalidad.idProgramaFondeo','=','ProgramaFondeo.id')
                        ->where('ProgramaFondeo.id',$id);
                    $selectString = 'Modalidad.Nombre as Labels';
                    break;
            }

            if($how=='Apoyado')
            {
                $results->select(DB::raw('SUM(RegistroProyecto.MontoApoyado) as Data'),$selectString);
            }
            else{
                $results->select(DB::raw('SUM(RegistroProyecto.MontoSolicitado) as Data'),$selectString);
            }

            if($status!=null && $status!='Todos')
            {
                $results->where('RegistroProyecto.Validado',$status);
            }
            $results = $results->get();
            foreach($results as $result)
            {
                $formattedResults['Data'][]=$result->Data;
                $formattedResults['Labels'][]=$result->Labels;
            }


            return response()->json($formattedResults);
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
        }catch(NotFoundException $e)
        {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }

    /**
     * @param string $how
     * @param null $status
     * @return \Illuminate\Http\JsonResponse
     */

    public function countFundsAllProgramas($how='Apoyado',$status=null)
    {
        try{
            $user = AuthenticateController::checkUser('Supervisor');
            $formattedResults = [];
            $results = DB::table('Convocatoria_Modalidad')
                ->join('Convocatoria','Convocatoria.id','=','Convocatoria_Modalidad.idConvocatoria')
                ->join('Modalidad','Modalidad.id','=','Convocatoria_Modalidad.idModalidad')
                ->join('RegistroProyecto','RegistroProyecto.idConvocatoriaModalidad','=','Convocatoria_Modalidad.id')
                ->join('ProgramaFondeo','Modalidad.idProgramaFondeo','=','ProgramaFondeo.id')
                ->groupBy('Labels');


            if($how=='Apoyado')
            {
                $results->select(DB::raw('SUM(RegistroProyecto.MontoApoyado) as Data, ProgramaFondeo.Titulo as Labels'));
            }
            else{
                $results->select(DB::raw('SUM(RegistroProyecto.MontoSolicitado) as Data, ProgramaFondeo.Titulo as Labels'));
            }

            if($status!=null && $status!='Todos')
            {
                $results->where('RegistroProyecto.Validado',$status);
            }
            $results = $results->get();
            foreach($results as $result)
            {
                $formattedResults['Data'][]=$result->Data;
                $formattedResults['Labels'][]=$result->Labels;
            }


            return response()->json($formattedResults);
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
        }catch(NotFoundException $e)
        {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }


    /**
     * @param $type
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function countProjectRegisterStatus($type,$id=null){
        try{
            AuthenticateController::checkUser('Supervisor');
            $result ['Labels'] = ['Aceptado','Rechazado','Pendiente','Culminado'];
            $result ['Data'] = [];
            $query = DB::table('RegistroProyecto');

            if($type!='All' && $id == null)
                return response()->json(['message'=>'id_needed_for_query'],500);

            switch($type)
            {
                case 'ProgramaFondeo':
                    $query
                        ->join('Convocatoria_Modalidad','Convocatoria_Modalidad.id','=','RegistroProyecto.idConvocatoriaModalidad')
                        ->join('Modalidad','Modalidad.id','=','Convocatoria_Modalidad.idModalidad')
                        ->join('ProgramaFondeo','ProgramaFondeo.id','=','Modalidad.idProgramaFondeo')
                        ->where('ProgramaFondeo.id',$id);
                    break;
                case 'Modalidad':
                    $query
                        ->join('Convocatoria_Modalidad','Convocatoria_Modalidad.id','=','RegistroProyecto.idConvocatoriaModalidad')
                        ->where('Convocatoria_Modalidad.idModalidad',$id);
                    break;
                case 'Convocatoria':
                    $query
                        ->join('Convocatoria_Modalidad','Convocatoria_Modalidad.id','=','RegistroProyecto.idConvocatoriaModalidad')
                        ->where('Convocatoria_Modalidad.idConvocatoria',$id);
                    break;
            }

            foreach($result['Labels'] as $condition)
            {
                $queryResult = clone $query;
                $result['Data'][]= $queryResult->where('Validado',$condition)->count();
            }




            return response()->json($result);


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
        }catch(NotFoundException $e)
        {
            return response()->json(['proyecto_not_found'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }

}
