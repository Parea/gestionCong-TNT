<?php

namespace App\Http\Controllers;

use App\Employee;
use App\User;
use App\FormTimeoff;
use  App\Http\Resources\FormTimeoff as FormTimeoffR;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
class FormTimeoffController extends Controller {

    public function all(){

        $authUserTypeId = Auth::user()->user_type_id;
        $authUserId = Auth::user()->id;

        if($authUserTypeId == 1):
            $formTimeoff = FormTimeoff::select('form_timeoffs.id as form_id',
            'form_timeoffs.employee_id','users.firstname',
            'form_timeoffs.motif','form_timeoffs.other_motif',
            'form_timeoffs.start_timeoff','form_timeoffs.end_timeoff',
            'form_timeoffs.numbers_days_taken', 'form_timeoffs.manager_id',
            'form_timeoffs.validate','form_timeoffs.validation_date')
            ->join('employees','employees.id','form_timeoffs.employee_id')
            ->join('users','users.id','employees.user_id')
            ->paginate(25);

            return Response::json($formTimeoff);
        else:    
            return Response::json(["Erreur: "=>"Vous n'avez pas les droits"]);
        endif;
    }
    
    public function show($formTimeoffId){
        $formTimeoff = FormTimeoff::select('form_timeoffs.id as form_id',
        'form_timeoffs.employee_id','users.firstname',
        'form_timeoffs.motif','form_timeoffs.other_motif',
        'form_timeoffs.start_timeoff','form_timeoffs.end_timeoff',
        'form_timeoffs.numbers_days_taken', 'form_timeoffs.manager_id',
        'form_timeoffs.validate','form_timeoffs.validation_date')
        ->join('employees','employees.id','form_timeoffs.employee_id')
        ->join('users','users.id','employees.user_id')
        ->where('form_timeoffs.id',$formTimeoffId)
        ->get()->first();

        return Response::json($formTimeoff);
    }

    public function destroy($formTimeoffId){
        $formTimeoff = FormTimeoff::findOrFail($formTimeoffId);
        if($formTimeoff->delete()):
            return new FormTimeoffR($formTimeoff);
        endif;
    }

    public function getTotalValidation() {
		$validation = FormTimeoff::count('id');
		return Response::json("Il y a $validation demande de congées en tout");
	} 

	public function validationByEmployee() {
			
		$user = Auth::user();
		if($user->user_type_id == 1):
				$employees = Employee::select('employees.id as employee_id',
				'users.lastname as Nom',
				'users.firstname as Prenom')
				->join('users','users.id','employees.user_id')
				->get();

				foreach($employees as $key=>$employee):
						$employeeValidationTimeoff = FormTimeoff::select('form_timeoffs.id')
						->join('employees','employees.id','form_timeoffs.employee_id')
						->where('employees.id',$employee->employee_id)
						->where('validate',1)
						->get();

						$employeeValidateTimeoffByManager = FormTimeoff::select('form_timeoffs.id',
						'form_timeoffs.validation_date as date_validate',
						'users.firstname as manager_name')
						->join('employees', 'employees.id', 'form_timeoffs.employee_id')
						->join('users','users.id','form_timeoffs.manager_id')
						->where('employees.id',$employee->employee_id)
						->where('validate',1)
						->get();

						$employeeNotValidateTimeoffByManager = FormTimeoff::select('form_timeoffs.id',
						'form_timeoffs.validation_date as date_validate',
						'users.firstname as manager_name')
						->join('employees', 'employees.id', 'form_timeoffs.employee_id')
						->join('users','users.id','form_timeoffs.manager_id')
						->where('employees.id',$employee->employee_id)
						->where('validate',0)
						->get();
				
						$employees[$key]->validate_by_manager = $employeeValidateTimeoffByManager;
						$employees[$key]->not_validate_by_manager = $employeeNotValidateTimeoffByManager;
						$employees[$key]->totalValidate = $employeeValidationTimeoff->count();
						$employees[$key]->totalNotValidate = $employeeNotValidateTimeoffByManager->count();
				endforeach;

				return Response::json($employees);
		else:
				return Response::json("Vous n'avez pas les droits");
		endif;
	}

	public function store(Request $request)
	{
		$userAuthId = Auth::user()->id;
	}

	public function managerValidation()
	{
		$userAuthorized = [2, 3];
		if(in_array(Auth::user()->user_type_id, $userAuthorized)):
			$authUserEmployeeId = 6;
			$decrementValue = 15;
			DB::table('employees')->where('id', $authUserEmployeeId)->decrement('timeoff_granted', $decrementValue);
			DB::table('employees')->where('id', $authUserEmployeeId)->update(['taken_timeoff' => $decrementValue]);
			return response::json('Succès');
		else:
			return Response::json("Vous n'avez pas les droits");
		endif;

		// $authUserEmployeeId = 6;
		// $decrementValue = 15;
		// DB::table('employees')->where('id', $authUserEmployeeId)->decrement('timeoff_granted', $decrementValue);
		// DB::table('employees')->where('id', $authUserEmployeeId)->update(['taken_timeoff' => $decrementValue]);
		// return response::json('Succès');
	}

	public function updateManagerValidationTimeoff(Request $request){
		$userAuthorized = [2, 3];
		if(in_array(Auth::user()->user_type_id, $userAuthorized)):
			$input = $request->all();
			$validations = FormTimeoff::find($input['form_timeoff_id']);
			$validations->manager_id = $input['manager_id'];
			$validations->validate = $input['validate'];
			$validations->validation_date = date('Y-m-d H:m:s');
			$validations->save();


			return Response::json('succès');
		else:
			return Response::json("Vous n'avez pas les droits");
		endif;
	}
}
