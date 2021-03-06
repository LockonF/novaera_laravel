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

    /*Validacion de persona*/
    Route::get('Supervisor/Persona','PersonaController@showNotValidated');
    Route::get('Supervisor/RegistroProyecto','RegistroProyectoController@showRegistroProyectoAdmin');
    Route::post('Supervisor/Persona','PersonaController@validatePerson');
    Route::post('Supervisor/Organizacion','OrganizacionController@valiateOrganizaciones');


    /*Tipo Descriptor*/
    Route::get('TipoDescriptor','TipoDescriptorController@showAll');
    Route::get('TipoDescriptor/{id}','TipoDescriptorController@show');
    Route::post('TipoDescriptor','TipoDescriptorController@store');
    Route::put('TipoDescriptor/{id}','TipoDescriptorController@update');
    Route::delete('TipoDescriptor/{id}','TipoDescriptorController@destroy');


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
            ->where(['idProyecto'=>'[0-9]+','whoIs'=>'(Persona|Organizacion)','idOrganizacion'=>'[0-9]+']);;


    /*Metodos para registrar el TRL de un proyecto */
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
        Route::post('Proyecto/Resultados/{whoIs?}/{idOrganizacion?}','ProyectoController@addResult')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
        Route::put('Proyecto/Resultados','ProyectoController@editResult');
        Route::get('Proyecto/Resultados/{id}/{type?}/{whoIs?}/{idOrganizacion?}','ProyectoController@showResults')
            ->where(['id'=>'[0-9]+','type'=>'(Patente|Producto|Servicio|Proceso|Todos)','whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
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
    Route::post('Proyecto/Descriptor','ProyectoController@addDescriptor');
    Route::get('Proyecto/Descriptor/{id}','ProyectoController@showAllDescriptor');
    Route::delete('Proyecto/Descriptor/{idProyecto}/{id}','ProyectoController@detachDescriptor');
    Route::put('Proyecto/DescriptorU/{id}','ProyectoController@updateDescriptor');


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