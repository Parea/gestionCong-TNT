<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Service;
use App\User;
use App\ValidationTimeoff;

use App\Http\Resources\Employee as EmployeeR;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;


class EmployeeController extends Controller
{
    //Affiche tous les employées
    public function all(){
        $authUserTypeId = Auth::user()->user_type_id;
        $authUserId = Auth::user()->id;

        if($authUserTypeId == 1):
            $Employees = Employee::select('employees.id as id','services.id as service_id','services.name as service_name','users.id as user_id','users.firstname as user_name')
            ->leftjoin('services','services.id','=','employees.service_id')
            ->leftjoin('users','users.id','=','employees.user_id')
            ->paginate(25);
            return Response::json($Employees);
        else:
            return Response::json(['error'=>'acces non autoriser']);
        endif;
    }

     //Affiche les employées par ID
    public function show($EmployeeId){
        $Employee = Employee::select('id', 'service_id','user_id')
            ->where('id', $EmployeeId)
            ->get()->first();
        return Response::json($Employee);
    }

    public function destroy($EmployeeId)
    {
        $employee = Employee::findOrFail($EmployeeId);
        if($employee->delete()):
            return new EmployeeR($employee);
        endif;
    }

    public function store(Request $request)
    {
        $authUserId = Auth::user()->id;

        //si la méthode est un put, on effectue la modification
        if($request->isMethod('put')):
          $employee =
            Employee::where([
              [ 'id', '=', $request->employee_id],
            ])->get()->first();

          if(!empty($employee)):
            $employee->id = $request->input('employee_id');
            $employee->user_id = $request->input('user_id');
            $employee->service_id = $request->input('service_id');

            // dd($employee);

            if($employee->save()):
              return new EmployeeR($employee);
            endif;
        else:
            return Response::json(["Erreur : "=>"Vous n'avez pas les droits"]);
        endif;

        //fin de la modification, ici on crée un nouveau commentaire
        else:
          $input = $request->all();
          $employee = Employee::create($input);

          return new EmployeeR($employee);
        endif;
    }

    public function getTimeoffAuthUser()
    {
        $serviceData = User::getMyCurrentService();

        $timeoffData = Employee::getEmployeesByServiceId($serviceData->service_id);

        $employeDatas = [];
        $i = -1;

        foreach ($timeoffData as $key=>$timeoff):
                $i++;
                // $employeDatas[$i]['service_id'] = $timeoff['service_id'];
                $employeDatas[$i]['timeoff_granted'] = $timeoff['timeoff_granted'];
                $employeDatas[$i]['timeoff_in_progress'] = $timeoff['timeoff_in_progress'];
                $employeDatas[$i]['taken_timeoff'] = $timeoff['taken_timeoff'];
                $employeDatas[$i]['taken_timeoff'] = $timeoff['taken_timeoff'];
                $employeDatas[$i]['total_timeoff'] = $timeoff['total_timeoff'];

            $validation = ValidationTimeoff::where([
                ['validation_timeoffs.employee_id', $serviceData->employee_id]
            ])->first();

            $employeDatas[$i]['timeoffs'][] = [
                'validations'=> ($validation)?[
                    'validation_id' => $validation->id,
                    'form_timeoff_id' => $validation->form_timeoff_id,
                    'validation_accept' => $validation->validate,
                    'manager_id' => $validation->manager_id,
                    'manager_validation_date' => $validation->manager_validation_date,
                ]:
                [
                    'validation_id' => null,
                    'form_timeoff_id' => null,
                    'validation_accept' => null,
                    'manager_id' => null,
                    'manager_validation_date' => null,
                ]
            ];
            
            if($validation):
                if($validation->employee_validation) $employeDatas[$i]['validate_timeoff']++;
                if($validation->employee_validation) $employeDatas[$i]['taken_timeoff']++;
            endif;
        endforeach;

        return response::json($employeDatas);

    }

    public function getEmployeeTimeoffsByService($userId, $serviceId)
    {
        $userAuthorized = [1, 2, 3];
        if(in_array(Auth::user()->user_type_id, $userAuthorized)):
            $serviceData = User::getMyCurrentService();
            $service = Service::find($serviceId);
            $user = User::find($userId);
            $employeeData = Employee::find($userId);
            $employee = Employee::where([['user_id',$userId],['service_id',$serviceId],['active',1]])->first();
            $timeoffData = Employee::getEmployeesByServiceIdAndManagerId($serviceId);
            
            $employeeDatas = [];
            $Data = [];
            $i = -1;
            
            $employeeDatas['employee'] = [
                'user_id'=> $user->id,
                'firstname'=> $user->firstname,
                'lastname'=> $user->lastname,
                'avatar'=> $user->avatar,
                'service' => $service->name,
                'timeoff_granted' => $employeeData->timeoff_granted,
                'timeoff_in_progress' => $employeeData->timeoff_in_progress,
                'taken_timeoff' => $employeeData->taken_timeoff,
                'total_timeoff' => $employeeData->total_timeoff
            ];

            // foreach ($timeoffData as $key=>$timeoff):
            //     $i++;
            //     $Data[$i]['timeoff_granted'] = $timeoff['timeoff_granted'];
            //     $Data[$i]['timeoff_in_progress'] = $timeoff['timeoff_in_progress'];
            //     $Data[$i]['taken_timeoff'] = $timeoff['taken_timeoff'];
            //     $Data[$i]['taken_timeoff'] = $timeoff['taken_timeoff'];
            //     $Data[$i]['total_timeoff'] = $timeoff['total_timeoff'];


            //     $validation = ValidationTimeoff::where([
            //         ['validation_timeoffs.employee_id', $serviceData->employee_id]
            //     ])->first();

            //     $Data[$i]['timeoffs'][] = [
            //         'validations'=> ($validation)?[
            //             'validation_id' => $validation->id,
            //             'form_timeoff_id' => $validation->form_timeoff_id,
            //             'validation_accept' => $validation->validate,
            //             'manager_id' => $validation->manager_id,
            //             'manager_validation_date' => $validation->manager_validation_date,
            //         ]:
            //         [
            //             'validation_id' => null,
            //             'form_timeoff_id' => null,
            //             'validation_accept' => null,
            //             'manager_id' => null,
            //             'manager_validation_date' => null,
            //         ]
            //     ];
                
            //     if($validation):
            //         if($validation->employee_validation) $employeDatas[$i]['validate_timeoff']++;
            //         if($validation->employee_validation) $employeDatas[$i]['taken_timeoff']++;
            //     endif;
            // endforeach;
                return response::json($employeeDatas);
            else:
                return Response::json(["Erreur : "=>"Vous n'avez pas les droits"]);
            endif;

    }

    public function getEmployeesByServiceId($serviceId)
    {
        // $employeeData = Employee::find($userId);
        $serviceData = Employee::select(
            'employees.user_id as employee_id',
            'employees.service_id as service_id',
            'services.name as service_name',
            'users.id as user_id',
            'users.lastname as lastname',
            'users.firstname as firstname'
        )
        ->join('users', 'users.id', '=', 'employees.user_id')
        ->join('services', 'services.id', '=', 'employees.service_id')
        ->where([
            ['employees.active', '=', '1'],
            ['services.id', '=', $serviceId],
        ])->get()->toArray();

        $employeDatas = [];
        $moduleId = 0;
        $i = -1;

        foreach($serviceData as $key=>$service):
            //dd($skill);
            $i++;
            $employeDatas[$i]['lastname'] = $service['lastname'];
        endforeach;
        return Response::json($serviceData);
    }

    public function getAllServices() {
        $userAuthorized = [1, 2, 3];
        if(in_array(Auth::user()->user_type_id, $userAuthorized)):
            $serviceData = [];
            $timeoffId = 0;
            $i = -1;

            $serviceData = Employee::select('employees.id as employee_id', 'users.lastname as Lastname', 'users.firstname as Firstname','services.id as service_id', 'services.name as service_name','services.color as service_color')
            ->join('services','services.id','employees.service_id')
            ->join('users','users.id','employees.user_id')
            ->where('employees.active', 1)
            ->orderBy('employees.id','desc')
            ->get()->toArray();

            return Response::json($serviceData);
        endif;
    }
}
