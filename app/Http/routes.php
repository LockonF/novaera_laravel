<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/




Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'cors','prefix' => 'api'], function()
{


    /*Usuario*/
        Route::post('Authenticate', 'AuthenticateController@authenticate');
        Route::post('Register', 'AuthenticateController@register');
        Route::get('User', 'AuthenticateController@getUser');

    /*Refresh Token*/
        Route::get('RefreshToken','AuthenticateController@refreshToken');

    /*Persona*/
        Route::get('Persona/Lookup/{name}','PersonaController@lookup');


        Route::get('Persona/{who?}','PersonaController@show')
            ->where(['who'=>'Current']);
        Route::post('Persona','PersonaController@store');
        Route::delete('Persona','PersonaController@destroy');
        Route::put('Persona','PersonaController@update');

    /*Persona_Descriptor*/
        Route::post('Persona/Descriptor','PersonaController@addDescriptor');
        Route::get('Persona/Descriptor/{id?}','PersonaController@showAllDescriptor')
            ->where(['id'=>'[0-9]+']);
        Route::delete('Persona/Descriptor/{id}/{idPersona?}','PersonaController@detachDescriptor')
            ->where(['idPersona'=>'[0-9]+']);
        Route::put('Persona/Descriptor/{id}','PersonaController@updateDescriptor');

    /*Validacion de persona y Organización*/
        Route::get('Supervisor/Persona','PersonaController@showNotValidated');
        Route::post('Supervisor/Organizacion','OrganizacionController@valiateOrganizaciones');


    /*Persona para supervisor*/

        Route::post('Supervisor/Persona','PersonaController@validatePerson');
        Route::get('Supervisor/Persona/ByDescriptor/{idDescriptor}','SupervisorController@getPersonasDescriptor')
            ->where(['idDescriptor'=>'[0-9]+']);
        Route::get('Supervisor/Persona/TipoDescriptor/Count/{idTipoDescriptor}','SupervisorController@countPersonasByTipoDescriptor')
            ->where(['idTipoDescriptor'=>'[0-9]+']);
        Route::get('Supervisor/Persona/ByOrganizacion/Count','SupervisorController@countByOrganizations');

    /*Proyectos para supervisor*/
        Route::get('Supervisor/Proyectos/Descriptor/{idDescriptor}','SupervisorController@proyectosByDescriptor');
        Route::get('Supervisor/Proyectos/TRL/List/{idTRL}','SupervisorController@proyectosByTRL')->where(['idTRL'=>'[0-9]+']);
        Route::get('Supervisor/Proyectos/TRL/Count','SupervisorController@countByTRL');
        Route::get('Supervisor/Proyectos/TipoDescriptor/Count/{idTipoDescriptor}','SupervisorController@countProyectosByTipoDescriptor')
            ->where(['idDescriptor'=>'[0-9]+']);

        Route::get('Supervisor/{type}/{id}','SupervisorController@getEjecucion')
            ->where(['type'=>'(Ejecucion|Impacto|TransferenciaTecnologica|ModeloNegocio)','id'=>'[0-9]+']);
        Route::get('Supervisor/{type}/Archivos/{id}','SupervisorController@showFileRoutes')
            ->where(['type'=>'(Ejecucion|Impacto|TransferenciaTecnologica|ModeloNegocio)','id'=>'[0-9]+']);
        Route::get('Supervisor/Resultados/{type}/{id}','SupervisorController@showResults')
            ->where(['type'=>'(Patente|Producto|Servicio|Proceso|Todos)','id'=>'[0-9]+']);
        Route::get('Supervisor/RegistroProyecto','RegistroProyectoController@showRegistroProyectoAdmin');
        Route::get('Supervisor/RegistroProyecto/Convocatoria/{id}','RegistroProyectoController@showByConvocatoria')->where(['id'=>'[0-9]+']);

    /*Organización para supervisor*/

        Route::get('Supervisor/Organizacion','OrganizacionController@getNotValidatedOrganizaciones');
        Route::get('Supervisor/Organizacion/ByDescriptor/{id}','SupervisorController@organizacionesByDescriptor')->where(['id'=>'[0-9]+']);
        Route::put('Supervisor/Organizacion/{id}','OrganizacionController@validateDocuments')->where(['id'=>'[0-9]+']);
        Route::get('Supervisor/Organizacion/TipoDescriptor/Count/{idTipoDescriptor}','SupervisorController@CountOrganizacionByTipoDescriptor')
            ->where(['idTipoDescriptor'=>'[0-9]+']);
        Route::get('Supervisor/Organizacion/Documentos','OrganizacionController@getNotValidatedDocumentsOrganizaciones');
        Route::get('Supervisor/Organizacion/Persona/{idOrganizacion}','SupervisorController@getOrganizacionPersonas')
            ->where(['idOrganizacion'=>'[0-9]+']);
        Route::get('Supervisor/Organizacion/{idOrganizacion}/Persona/TipoDescriptor/Count/{idDescriptor}','SupervisorController@countPersonasOrgByTipoDescriptor')
            ->where(['idOrganizacion'=>'[0-9]+','idDescriptor'=>'[0-9]+']);
        Route::get('Supervisor/Organizacion/{idOrganizacion}/Persona/Descriptor/{idDescriptor}','SupervisorController@getOrganizacionPersonasDescriptor')
            ->where(['idOrganizacion'=>'[0-9]+','idDescriptor'=>'[0-9]+']);

    /*Programas de Fondeo para Supervisor*/

        Route::get('Supervisor/Modalidad/Convocatoria/{idModalidad}','SupervisorController@openConvocatoriaModalidad')->where(['idModalidad'=>'[0-9]+']);

        Route::get('Supervisor/{type}/{id}/Proyectos/{status?}','SupervisorController@countProyectosByConvocatoriaModalidad')
            ->where(['type'=>'(ProgramaFondeo|Modalidad)','id'=>'[0-9]+','status'=>'(Aceptado|Rechazado|Pendiente|Culminado|Todos)']);

        Route::get('Supervisor/{type}/{id}/Montos/{how}/{status?}','SupervisorController@countFundsByConvocatoriaModalidad')
            ->where(['type'=>'(ProgramaFondeo|Modalidad)','id'=>'[0-9]+','how'=>'(Apoyado|Solicitado)','status'=>'(Aceptado|Rechazado|Pendiente|Culminado|Todos)']);

        Route::get('Supervisor/ProgramaFondeo/All/Montos/{how}/{status?}','SupervisorController@countFundsAllProgramas')
            ->where(['how'=>'(Apoyado|Solicitado)','status'=>'(Aceptado|Rechazado|Pendiente|Culminado|Todos)']);

        Route::get('Supervisor/{type}/Registros/Count/{id?}','SupervisorController@countProjectRegisterStatus')
            ->where(['how'=>'(ProgramaFondeo|Modalidad|Convocatoria|All)','id'=>'[0-9]+']);



    /*Tipo Descriptor*/

    Route::get('TipoDescriptor/Clasificacion/{classification}','TipoDescriptorController@showByClassification');
    Route::get('TipoDescriptor/Descriptor/{id}','TipoDescriptorController@showAssociatedDescriptor')->where(['id'=>'[0-9]+']);
    Route::get('TipoDescriptor','TipoDescriptorController@showAll');
    Route::get('TipoDescriptor/{id}','TipoDescriptorController@show')->where(['id'=>'[0-9]+']);
    Route::post('TipoDescriptor','TipoDescriptorController@store');
    Route::put('TipoDescriptor/{id}','TipoDescriptorController@update')->where(['id'=>'[0-9]+']);
    Route::delete('TipoDescriptor/{id}','TipoDescriptorController@destroy')->where(['id'=>'[0-9]+']);


    /*Descriptores*/
    Route::get('Descriptor','DescriptorController@showAll');
    Route::get('Descriptor/{id}','DescriptorController@show');
    Route::post('Descriptor','DescriptorController@store');
    Route::put('Descriptor/{id}','DescriptorController@update');
    Route::delete('Descriptor/{id}','DescriptorController@destroy');


    /*Organizacion*/
    Route::get('Organizacion','OrganizacionController@showAll');
    Route::post('Organizacion','OrganizacionController@store');
    Route::post('Organizacion/Upload','OrganizacionController@upload');
    Route::get('Organizacion/{id}','OrganizacionController@show')->where(['id'=>'[0-9]+']);
    Route::put('Organizacion/{id}','OrganizacionController@update')->where(['id'=>'[0-9]+']);
    Route::delete('Organizacion/{id}','OrganizacionController@destroy')->where(['id'=>'[0-9]+']);
    Route::post('Organizacion/Persona','OrganizacionController@addPersona');
    Route::get('Organizacion/Persona/{id}','OrganizacionController@showPersonasOrganizacion')->where(['id'=>'[0-9]+']);
    Route::get('Organizacion/{idOrganizacion}/Persona/Descriptor/{idDescriptor}','OrganizacionController@showPersonsByDescriptor')
        ->where(['idOrganizacion'=>'[0-9]+','idDescriptor'=>'[0-9]+']);
    Route::get('Organizacion/{idOrganizacion}/Persona/TipoDescriptor/Count/{idDescriptor}','OrganizacionController@countPersonsByTipoDescriptor')
        ->where(['idOrganizacion'=>'[0-9]+','idDescriptor'=>'[0-9]+']);
    Route::delete('Organizacion/Persona/{idOrganizacion}/{idPersona}','OrganizacionController@removePersonaOrganizacion')
        ->where(['idOrganizacion'=>'[0-9]+','idPersona'=>'[0-9]+']);
        /*Consultables por todos*/
        Route::get('Organizacion/General','OrganizacionController@showAllGeneral');
        Route::get('Organizacion/General/{id}','OrganizacionController@showOneGeneral')->where(['id'=>'[0-9]+']);
        /*Descriptor de Organizaciones*/
        Route::post('Organizacion/Descriptor','OrganizacionController@addDescriptor');
        Route::get('Organizacion/Descriptor/{id}','OrganizacionController@showAllDescriptor');
        Route::delete('Organizacion/Descriptor/{idPrograma}/{id}','OrganizacionController@detachDescriptor');
        Route::put('Organizacion/Descriptor/{id}','OrganizacionController@updateDescriptor');

    /*Contacto*/
    Route::post('Contacto','ContactoController@store');
    Route::get('Contacto','ContactoController@show');
    Route::put('Contacto','ContactoController@update');
    Route::delete('Contacto','ContactoController@destroy');

    /*Direccion*/
    Route::post('Direccion','DireccionController@store');
    Route::get('Direccion','DireccionController@show');
    Route::put('Direccion','DireccionController@update');


    /*Pais*/
    Route::get('Pais','PaisController@showAll');
    Route::get('Pais/EntidadFederativa/{id}','PaisController@showEntidades');

    /*EntidadFederativa*/
    Route::get('EntidadFederativa/Municipio/{id}','EntidadFederativaController@showMunicipios');

    /*Municipio*/
    Route::get('Municipio/Selected/{id}','MunicipioController@getSelectedInfo');



    /*TipoArchivo*/
    Route::get('TipoArchivo/{type?}','TipoArchivoController@showAll');
    /*Proyecto*/

        /*Metodos para cuando personas inscriben un proyecto */
        Route::get('Proyecto/{type}/{idOrganizacion?}','ProyectoController@showProjects')
            ->where(['type'=>'(Persona|Organizacion)','idOrganizacion'=>'[0-9]+']);
        Route::get('Proyecto/Date/{type?}/{idOrganizacion?}','ProyectoController@showByDate')
            ->where(['type'=>'(Persona|Organizacion)','idOrganizacion'=>'[0-9]+']);
        Route::get('Proyecto/One/{type}/{id}/{idOrganizacion?}','ProyectoController@showOneProject')
            ->where(['id'=>'[0-9]+','type'=>'(Persona|Organizacion)','idOrganizacion'=>'[0-9]+']);
        Route::post('Proyecto/{type}/{idOrganizacion?}','ProyectoController@storeByPerson')
            ->where(['type'=>'(Persona|Organizacion)','idOrganizacion'=>'[0-9]+']);
        Route::put('Proyecto/{type}/{id}/{idOrganizacion?}','ProyectoController@editProject')
            ->where(['id'=>'[0-9]+','type'=>'(Persona|Organizacion)','idOrganizacion'=>'[0-9]+']);
        Route::delete('Proyecto/{type}/{id}/{idOrganizacion?}','ProyectoController@removeProject')
            ->where(['id'=>'[0-9]+','type'=>'(Persona|Organizacion)','idOrganizacion'=>'[0-9]+']);
        /*Metodos para personas colaboradoras del proyecto*/
        Route::post('Proyecto/Persona/Inscribir','ProyectoController@addCollaborator');
        Route::post('Proyecto/Persona/Eliminar','ProyectoController@removeCollaborator');

        /*Metodos para inscribir proyecto a modalidad*/
        Route::post('Proyecto/Modalidad','ProyectoController@addToModalidad');
        Route::post('Proyecto/Modalidad/Delete','ProyectoController@removeFromModalidad');
        Route::get('Proyecto/Modalidad/{id}','ProyectoController@showModalidades');

        /*Metodos para registrar el impacto de un proyecto*/
        Route::post('Impacto/{whoIs?}/{idOrganizacion?}','ImpactoController@store')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
        Route::post('Impacto/Update/{whoIs?}/{idOrganizacion?}','ImpactoController@update')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
        Route::get('Impacto/{idProyecto}/{whoIs?}/{idOrganizacion?}','ImpactoController@show')
            ->where(['idProyecto'=>'[0-9]+','whoIs'=>'(Persona|Organizacion)','idOrganizacion'=>'[0-9]+']);
        Route::get('Impacto/Archivos/{idProyecto}/{whoIs?}/{idOrganizacion?}','ImpactoController@showFileRoutes')
            ->where(['idProyecto'=>'[0-9]+','whoIs'=>'(Persona|Organizacion)','idOrganizacion'=>'[0-9]+']);

        /* Métodos para ver proyecto de acuerdo al descriptor */
        Route::get('Proyecto/ByDescriptor/{id}/{whoIs?}/{idOrganizacion?}','ProyectoController@showByDescriptor')
            ->where(['id'=>'[0-9]+','whoIs'=>'(Persona|Organizacion)','idOrganizacion'=>'[0-9]+']);;
        Route::get('Proyecto/TipoDescriptor/Count/{idTipoDescriptor}/{whoIs?}/{idOrganizacion?}',
            'ProyectoController@countByTipoDescriptor')
            ->where(['id'=>'[0-9]+','whoIs'=>'(Persona|Organizacion)','idOrganizacion'=>'[0-9]+']);;


    /*Metodos para registrar el TRL de un proyecto */
        Route::get('Proyecto/TRL/Count/{whoIs?}/{idOrganizacion?}','ProyectoController@countByTRL');
        Route::post('Proyecto/TRL/{whoIs?}/{idOrganizacion?}','ProyectoController@addTRL')
            ->where(['whoIs'=>'(Organizacion)','idOrganizacion'=>'[0-9]+']);
        Route::post('Proyecto/TRL/Delete/{whoIs?}/{idOrganizacion?}','ProyectoController@deleteTRLs')
            ->where(['whoIs'=>'(Organizacion)','idOrganizacion'=>'[0-9]+']);
        Route::get('Proyecto/TRL/{id}/{whoIs?}/{idOrganizacion?}','ProyectoController@viewTRL')
            ->where(['id'=>'[0-9]+','whoIs'=>'(Organizacion)','idOrganizacion'=>'[0-9]+']);

        /*Métodos para registrar la ejecución de un proyecto*/
        Route::post('Ejecucion/{whoIs?}/{idOrganizacion?}','EjecucionController@store')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
        Route::post('Ejecucion/Update/{whoIs?}/{idOrganizacion?}','EjecucionController@update')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
        Route::get('Ejecucion/{idProyecto}/{whoIs?}/{idOrganizacion?}','EjecucionController@show')
            ->where(['idProyecto'=>'[0-9]+','whoIs'=>'(Persona|Organizacion)','idOrganizacion'=>'[0-9]+']);
        Route::get('Ejecucion/Archivos/{idProyecto}/{whoIs?}/{idOrganizacion?}','EjecucionController@showFileRoutes')
            ->where(['idProyecto'=>'[0-9]+','whoIs'=>'(Persona|Organizacion)','idOrganizacion'=>'[0-9]+']);;


    /*Métodos para registrar el Modelo de Negocio de un proyecto*/
        Route::post('ModeloNegocio/{whoIs?}/{idOrganizacion?}','ModeloNegocioController@store')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
        Route::post('ModeloNegocio/Update/{whoIs?}/{idOrganizacion?}','ModeloNegocioController@update')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
        Route::get('ModeloNegocio/{idProyecto}/{whoIs?}/{idOrganizacion?}','ModeloNegocioController@show')
            ->where(['idProyecto'=>'[0-9]+','whoIs'=>'(Persona|Organizacion)','idOrganizacion'=>'[0-9]+']);
        Route::get('ModeloNegocio/Archivos/{idProyecto}/{whoIs?}/{idOrganizacion?}','ModeloNegocioController@showFileRoutes')
            ->where(['idProyecto'=>'[0-9]+','whoIs'=>'(Persona|Organizacion)','idOrganizacion'=>'[0-9]+']);;


    /*Métodos para registrar el Modelo de Negocio de un proyecto*/


        Route::post('TransferenciaTecnologica/{whoIs?}/{idOrganizacion?}','TransferenciaTecnologicaController@store')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
        Route::get('TransferenciaTecnologica/Archivos/{id}/{whoIs}/{idOrganizacion?}','TransferenciaTecnologicaController@showFileRoutes')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
        Route::get('TransferenciaTecnologica/{id}','TransferenciaTecnologicaController@show');
        Route::delete('TransferenciaTecnologica/{id}/{whoIs?}/{idOrganizacion?}','TransferenciaTecnologicaController@destroy')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
        Route::post('TransferenciaTecnologica/Update/{whoIs?}/{idOrganizacion?}','TransferenciaTecnologicaController@update')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
        Route::get('Proyecto/TransferenciaTecnologica/{idProyecto}/{whoIs?}/{idOrganizacion?}','TransferenciaTecnologicaController@showAll')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);

        /*Métodos para registrar etapas en un proyecto*/
        Route::get('Proyecto/EtapaProyecto/{id}/{whoIs?}/{idOrganizacion?}','ProyectoController@showEtapas')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
            Route::post('Proyecto/EtapaProyecto/{whoIs?}/{idOrganizacion?}','EtapaProyectoController@store')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);

        /*Métodos para registrar resultados de un proyecto*/

        Route::get('Proyecto/Resultados/Count/{whoIs?}/{idOrganizacion?}','ProyectoResultadoController@countResultados')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);


        Route::post('Proyecto/Resultados/{whoIs?}/{idOrganizacion?}','ProyectoController@addResult')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
        Route::put('Proyecto/Resultados/{id}/{whoIs?}/{idOrganizacion?}','ProyectoController@editResult')
            ->where(['id'=>'[0-9]+','whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
        Route::get('Proyecto/Resultados/{id}/{type?}/{whoIs?}/{idOrganizacion?}','ProyectoController@showResults')
            ->where(['id'=>'[0-9]+','type'=>'(Patente|Producto|Servicio|Proceso|Todos)','whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
        Route::delete('Proyecto/Resultados/{id}/{whoIs?}/{idOrganizacion?}','ProyectoController@destroyResult')
            ->where(['id'=>'[0-9]+','whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
            /* Descriptor de Resultado */
        Route::get('Proyecto/Resultados/Descriptor/{id}/{whoIs?}/{idOrganizacion?}','ProyectoResultadoController@showDescriptor')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
        Route::post('Proyecto/Resultados/Descriptor/{whoIs?}/{idOrganizacion?}','ProyectoResultadoController@store')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
        Route::put('Proyecto/Resultados/Descriptor/{id}/{whoIs?}/{idOrganizacion?}','ProyectoResultadoController@update')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
        Route::delete('Proyecto/Resultados/Descriptor/{id}/{whoIs?}/{idOrganizacion?}','ProyectoResultadoController@destroy')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
    /*Descriptores de Proyecto*/
    Route::post('Proyecto/Descriptor/{whoIs?}/{idOrganizacion?}','ProyectoController@addDescriptor')
        ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
    Route::get('Proyecto/Descriptor/{id}/{whoIs?}/{idOrganizacion?}','ProyectoController@showAllDescriptor')
        ->where(['id'=>'[0-9]+','whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
    Route::delete('Proyecto/Descriptor/{idProyecto}/{id}/{whoIs?}/{idOrganizacion?}','ProyectoController@detachDescriptor')
        ->where(['id'=>'[0-9]+','whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
    Route::put('Proyecto/Descriptor/{id}/{whoIs?}/{idOrganizacion?}','ProyectoController@updateDescriptor')
        ->where(['id'=>'[0-9]+','whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);


    /*Programa de Fondeo*/
    Route::get('ProgramaFondeo','ProgramaFondeoController@showAll');
    Route::get('ProgramaFondeo/{id}','ProgramaFondeoController@show');
    Route::get('ProgramaFondeo/Convocatoria/{id}','ProgramaFondeoController@showConvocatoriasAsociadas');
    Route::post('ProgramaFondeo','ProgramaFondeoController@store');
    Route::post('ProgramaFondeo/Update/{id}','ProgramaFondeoController@update');
    Route::delete('ProgramaFondeo/{id}','ProgramaFondeoController@destroy');
        /*Convocatorias del Programa de Fondeo */
        Route::get('ProgramaFondeo/Modalidad/{id}','ProgramaFondeoController@showModalidades');
    /*Descriptores de Programa de Fondeo*/
    Route::post('ProgramaFondeo/Descriptor','ProgramaFondeoController@addDescriptor');
    Route::get('ProgramaFondeo/Descriptor/{id}','ProgramaFondeoController@showAllDescriptor');
    Route::delete('ProgramaFondeo/Descriptor/{idPrograma}/{id}','ProgramaFondeoController@detachDescriptor');
    Route::put('ProgramaFondeo/Descriptor/{id}','ProgramaFondeoController@updateDescriptor');

    /*Convocatorias*/
    Route::get('Convocatoria','ConvocatoriaController@showAll');
    Route::get('Convocatoria/{id}','ConvocatoriaController@showModalidades');
    Route::post('Convocatoria','ConvocatoriaController@store');
    Route::put('Convocatoria/{id}','ConvocatoriaController@update');
    Route::delete('Convocatoria/{id}','ConvocatoriaController@destroy');
        /*Modalidades Asociadas a la Convocatoria*/
        Route::post('Convocatoria/Modalidad','ConvocatoriaController@addToModalidad');
        Route::delete('Convocatoria/{idConvocatoria}/Modalidad/{idModalidad}','ConvocatoriaController@detachFromModaliad')
            ->where(['idConvocatoria'=>'[0-9]+','idModalidad'=>'[0-9]+']);
        Route::delete('Convocatoria/{idConvocatoria}/Modalidad','ConvocatoriaController@detachFromAll')
            ->where(['idConvocatoria'=>'[0-9]+']);
    /*Modalidades*/
    Route::get('Modalidad/Convocatoria/{id}','ModalidadController@showConvocatorias')
        ->where(['id'=>'[0-9]+']);
    Route::post('Modalidad','ModalidadController@store');
    Route::delete('Modalidad/{id}','ModalidadController@destroy');
    Route::put('Modalidad/{id}','ModalidadController@update');
    Route::get('Modalidad','ModalidadController@show');

    /*TRL*/
    Route::get('TRL','TRLController@show');

    /*Registro de Proyecto*/

    Route::post('RegistroProyecto/{whoIs?}/{idOrganizacion?}','RegistroProyectoController@store')
        ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
    Route::get('RegistroProyecto/{id}/{whoIs?}/{idOrganizacion?}','RegistroProyectoController@showAll')
        ->where(['id'=>'[0-9]+','whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
    Route::get('RegistroProyecto/{whoIs?}/{idOrganizacion?}','RegistroProyectoController@showRegistroProyecto')
        ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
    Route::put('RegistroProyecto/ValidateRequisitos/{id}','RegistroProyectoController@validateRequisitos');
    Route::put('RegistroProyecto/Validate/{id}','RegistroProyectoController@validateRegistroProyecto');
    Route::delete('RegistroProyecto/{id}/{whoIs?}/{idOrganizacion?}','RegistroProyectoController@delete')
        ->where(['id'=>'[0-9]+','whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);

    /* Parque Tecnologico*/
    Route::get('ParqueTecnologico','ParqueTecnologicoController@showAll');

});