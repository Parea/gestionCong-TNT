<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/**
 * Connexion d'un utilisateur
 * Enregistrement d'un nouvel utilisateur
 */
 Route::post('login', 'UserController@login');
 
 /**
  * Liste de tout les utilisateurs présent dans l'api
  */
 Route::get('users', 'UserController@all');

 Route::group(['middleware' => 'auth:api'], function(){
    
    /*
     * Enregistrement d'un utilisateur
     */
    
    Route::post('register', 'UserController@register');
    
    /**
     * Déconnexion d'un utilisateur
     */
    Route::get('logout','UserController@logout');

     /*
     * Les trois routes qui suivents permettent de récupérer les différents type d'utilisateur présent dans l'api
     */
     Route::get('users/admin','UserController@listUsersAdmin');
     Route::get('users/employee','UserController@listUsersEmployee');
     Route::get('users/manager','UserController@listUsersManager');
 
     /*
     * Affiliation d'un employé à un service
     */
    Route::post('fillemployee', 'UserController@fillEmployee');

    //***************Routes concernant le controlleur Employee****************************//
    //************************************************************************************//

    //=======================Récupération de tous les employées
    Route::get('employees','EmployeeController@all');
    //=======================Récupération d'un employé par sont ID
    Route::get('employee/{employeeID}','EmployeeController@show');
    //=======================Modification d'un employé
    Route::post('employee/create','EmployeeController@store');
    //=======================Suppréssion d'un employé
    Route::delete('employee/{employeeID}','EmployeeController@destroy');
    //=======================Modification d'un employé
    Route::put('employee','EmployeeController@store');

    //======================= Récupération des inServices de l'employé connecter => service + validation
    Route::get('getServices', 'EmployeeController@getTimeoffAuthUser');
    Route::get('getFormationForAdmin/{ServiceId}', 'EmployeeController@getTimeoffByService');

    Route::get('getEmployeesOfService/{ServiceId}', 'EmployeeController@getEmployeesByServiceId');

    Route::get('getEmployeeDatas/{userId}/ofService/{ServiceId}', 'EmployeeController@getEmployeeTimeoffsByService');
});
