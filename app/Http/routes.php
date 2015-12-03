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

Route::group(['prefix' => 'api'], function()
{
    /*
     *
     */
    Route::post('Authenticate', 'AuthenticateController@authenticate');
    Route::post('Register', 'AuthenticateController@register');
    /*Persona*/
    Route::get('Persona','PersonaController@show');
    Route::post('Persona','PersonaController@store');
    Route::delete('Persona','PersonaController@destroy');
    Route::put('Persona','PersonaController@update');
    /*Proyecto*/
    Route::get('Proyecto/Persona','ProyectoController@showProjects');
    Route::put('Proyecto/Persona/{id}','ProyectoController@editProject');
    Route::post('Proyecto/Persona/Inscribir','ProyectoController@addCollaborator');
    Route::post('Proyecto/Persona/Eliminar','ProyectoController@removeCollaborator');


    Route::post('Proyecto/Persona','ProyectoController@storeByPerson');
    Route::delete('Proyecto/Persona/{id}','ProyectoController@removeProject');

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


});