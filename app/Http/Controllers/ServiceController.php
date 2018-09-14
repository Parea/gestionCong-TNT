<?php

namespace App\Http\Controllers;

use App\Service;
use App\User;
use App\Employee;
use App\ServiceDetail;
use App\ValidationTimeoff;
use App\Http\Resources\Service as ServiceR;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;             
use Carbon;      

class ServiceController extends Controller {
  public function all() {
    $services = Service::select('id', 'name', 'color')->paginate(10);
    return Response::json($services);
  }

  public function show($serviceId) {
    $service = Service::select('id', 'name', 'color')
    ->where('id', $serviceId)
    ->get()->first();
    return Response::json($serviceId);
  }


  public function store(Request $request) {
    if(Auth::user()->user_type_id == 1 ):
      if($request->isMethod('put')):
        $services = 
        Service::where([['id','=',$request->service_id]])->get()->first();
          
        if(!empty($service)):
          $service->id = $request->input('service_id');
          $service->name = $request->input('name');
          $service->logo = $request->input('color');

          if($service->save()):
            return new ServiceR($service);
          endif;
        endif;
      
      else:
        $input = $request->all();
        $service = Service::create($input);
        return new ServiceR($service);
      endif;
    else:
      return Response::json(["Erreur: " => "Vous n'avez pas les droits"]);
    endif;
  }


  public function getEmployeeServicesOfManager($serviceId) {
    
    $manager = Auth::user();

    if($manager->user_type_id == 3):
      $employeeDatas = [];

      $myServices = ServiceDetail::select(DB::raw('DISTINCT(service_details.service_id) as service_id,
      services.id as service_id,services.name as nom_service,services.color as couleur'))
      ->join('services','services.id','service_details.service_id')
      ->where('service_details.service_id',$serviceId)
      ->where('service_details.manager_id',$manager->id)
      ->paginate(20);

      
      foreach($myServices as $key=>$myService):

        $employeeDatas = Employee::select('employees.id as employee_id',
          'users.lastname as Nom',
          'users.firstname as Prenom',
          'services.id as service_id',
          'services.name as Nom_service',
          'employees.timeoff_granted as congées obtenu',
          'employees.taken_timeoff as congées pris',
          'employees.total_timeoff as congées restant')
          ->join('services','services.id','employees.service_id')
          ->join('users','users.id','employees.user_id')
          ->orderBy('employees.id','desc')
          ->where('employees.service_id',$serviceId)
          ->get();

        $myServices[$key]['total_employees'] = $employeeDatas->count();
        $myServices[$key]['all_employees'] = $employeeDatas;
      endforeach;
      return Response::json($myServices);
    else:
        return Response::json(["Erreur : "=>"Vous n'avez pas les droits"]);
    endif;
  }


  public function getAllServicesForDirectorAndAdmin(){
    $userAuthorized = [1, 2];
    $manager = Auth::user();
    $employeeData = [];

    if(in_array(Auth::user()->user_type_id, $userAuthorized)):
      $myServices = Service::all()->toArray();
      
      foreach($myServices as $key=> $myService):

        $managers = User::select('service_details.manager_id')
          ->join('service_details','service_details.manager_id','users.id')
          ->groupBy('service_details.manager_id')
          ->where('service_details.service_id',$myService['id'])
          ->get();

        // $employees = Employee::select('employees.id as employe_id',
        //   'employees.timeoff_granted as Congé_obtenu')
        //   ->join('users','users.id','employees.user_id')
        //   ->groupBy('employees.id')
        //   ->where('employees.service_id',$myService['id'])
        //   ->get();

        $employeeData = Employee::select('employees.id as employee_id',
          'users.lastname as Nom',
          'users.firstname as Prenom',
          // 'services.id as service_id',
          // 'services.name as Nom_service',
          'employees.timeoff_granted as congées obtenu',
          'employees.taken_timeoff as congées pris',
          'employees.total_timeoff as congées restant')
          ->join('services','services.id','employees.service_id')
          ->join('users','users.id','employees.user_id')
          ->orderBy('employees.id','desc')
          ->where('employees.service_id',$myService['id'])
          ->get();

        $myServices[$key]['total_responsable'] = $managers->count();
        $myServices[$key]['total_employee_by_service'] = $employeeData->count();
        $myServices[$key]['Emloyees'] = $employeeData;
      endforeach;

      return Response::json($myServices);
    else:
        return Response::json(["Erreur : "=>"Vous n'avez pas les droits"]);
    endif;
  }


  public function getManagerByServiceId($serviceId){
    $userAuthorized = [1, 2];
    if(in_array(Auth::user()->user_type_id, $userAuthorized)):
        $managerOfService = User::select('service_details.manager_id as id', 
        'users.lastname as lastname', 'users.firstname as firstname')
        ->join('service_details', 'service_details.manager_id', 'users.id')
        ->join('services','services.id','service_details.service_id')
        ->groupBy('service_details.manager_id')
        ->where('service_details.service_id', $serviceId)
        ->get();
        return Response::json($managerOfService);
    else:
        return Response::json(["Erreur : "=>"Vous n'avez pas les droits"]);
    endif;
  }


  public function getAgentsByServiceId($serviceId){
    $userAuthorized = [1, 2];
    if(in_array(Auth::user()->user_type_id, $userAuthorized)):

        $agents = User::select('users.id as user_id',
        'employees.id as employee_id', 'users.firstname', 'users.lastname')
        ->join('employees', 'employees.user_id','users.id')
        ->where('employees.service_id', $serviceId)
        ->get();

        foreach($agents as $key=>$agent):

            $timeoffs = ValidationTimeoff::select('validation_timeoffs.form_timeoff_id',
            'validation_timeoffs.manager_validation_date as date_validate',
            'users.firstname as manager_name')
            ->join('employees', 'employees.id', 'validation_timeoffs.employee_id')
            ->join('users','users.id','validation_timeoffs.manager_id')
            ->where('employees.service_id', $serviceId)
            ->where('employees.user_id', $agent->user_id)
            ->where('validation_timeoffs.validate', 1)
            ->get();

            $agents[$key]['total_timeoffs_validated'] = $timeoffs->count();
            $agents[$key]['timeoff_validated'] = $timeoffs;
            // $agents[$key]['timeoff_manager_validated'] = $timeoffValidatedManager->count();
        endforeach;
        return Response::json($agents);

    else:
        return Response::json(["Erreur : "=>"Vous n'avez pas les droits"]);
    endif;
  }

  public function getEmployeeTakenTimeoffByServiceId($serviceId){
    
    if(Auth::user()->user_type_id == 1):
        
      $timeoffs = User::select('users.id as user_id',
      'employees.id as employee_id', 
      'users.lastname as Nom','users.firstname as Prenom')
      ->join('employees', 'employees.user_id','users.id')
      ->where('employees.service_id', $serviceId)
      ->get();

      foreach($timeoffs as $key=>$timeoff):

        $timeoffValidated = ValidationTimeoff::select('validation_timeoffs.id',
        'validation_timeoffs.form_timeoff_id','validation_timeoffs.manager_id',
        'validation_timeoffs.validate','validation_timeoffs.manager_validation_date')
        ->join('employees','employees.id','validation_timeoffs.employee_id')
        ->join('users','users.id','validation_timeoffs.manager_id')
        ->where('employees.service_id',$serviceId)
        ->where('employees.user_id',$timeoff->user_id)
        ->where('validation_timeoffs.validate','=',1)
        ->get();

        $timeoffNotValidated = ValidationTimeoff::select('validation_timeoffs.id',
        'validation_timeoffs.form_timeoff_id','validation_timeoffs.manager_id',
        'validation_timeoffs.validate','validation_timeoffs.manager_validation_date')
        ->join('employees','employees.id','validation_timeoffs.employee_id')
        ->join('users','users.id','validation_timeoffs.manager_id')
        ->where('employees.service_id',$serviceId)
        ->where('employees.user_id',$timeoff->user_id)
        ->where('validation_timeoffs.validate','=', 0)
        ->get();


        $timeoffs[$key]['TotalDemandeCongésValider'] = $timeoffValidated->count();
        $timeoffs[$key]['DemandeCongésValider'] = $timeoffValidated;
        
        $timeoffs[$key]['TotalDemandeCongésAttente'] = $timeoffNotValidated->count();
        $timeoffs[$key]['DemandeCongésAttente'] = $timeoffNotValidated;
          
      endforeach;
      
      return Response::json($timeoffs);
    else:
      return Response::json(["Erreur : "=>"Vous n'avez pas les droits"]);
    endif;
  }
}
