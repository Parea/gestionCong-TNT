<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceDetail extends Model
{
    protected $fillable = [
        'service_id', 'employee_id', 'manager_id'
    ];
}
