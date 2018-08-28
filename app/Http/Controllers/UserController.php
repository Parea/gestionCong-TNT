<?php

namespace App\Http\Controllers;

use App\User;
use App\Employee;
use App\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller {
	public $successStatus = 200;

	//Cet fonction permet a l'utilisateur de ce connecter avec son email et mot de passe
	public function login() {
		if(Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
			$user = Auth::user();
			$employee = ($user->user_type_id == 4)?Employee::select('*')->where('user_id', $user->id)->where('active', 1)->get()->first():null;

			$success['token'] = $user->createToken('Laravel')->accessToken;
			$success['lastname'] = $user->lastname;
			$success['firstname'] = $user->firstname;
			$success['email'] = $user->email;
			$success['user_type_id'] = $user->user_type_id;
			$success['avatar'] = $user->avatar;
			$success['employee_id'] = ($employee)?$employee->id:0;
			$success['service_id'] = ($employee)?$employee->service_id:0;
			return Response::json([$success]);
		}
		else {
			return reponse()->json(['error'=>'Non autorisé'],401);
		}
	}

	//Cet fonction permet a l'admin de créer des utilisateur
	public function register(Request $request) {
		if(Auth::user()->user_type_id == 1):
			$validator = Validator::make($request->all(), [
				'lastname' => 'required',
				'firstname' => 'required',
				'email' => 'required|email',
				'password' => 'required|min:6',
				'c_password' => 'required|same:password',
				'user_type_id' => 'required',
				'avatar' => 'required',
			]);

			if($validator->fails()) {
				return response()->json(['error', $validator->errors()], 401);
			}

			if($request->hasfile('avatar')):
				$file = $request->file('avatar');
				$extension = $file->getClientOriginalExtension(); // getting image extension
				$filename = substr( md5( 1 . '-' . time() ), 0, 15).'.'.$extension;
				$file->move('uploads/images/', $filename);
			endif;

			$input = $request->all();
			$input['avatar'] = $filename;
			$input['password'] = bcrypt($input['password']);

			$user = user::create($input);
			$employee = Employee::select('*')->where('user_id', $user->id)->where('active', 1)->get()->first();
			dd($employee);

			$success['token'] =  $user->createToken('Laravel')->accessToken;
				$success['id'] =  $user->id;
				$success['lastname'] =  $user->lastname;
				$success['firstname'] =  $user->firstname;
				$success['email'] =  $user->email;
				$success['user_type_id'] =  $user->user_type_id;
				$success['employee_id'] =  $employee['user_id'];
				dd($success);
				return Response::json($success);
		else:
			return response::json(["error"=>"Vous n'avez pas les droits"]);
		endif;
	}

	public function fillStudent(Request $request) {
		$validator = Validator::make($request->all(), [
			'service_id' => 'required',
			'user_id' => 'required',
		]);

		if($validator->fails()) {
			return response()->json(['error'=>$validator->errors()], 401);
		}

		$input = $request->all();
		$employeData = [
			'user_id' => $input['user_id'],
			'service_id' => $input['service_id'],
			'active' => 1,
		];

		$employee = Employee::create($employeData);

		$success['user_id'] = $employee->user_id;
		$success['service_id'] = $employee->service_id;

		return Response::json($success);
	}

	
}
