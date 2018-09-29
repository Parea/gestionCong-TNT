<?php

namespace App\Http\Controllers;

use App\ValidationTimeoff;
use App\Http\Resources\ValidationTimeoff as ValidationR;
use App\Employee;
use App\User;
use App\FormTimeoff;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ValidationTimeoffController extends Controller {
    
  public function all() {
		$authUserTypeId = Auth::user()->user_type_id;
		$authUserId = Auth::user();

		if($authUserId == 1):
			$validations = ValidationTimeoff::select('validation_timeoffs.id as validation_id',
			'employee_id','manager_id','manager_validation_date',
			'users.lastname as student_name')
			->join('users','users.id','validation_timeoffs.employee_id')
			->paginate(25);
			
			return Response::json($validations);
		else:
			return Response::json("Vous n'avez pas les droits");
		endif;
  }

	public function getTotalValidation() {
		$validation = ValidationTimeoff::count('id');
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
						$employeeValidationTimeoff = ValidationTimeoff::select('validation_timeoffs.id')
						->join('employees','employees.id','validation_timeoffs.employee_id')
						->where('employees.id',$employee->employee_id)
						->where('validate',1)
						->get();

						$employeeValidateTimeoffByManager = ValidationTimeoff::select('validation_timeoffs.form_timeoff_id',
						'validation_timeoffs.manager_validation_date as date_validate',
						'users.firstname as manager_name')
						->join('employees', 'employees.id', 'validation_timeoffs.employee_id')
						->join('users','users.id','validation_timeoffs.manager_id')
						->where('employees.id',$employee->employee_id)
						->where('validate',1)
						->get();

						$employeeNotValidateTimeoffByManager = ValidationTimeoff::select('validation_timeoffs.form_timeoff_id',
						'validation_timeoffs.manager_validation_date as date_validate',
						'users.firstname as manager_name')
						->join('employees', 'employees.id', 'validation_timeoffs.employee_id')
						->join('users','users.id','validation_timeoffs.manager_id')
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

	public function updateManagerValidationTimeoff(Request $request){
		$userAuthorized = [2, 3];
		if(in_array(Auth::user()->user_type_id, $userAuthorized)):
			$input = $request->all();
			$validations = ValidationTimeoff::find($input['validation_timeoff_id']);
			$validations->manager_id = $input['manager_id'];
			$validations->validate = $input['validate'];
			$validations->manager_validation_date = date('Y-m-d H:m:s');
			$validations->save();


			return Response::json('succès');
		else:
			return Response::json("Vous n'avez pas les droits");
		endif;
	}
}
