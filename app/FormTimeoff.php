<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormTimeoff extends Model
{
    protected $fillable = [
        'motif', 'other_motif', 'star_timeoff', 'end_timeoff', 'numbers_days_taken','employee_id'
    ];
}
