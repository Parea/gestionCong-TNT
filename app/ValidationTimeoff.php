<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValidationTimeoff extends Model
{
    protected $fillable = [
        'form_timeoff_id', 'manager_id', 'validate'
    ];
}
