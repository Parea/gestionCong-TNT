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
 
     /*
     * Affiliation d'un employé à un service
     */
    Route::post('fillemployee', 'UserController@fillEmployee');

     /*
      * Routes pour les utilisateurs connecté en tant que student
      */
     Route::get('getAllServices', 'EmployeeController@getAllServices');
 

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
    // Route::get('getServiceForAdmin/{ServiceId}', 'EmployeeController@getTimeoffByService');

    Route::get('getEmployeesOfService/{ServiceId}', 'EmployeeController@getEmployeesByServiceId');

    Route::get('getEmployeeDatas/{userId}/ofService/{ServiceId}', 'EmployeeController@getEmployeeTimeoffsByService');

    Route::get('getManagerByService/{ServiceId}','EmployeeController@getManagerByService');

    //=======================Récupération de toutes les FormationDetail
    Route::get('servicedetails', 'ServiceDetailController@all');
    //=======================Récupération d'une formationdetail par son id
    Route::get('formationdetail/{formationdetailsId}', 'FormationDetailController@show');
    //=======================Création d'une formationdetail
    Route::post('formationdetail/create', 'FormationDetailController@store');
    //=======================Supression d'une formationdetail
    Route::delete('formationdetail/{fformationdetailsId}', 'FormationDetailController@destroy');
    //=======================Modification d'une formationdetail
    Route::put('formationdetail', 'FormationDetailController@store');
    //============Récupérer les modules de l'utilisateur connecte==============================
    Route::get('modulesByStudent', 'FormationDetailController@getModulesByAuthUser');
    //============Récupérer les skills de l'utilisateur connecté===============================
    Route::get('skillsByStudent', 'FormationDetailController@getSkillsByAuthUser');
    //============Récupérer les formations d'un formateur ======================================
    Route::get('manager/myServices', 'ServiceController@getServicesOfManager');
});
