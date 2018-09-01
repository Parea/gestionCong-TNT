<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id','service_id','timeoff_granted','timeoff_in_progress','taken_timeoff','total_timeoff','active','manager'
    ];

    public static function getEmployeesByServiceId($serviceId, $toArray = 1) {
        $employeesDatas = Employee::select(
            'employees.id as employee_id',
            'employees.timeoff_granted as timeoff_granted',
            'employees.timeoff_in_progress as timeoff_in_progress',
            'employees.taken_timeoff as taken_timeoff',
            'employees.total_timeoff as total_timeoff'
        )
        ->join('service_details', 'service_details.employee_id', 'employees.id')
        ->where('service_details.service_id', $serviceId)
        ->get();

        return ($toArray)?$employeesDatas->toArray():$employeesDatas;
    }

    public static function getEmployeesByServiceIdAndManagerId($serviceId, $toArray = 1) {
        $employeesDatas = Employee::select(
            'employees.id as employee_id',
            'employees.timeoff_granted as timeoff_granted',
            'employees.timeoff_in_progress as timeoff_in_progress',
            'employees.taken_timeoff as taken_timeoff',
            'employees.total_timeoff as total_timeoff',
            'users.firstname as teacher_firstname',
            'users.lastname as teacher_lastname'
        )
        
        ->join('service_details', 'service_details.employee_id', 'employees.id')
        ->join('users', 'users.id', 'service_details.manager_id')
        ->where('service_details.service_id', $serviceId);
        if(Auth::user()->user_type_id == 2)  $employeesDatas = $employeesDatas->where('service_details.manager_id', Auth::user()->id);


        $employeesDatas = $employeesDatas->get();
        return ($toArray)?$employeesDatas->toArray():$employeesDatas;
    }

}
