<?php

namespace App\Http\Controllers;

use App\ServiceDetail;
use App\User;
use App\Service;
use App\Employee;
use App\Http\Resources\ServiceDetail as ServiceDetailR;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;


class ServiceDetailController extends Controller {
    public function all() {
        if(Auth::user()->user_type_id == 1):
            $servicedetails = ServiceDetail::select('service_details.service_id as service_id',
            'services.name as service_name',
            'service_details.id as service_details_id',
            'employees.id as employee_id','users.firstname as name_manager')
            ->join('employees','employees.id','service_details.employee_id')
            ->join('users','users.id','service_details.manager_id')
            ->join('services','services.id','service_details.service_id')
            ->paginate(25);

            return Response::json($servicedetails);
        else:
            return Response::json(["Erreur: " => "Vous n'avez pas les droits"]);
        endif;
    }

    public function show($serviceDetailId) {
        if(Auth::user()->user_type_id == 1):
            $serviceDetailId = ServiceDetail::select('service_details.id as service_detail_id',
            'services.name as service_name','services.color as service_color')
            ->join('services','services.id','service_details.service_id')
            ->get();

            return Response::json($serviceDetailId);
        else:
            return Response::json(["Erreur"=>"Vous n'avez pas les droits"]);
        endif;
    }

    public function destroy($id) {
        if(Auth::user()->user_type_id == 1):
            $servicedetail = ServiceDetail::findOrFail($id);
            $servicedetail->delete();
            return Response::json($servicedetail);
        else:
            return Response::json(["Erreur : "=>"Vous n'avez pas les droits"]);
        endif;
    }

    public function store(Request $request) {
        $authUserId = Auth::user()->id;

        //si la méthode est un put, on effectue la modification
        if($request->isMethod('put')):
          $servicedetail =
          ServiceDetail::where([
              [ 'id', '=', $request->service_detail_id],
            ])->get()->first();

          if(!empty($servicedetail)):
            $servicedetail->id = $request->input('service_detail_id');
            $servicedetail->service_id = $request->input('service_id');
            $servicedetail->employee_id = $request->input('employee_id');
            $servicedetail->manager_id = $request->input('manager_id');

            // dd($formation);

            if($servicedetail->save()):
              return new ServiceDetailR($servicedetail);
            endif;

          else:
            return Response::json(['Erreur '=>'Vous ne pouvez pas modifier']);
          endif;

        //fin de la modification, ici on crée un nouveau commentaire
        else:
          $input = $request->all();
          $servicedetail = ServiceDetail::create($input);
          return new ServiceDetailR($servicedetail);
        endif;
    }

    // public function getRemainingTimeoffByAuthUser() {
        
    //     $authUserId = Auth::user()->id;

    //     $remainingTimeoffByAuthUser = Employee::select('employees.user_id as user_id',
    //     'employees.service_id as service_id','services.name as service_name','services.color as service_color')
    //     ->join('service_details','service_details.service_id','employees.service_id')
    //     ->join('services','services.id','employees.service_id')
    //     ->where('employees.user_id', $authUserId)
    //     ->paginate(25);

    //     return Response::json($remainingTimeoffByAuthUser);
    // }

    
}

