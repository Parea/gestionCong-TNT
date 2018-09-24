<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValidationTimeoff extends Model
{
    protected $fillable = [
        'form_timeoff_id', 'employee_id','manager_id', 'validate','manager_validation_date'
    ];
}
