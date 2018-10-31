<?php

use Illuminate\Database\Seeder;

class ValidationTimeoffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('validation_timeoffs')->insert([
            'form_timeoff_id' => 1,
            'employee_id' => 4,
            'manager_id' => 3,
            'validate' => 1,
            'validation_date' => "2018-09-11 11:30:00"
        ]);
        DB::table('validation_timeoffs')->insert([
            'form_timeoff_id' => 2,
            'employee_id' => 6,
            'manager_id' => 3,
            'validate' => 1,
            'validation_date' => "2018-09-25 11:30:00"
        ]);
        DB::table('validation_timeoffs')->insert([
            'form_timeoff_id' => 3,
            'employee_id' => 4,
            'manager_id' => 3,
            'validate' => 0,
        ]);
        DB::table('validation_timeoffs')->insert([
            'form_timeoff_id' => 4,
            'employee_id' => 3,
            'manager_id' => 2,
            'validate' => 1,
            'validation_date' => "2018-10-11 08:00:00"
        ]);
        DB::table('validation_timeoffs')->insert([
            'form_timeoff_id' => 5,
            'employee_id' => 4,
            'manager_id' => 3,
            'validate' => 1,
            'validation_date' => "2018-10-11 11:30:00"
        ]);
        DB::table('validation_timeoffs')->insert([
            'form_timeoff_id' => 6,
            'employee_id' => 2,
            'manager_id' => 2,
            'validate' => 1,
            'validation_date' => "2018-11-01 00:00:00"
        ]);
        DB::table('validation_timeoffs')->insert([
            'form_timeoff_id' => 7,
            'employee_id' => 2,
            'manager_id' => 2,
            'validate' => 0,
        ]);
    }
}
