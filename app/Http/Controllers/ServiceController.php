<?php

namespace App\Http\Controllers;

use App\Service;
use App\User;
use App\Employee;
use App\ServiceDetail;
use App\Http\Resources\Formation as FormationR;
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

  public function getServicesOfManager() {
    $manager = Auth::user();

    if($manager->user_type_id == 3):
      $myServices = ServiceDetail::
        select(DB::raw('DISTINCT(service_details.service_id,
        services.name,
        services.color'))
      ->join('services','services.id','=','service_details.service_id')
      ->where('service_details.manager_id',$manager->id)
      ->paginate(20);
      
      foreach($myServices as $key=>$myService):
        $employee = ServiceDetail::where('manager_id', Auth::user()->id)
        ->select('employees.id', 'employees.timeoff_granted', 'employees.timeoff_in_progress', 'employees.taken_timeoff', 'employees.total_timeoff','employees.user_id')
        ->join('users','users.id','employees.user_id')
        ->join('employees','employees.id','service_details.employee_id')
        ->get();

        $myServices[$key]['employees'] = $employee;
      endforeach;
      return Response::json($myServices);
    else:
        return Response::json(["Erreur : "=>"Vous n'avez pas les droits"]);
    endif;
  }
}
