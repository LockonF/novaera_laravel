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



    /*Proyecto*/

        /*Metodos para cuando personas inscriben un proyecto */
        Route::get('Proyecto/Persona','ProyectoController@showProjects');
        Route::get('Proyecto/Persona/{id}','ProyectoController@showOneProject');
        Route::post('Proyecto/Persona','ProyectoController@storeByPerson');
        Route::put('Proyecto/Persona/{id}','ProyectoController@editProject');
        Route::delete('Proyecto/Persona/{id}','ProyectoController@removeProject');
        /*Metodos para personas colaboradoras del proyecto*/
        Route::post('Proyecto/Persona/Inscribir','ProyectoController@addCollaborator');
        Route::post('Proyecto/Persona/Eliminar','ProyectoController@removeCollaborator');

        /*Metodos para inscribir proyecto a modalidad*/
        Route::post('Proyecto/Modalidad','ProyectoController@addToModalidad');
        Route::post('Proyecto/Modalidad/Delete','ProyectoController@removeFromModalidad');
        Route::get('Proyecto/Modalidad/{id}','ProyectoController@showModalidades');

        /*Metodos para registrar el impacto de un proyecto*/
        Route::post('Impacto','ImpactoController@store');
        Route::post('Impacto/Update','ImpactoController@update');
        Route::get('Impacto/{idProyecto}','ImpactoController@show');
        Route::get('Impacto/Archivos/{idProyecto}','ImpactoController@showFileRoutes');

        /*Metodos para registrar el TRL de un proyecto */
        Route::post('Proyecto/TRL','ProyectoController@addTRL');
        Route::post('Proyecto/TRL/Delete','ProyectoController@deleteTRLs');
        Route::get('Proyecto/TRL/{id}','ProyectoController@viewTRL');

        /*Métodos para registrar la ejecución de un proyecto*/
        Route::post('Ejecucion','EjecucionController@store');
        Route::post('Ejecucion/Update','EjecucionController@update');
        Route::get('Ejecucion/{idProyecto}','EjecucionController@show');
        Route::get('Ejecucion/Archivos/{idProyecto}','EjecucionController@showFileRoutes');

        /*Métodos para registrar el Modelo de Negocio de un proyecto*/
        Route::post('ModeloNegocio','ModeloNegocioController@store');
        Route::post('ModeloNegocio/Update','ModeloNegocioController@update');
        Route::get('ModeloNegocio/{idProyecto}','ModeloNegocioController@show');
        Route::get('ModeloNegocio/Archivos/{idProyecto}','ModeloNegocioController@showFileRoutes');


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