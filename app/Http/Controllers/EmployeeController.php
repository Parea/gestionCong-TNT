<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Service;
use App\User;
use App\ValidationTimeoff;
use App\FormTimeoff;

use App\Http\Resources\Employee as EmployeeR;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Carbon;

class EmployeeController extends Controller
{
    //Affiche tous les employées
    public function all(){
        $authUserTypeId = Auth::user()->user_type_id;
        $authUserId = Auth::user()->id;

        if($authUserTypeId == 1):
            $Employees = Employee::select('employees.id as id',
            'services.id as service_id',
            'services.name as Nom_service',
            'users.id as user_id','users.firstname as Prenom')
            ->leftjoin('services','services.id','employees.service_id')
            ->leftjoin('users','users.id','employees.user_id')
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
        $validation = FormTimeoff::where([['form_timeoffs.employee_id', $serviceData->employee_id],['form_timeoffs.validate', 1]])->get();
        $notValidation = FormTimeoff::where([['form_timeoffs.employee_id', $serviceData->employee_id],['form_timeoffs.validate', 0]])->get();        

        foreach ($timeoffData as $key=>$timeoff):

            $employeDatas['lastname'] = $serviceData['Nom'];
            $employeDatas['firstname'] = $serviceData['Prenom'];
            $employeDatas['serviceName'] = $serviceData['service_name'];
            $employeDatas['timeoffgranted'] = $timeoff['timeoff_granted'];
            $employeDatas['timeoffprogress'] = $timeoff['timeoff_in_progress'];
            $employeDatas['timeofftaken'] = $timeoff['taken_timeoff'];
            $employeDatas['totaltimeoff'] = $timeoff['total_timeoff'];
            $employeDatas['totaltimeoffvalidated'] =  $validation->count();
            $employeDatas['timeoffvalidated'] = $validation;
            $employeDatas['totaltimeoffnotvalidated'] =  $notValidation->count();
            $employeDatas['timeoffnotvalidated'] = $notValidation;
            
        endforeach;

        return response::json($employeDatas);

    }

    public function getEmployeeTimeoffs() {

        $employeeTimeoff = FormTimeoff::select('form_timeoffs.id as form_id', 'users.firstname', 'services.name', 'services.color','form_timeoffs.motif as motif',
        'form_timeoffs.start_timeoff as start', 'form_timeoffs.end_timeoff as end','form_timeoffs.numbers_days_taken as daysTaken',
        'form_timeoffs.manager_id as managerId', 'form_timeoffs.validate as validate',
        'form_timeoffs.validation_date as dateValidate')
        ->join('services','services.id','form_timeoffs.service_id')
        ->join('employees','employees.id','form_timeoffs.employee_id')
        ->join('users', 'users.id', 'form_timeoffs.employee_id')
        ->where([['form_timeoffs.validate', 1]])
        ->get();

        return response::json($employeeTimeoff);
    }

    public function getEmployeesByServiceId($serviceId){
        
        $userAuthorized = [1, 2, 3];
        if(in_array(Auth::user()->user_type_id, $userAuthorized)):
            $serviceData = Employee::select(
                'users.id as user_id',
                'users.lastname as Nom',
                'users.firstname as Prenom',
                'employees.user_id as employee_id',
                'employees.service_id as service_id',
                'services.name as Nom_service')
            ->join('users', 'users.id', 'employees.user_id')
            ->join('services', 'services.id', 'employees.service_id')
            ->where([
                ['employees.active', 1],
                ['services.id', '=', $serviceId],
            ])->get()
            ->toArray();
            return Response::json($serviceData);
        else:
            return response::json(["Erreur"=>"Vous n'avez pas les droits"]);
        endif;
    }

    public function getAllServices() {
        $userAuthorized = [1, 2];
        if(in_array(Auth::user()->user_type_id, $userAuthorized)):
            $serviceData = [];

            $serviceData = Employee::select('employees.id as employee_id',
            'users.lastname as Nom',
            'users.firstname as Prenom',
            'services.id as service_id',
            'services.name as Nom_service',
            'employees.timeoff_granted as congées obtenu',
            'employees.taken_timeoff as congées pris',
            'employees.total_timeoff as congées restant',
            'services.color as service_color')
            ->join('services','services.id','employees.service_id')
            ->join('users','users.id','employees.user_id')
            ->where('employees.active', 1)
            ->orderBy('employees.id','desc')
            ->get()
            ->toArray();

            return Response::json($serviceData);
        else:
            return response::json(["Erreur"=>"Vous n'avez pas les droits"]);
        endif;
    }
    //Récupère le responsable de service
    public function getManagerByService($serviceId) {
        $userAuthorized = [1, 2];
        if(in_array(Auth::user()->user_type_id, $userAuthorized)):
            $serviceData = [];

            $serviceData = Employee::select('employees.id as employee_id',
            'users.lastname as Nom',
            'users.firstname as Prenom',
            'services.id as service_id',
            'services.name as Nom_service')
            ->join('services','services.id','employees.service_id')
            ->join('users','users.id','employees.user_id')
            ->where([['users.user_type_id','=',3],['employees.service_id','=',$serviceId]])
            ->orderBy('employees.id','desc')
            ->get()
            ->toArray();

            return Response::json($serviceData);
        else:
            return response::json(["Erreur"=>"Vous n'avez pas les droits"]);
        endif;
    }

    //Récupère les agents de chaque responsable de service
    public function getAgentsByManager($serviceId) {
        $userAuthorized = [2, 3];
        if(in_array(Auth::user()->user_type_id, $userAuthorized)):
            $serviceData = [];

            $serviceData = Employee::select('employees.id as employee_id',
            'users.lastname as Nom',
            'users.firstname as Prenom',
            'services.id as service_id',
            'services.name as Nom_service',
            'employees.timeoff_granted as congées obtenu',
            'employees.taken_timeoff as congées pris',
            'employees.total_timeoff as congées restant')
            ->join('services','services.id','employees.service_id')
            ->join('users','users.id','employees.user_id')
            ->where([['users.user_type_id',4],['employees.service_id',$serviceId]])
            ->orderBy('employees.id','desc')
            ->get()
            ->toArray();

            return Response::json($serviceData);
        else:
            return response::json(["Erreur"=>"Vous n'avez pas les droits"]);
        endif;
    }

    public function createEmployee(Request $request) {
        if(Auth::user()->user_type_id == 1):
            $validator = Validator::make($request->all(), [
                'lastname' => 'required',
                'firstname' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:6',
                'c_password' => 'required|same:password',
                'avatar' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['Erreur'=>$validator->errors()], 401);
            }

            if($request->hasfile('avatar')):
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = substr( md5( 1 . '-' . time() ), 0, 15).'.'.$extension;
            $file->move('uploads/images/', $filename);
            endif;

            $input = $request->all();
            $input['avatar'] = $filename;
            $input['user_type_id'] = 3;
            $input['password'] = bcrypt($input['password']);

            $user = User::create($input);

            $employeeData = [
                'user_id' => $user->id,
                'service_id' => $input['service_id'],
                'active' => 1,
            ];

            $employeeCreate = Employee::create($employeeData);


            $employee = Employee::select('*')->where('user_id', $user->id)->where('active', 1)->get()->first();
            // dd($employee);

            $success['token'] =  $user->createToken('Laravel')->accessToken;
            $success['id'] =  $user->id;
            $success['lastname'] =  $user->lastname;
            $success['firstname'] =  $user->firstname;
            $success['email'] =  $user->email;
            // $success['gender'] =  $user->gender;
            $success['user_type_id'] =  $user->user_type_id;
            $success['employee_id'] =  $employee['user_id'];
            // dd($success);
            return Response::json($success);
        else:
            return response::json(["Erreur"=>"Vous n'avez pas les droits"]);
        endif;
    }

    public function addTimeoffByMonth(){
        $dateS = Carbon::now()->startOfMonth(); 
        if($user->user_type_id == 2):
            $input = $request->all();
            $values=array(
                'student_validation' => $input['student_validation'],
                'student_validation_date' => date('Y-m-d H:m:s'),

            );
            $progression = DB::table('progressions')->select('progressions.student_validation', 'progressions.student_validation_date','progressions.updated_at as progressions_updated_at')
                ->join('skills', 'skills.id','progressions.skill_id')
                ->join('formation_details', 'formation_details.module_id', 'skills.module_id')
                ->where('formation_details.module_id', $input['module_id'])
                ->where('progressions.student_id', $input['student_id'])
                ->update($values);

            return Response::json('succès');
        else:
            return Response::json("Vous n'avez pas les droits");
        endif;
    }
//////////////////////////////////////////////////////Partie pour affichage HTML/////////////////////////////////////////////////////////////////////////////////////////

    public function HTMLall(){
        $userTypeId = Auth::user()->user_type_id;
        $users = Auth::user();
        $employees = Employee::all();

        $employeeTab = [];

        if($userTypeId === 4):
            foreach($employees as $employee):
                $employeeTab[] = [
                    'employees' => $employee->user_id,
                    'users' => User::find($employee->user_id)
                ];
            endforeach;

            //dd($employees);
            return view('agents.agents', ['employees' => $employees, 'users' => $users]);
        else:
            return("Vous n'avez pas accés a ces informations car vous êtes pas admin");
        endif;        
    }
}
