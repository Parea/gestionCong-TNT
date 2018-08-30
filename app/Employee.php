<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id','service_id','timeoff_granted','timeoff_in_progress','taken_timeoff','total_timeoff','active','manager'
    ];
}
