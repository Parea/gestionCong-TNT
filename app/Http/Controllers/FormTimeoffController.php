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
            'form_timeoffs.numbers_days_taken')
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
        'form_timeoffs.numbers_days_taken')
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
}
