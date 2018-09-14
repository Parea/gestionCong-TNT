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
}
