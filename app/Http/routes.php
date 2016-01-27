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
    /*Persona*/
    Route::get('Persona','PersonaController@show');
    Route::post('Persona','PersonaController@store');
    Route::delete('Persona','PersonaController@destroy');
    Route::put('Persona','PersonaController@update');
        /*Persona_Descriptor*/
        Route::post('Persona/Descriptor','PersonaController@addDescriptor');
        Route::get('Persona/Descriptor','PersonaController@showAllDescriptor');
        Route::delete('Persona/Descriptor/{id}','PersonaController@detachDescriptor');

    /*Validacion de persona*/
    Route::get('Supervisor/Persona','PersonaController@showNotValidated');
    Route::post('Supervisor/Persona','PersonaController@validatePerson');


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
    Route::get('Organizacion/{id}','OrganizacionController@show');
    Route::put('Organizacion/{id}','OrganizacionController@update');
    Route::delete('Organizacion/{id}','OrganizacionController@destroy');

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
        Route::get('Proyecto/{type}/{id}/{idOrganizacion?}','ProyectoController@showOneProject')
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
        Route::get('Impacto/Archivos/{idProyecto}','ImpactoController@showFileRoutes');


    /*Metodos para registrar el TRL de un proyecto */
        Route::post('Proyecto/TRL','ProyectoController@addTRL');
        Route::post('Proyecto/TRL/Delete','ProyectoController@deleteTRLs');
        Route::get('Proyecto/TRL/{id}','ProyectoController@viewTRL');

        /*Métodos para registrar la ejecución de un proyecto*/
        Route::post('Ejecucion/{whoIs?}/{idOrganizacion?}','EjecucionController@store')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
        Route::post('Ejecucion/Update/{whoIs?}/{idOrganizacion?}','EjecucionController@update')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
        Route::get('Ejecucion/{idProyecto}/{whoIs?}/{idOrganizacion?}','EjecucionController@show')
            ->where(['idProyecto'=>'[0-9]+','whoIs'=>'(Persona|Organizacion)','idOrganizacion'=>'[0-9]+']);
        Route::get('Ejecucion/Archivos/{idProyecto}','EjecucionController@showFileRoutes');

        /*Métodos para registrar el Modelo de Negocio de un proyecto*/
        Route::post('ModeloNegocio/{whoIs?}/{idOrganizacion?}','ModeloNegocioController@store')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
        Route::post('ModeloNegocio/Update/{whoIs?}/{idOrganizacion?}','ModeloNegocioController@update')
            ->where(['whoIs'=>'Organizacion','idOrganizacion'=>'[0-9]+']);
        Route::get('ModeloNegocio/{idProyecto}/{whoIs?}/{idOrganizacion?}','ModeloNegocioController@show')
            ->where(['idProyecto'=>'[0-9]+','whoIs'=>'(Persona|Organizacion)','idOrganizacion'=>'[0-9]+']);
        Route::get('ModeloNegocio/Archivos/{idProyecto}','ModeloNegocioController@showFileRoutes');

    /*Métodos para registrar el Modelo de Negocio de un proyecto*/
        Route::post('TransferenciaTecnologica','TransferenciaTecnologicaController@store');
        Route::get('TransferenciaTecnologica/Archivos/{id}','TransferenciaTecnologicaController@showFileRoutes');
        Route::get('TransferenciaTecnologica/{id}','TransferenciaTecnologicaController@show');
        Route::delete('TransferenciaTecnologica/{id}','TransferenciaTecnologicaController@destroy');
        Route::post('TransferenciaTecnologica/Update','TransferenciaTecnologicaController@update');
        Route::get('Proyecto/TransferenciaTecnologica/{idProyecto}','TransferenciaTecnologicaController@showAll');

        /*Métodos para registrar etapas en un proyecto*/
        Route::get('Proyecto/EtapaProyecto/{id}','ProyectoController@showEtapas');
        Route::post('EtapaProyecto','EtapaProyectoController@store');

        /*Métodos para registrar resultados de un proyecto*/
        Route::post('Proyecto/Resultados','ProyectoController@addResult');
        Route::put('Proyecto/Resultados','ProyectoController@editResult');
        Route::get('Proyecto/Resultados/{id}/{type?}','ProyectoController@showResults')
            ->where(['id'=>'[0-9]+','type'=>'(Patente|Producto|Servicio|Proceso|Todos)']);



    /*Programa de Fondeo*/
    Route::get('ProgramaFondeo','ProgramaFondeoController@showAll');
    Route::get('ProgramaFondeo/{id}','ProgramaFondeoController@show');
    Route::post('ProgramaFondeo','ProgramaFondeoController@store');
    Route::put('ProgramaFondeo/{id}','ProgramaFondeoController@update');
    Route::delete('ProgramaFondeo/{id}','ProgramaFondeoController@destroy');
        /*Convocatorias del Programa de Fondeo */
        Route::get('ProgramaFondeo/Convocatoria/{id}','ProgramaFondeoController@showConvocatorias');

    /*Convocatorias*/
    Route::post('Convocatoria','ConvocatoriaController@store');
    Route::put('Convocatoria/{id}','ConvocatoriaController@update');
    Route::delete('Convocatoria/{id}','ConvocatoriaController@destroy');
        /*Modalidades Asociadas a la Convocatoria*/
        Route::get('Convocatoria/Modalidad/{id}','ConvocatoriaController@showModalidades');
    /*Modalidades*/
    Route::post('Modalidad','ModalidadController@store');
    Route::delete('Modalidad/{id}','ModalidadController@destroy');
    Route::put('Modalidad/{id}','ModalidadController@update');

    /*TRL*/
    Route::get('TRL','TRLController@show');




});