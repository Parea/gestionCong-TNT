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
    
    Route::post('register', 'UserController@register');

    Route::put('user/update', 'UserController@update');
    /**
     * Créer un nouveau étudiant
     */
    Route::post('createEmployee', 'EmployeeController@createEmployee');

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
    Route::put('user/update', 'UserController@update');
     
    /**
      * Créer un nouveau employé
      */
    Route::post('createEmployee', 'EmployeeController@createEmployee');
     /*
     * Affiliation d'un employé à un service
     */
    Route::post('fillemployee', 'UserController@fillEmployee');

    
    
    //***************Routes concernant le controlleur Employee****************************//
    //************************************************************************************//

    /*
      * Routes pour les utilisateurs connecté en tant que employee
      */
    Route::get('EmployeesAllServices', 'EmployeeController@getAllServices');
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

    //======================= Récupération les infos des congées de l'employé connecter => service + validation
    Route::get('mytimeoff', 'EmployeeController@getTimeoffAuthUser');
    //=======================Récupération de toutes les employées de chaque service
    Route::get('employeesbyservice/{ServiceId}', 'EmployeeController@getEmployeesByServiceId');
    //=======================Récupération de toutes les infos de chaque employées par service
    Route::get('employeeTimeoff/{userId}/byservice/{ServiceId}', 'EmployeeController@getEmployeeTimeoffsByService');
    //=======================Récupération du responsable du service 
    Route::get('managerbyservice/{ServiceId}','EmployeeController@getManagerByService');
    //=======================Récupération de toutes les agents du responsable par service
    Route::get('agentsbymanager/{serviceId}','EmployeeController@getAgentsByManager');

    
    //***************Routes concernant le controlleur Services****************************//
    //************************************************************************************//

    //=======================Récupération de toutes les Services
    Route::get('servicedetails', 'ServiceDetailController@all');
    
    Route::get('myServices/{serviceID}', 'ServiceController@getEmployeeServicesOfManager');

    Route::get('allServiceDirectorAndAdmin','ServiceController@getAllServicesForDirectorAndAdmin');

    Route::get('managerByService/{serviceId}','ServiceController@getManagerByServiceId');

    Route::get('agentsByService/{serviceId}','ServiceController@getAgentsByServiceId');

    Route::get('employeeTakenTimeoffByService/{serviceId}','ServiceController@getEmployeeTakenTimeoffByServiceId');

    
    
    Route::get('validationTimeoffByEmployee','ValidationTimeoffController@validationByEmployee');

    Route::put('validation/updateManagerValidationTimeoff','ValidationTimeoffController@updateManagerValidationTimeoff');
  });
