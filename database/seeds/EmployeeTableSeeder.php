<?php

use Illuminate\Database\Seeder;

class EmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employees')->insert([
            'user_id' => 2,
            'service_id' => 5,
            'timeoff_granted' => 30,
            'timeoff_in_progress' => 0,
            'taken_timeoff' => 0,
            'total_timeoff' => 30,
            'active' => 1

        ]);

        DB::table('employees')->insert([
            'user_id' => 3,
            'service_id' => 3,
            'timeoff_granted' => 30,
            'timeoff_in_progress' => 0,
            'taken_timeoff' => 0,
            'total_timeoff' => 30,
            'active' => 1

        ]);

        DB::table('employees')->insert([
            'user_id' => 4,
            'service_id' => 1,
            'timeoff_granted' => 30,
            'timeoff_in_progress' => 0,
            'taken_timeoff' => 0,
            'total_timeoff' => 30,
            'active' => 1

        ]);

        DB::table('employees')->insert([
            'user_id' => 5,
            'service_id' => 3,
            'timeoff_granted' => 30,
            'timeoff_in_progress' => 0,
            'taken_timeoff' => 0,
            'total_timeoff' => 30,
            'active' => 1

        ]);

        DB::table('employees')->insert([
            'user_id' => 6,
            'service_id' => 1,
            'timeoff_granted' => 30,
            'timeoff_in_progress' => 0,
            'taken_timeoff' => 0,
            'total_timeoff' => 30,
            'active' => 1

        ]);

        DB::table('employees')->insert([
            'user_id' => 7,
            'service_id' => 3,
            'timeoff_granted' => 30,
            'timeoff_in_progress' => 0,
            'taken_timeoff' => 0,
            'total_timeoff' => 30,
            'active' => 1

        ]);

        DB::table('employees')->insert([
            'user_id' => 8,
            'service_id' => 2,
            'timeoff_granted' => 30,
            'timeoff_in_progress' => 0,
            'taken_timeoff' => 0,
            'total_timeoff' => 30,
            'active' => 1

        ]);

        DB::table('employees')->insert([
            'user_id' => 9,
            'service_id' => 3,
            'timeoff_granted' => 30,
            'timeoff_in_progress' => 0,
            'taken_timeoff' => 0,
            'total_timeoff' => 30,
            'active' => 1

        ]);

        DB::table('employees')->insert([
            'user_id' => 10,
            'service_id' => 4,
            'timeoff_granted' => 30,
            'timeoff_in_progress' => 0,
            'taken_timeoff' => 0,
            'total_timeoff' => 30,
            'active' => 1

        ]);

        DB::table('employees')->insert([
            'user_id' => 11,
            'service_id' => 1,
            'timeoff_granted' => 30,
            'timeoff_in_progress' => 0,
            'taken_timeoff' => 0,
            'total_timeoff' => 30,
            'active' => 1

        ]);
    }
}
