<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Employee;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','lastname', 'firstname', 'email', 'password', 'user_type_id','avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function AauthAcessToken(){
        return $this->hasMany('\App\OauthAccessToken');
    }

    public static function getMyCurrentService()
    {
        return Employee::select('employees.id as employee_id', 'services.name as service_name', 'services.id as service_id')
        ->join('services', 'services.id', 'employees.service_id')
        ->where('employees.user_id', Auth::user()->id)
        ->where('employees.active', 1)
        ->orderBy('employees.id', 'desc')
        ->get()->first();
    } 
    public static function getCurrentServiceOfAEmployee($serviceId)
    {
        return Employee::select('employees.user_id as user_id', 'employees.id as employee_id', 'services.name as service_name', 'services.id as service_id')
        ->join('services', 'services.id', 'employees.service_id')
        ->join('users', 'users.id', 'employees.user_id')
        ->where('employees.service_id', $serviceId)
        ->where('employees.active', 1)
        ->orderBy('employees.id', 'desc')
        ->get()->first();
    } 
    public static function getAllServicesActive() 
    {
        return Employee::select('employees.id as employee_id', 'services.name as service_name', 'services.id as service_id')
        ->join('services', 'services.id', 'employees.service_id')
        ->where('employees.active', 1)
        ->orderBy('employees.id', 'desc')
        ->get();
    }
}
