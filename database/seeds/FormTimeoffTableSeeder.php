<?php

use Illuminate\Database\Seeder;

class FormTimeoffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('form_timeoffs')->insert([
            'motif' => "Vacances",
            'start_timeoff' => "2018-09-12 08:00:00",
            'end_timeoff' => "2018-09-22 08:00:00",
            'number_days_taken' => 10,
            'employee_id' => 4
        ]);
        DB::table('form_timeoffs')->insert([
            'motif' => "Vacances",
            'start_timeoff' => "2018-09-11 08:00:00",
            'end_timeoff' => "2018-09-11 08:00:00",
            'number_days_taken' => 5,
            'employee_id' => 4
            ]);
        DB::table('form_timeoffs')->insert([
            'motif' => "Vacances",
            'start_timeoff' => "2018-09-11 08:00:00",
            'end_timeoff' => "2018-09-11 08:00:00",
            'number_days_taken' => 25,
            'employee_id' => 3
            ]);
        DB::table('form_timeoffs')->insert([
            'motif' => "Vacances",
            'number_days_taken' => 10,
            'start_timeoff' => "2018-10-15 08:00:00",
            'end_timeoff' => "2018-10-20 08:00:00",
            'employee_id' => 3
        ]);
        DB::table('form_timeoffs')->insert([
            'motif' => "Vacances",
            'start_timeoff' => "2018-10-25 08:00:00",
            'end_timeoff' => "2018-11-08 08:00:00",
            'number_days_taken' => 10,
            'employee_id' => 4
        ]);
        DB::table('form_timeoffs')->insert([
            'motif' => "Vacances",
            'start_timeoff' => "2018-11-05 08:00:00",
            'end_timeoff' => "2018-11-25 08:00:00",
            'number_days_taken' => 20,
            'employee_id' => 2
        ]);
        DB::table('form_timeoffs')->insert([
            'motif' => "Vacances",
            'start_timeoff' => "2018-11-25 08:00:00",
            'end_timeoff' => "2018-11-30 08:00:00",
            'number_days_taken' => 5,
            'employee_id' => 2
        ]);
    }
}
