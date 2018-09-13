<?php

use Illuminate\Database\Seeder;

class ServiceDetailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('service_details')->insert([
            'service_id' => 3,
            'employee_id' => 3,
            'manager_id' => 2
        ]);
        DB::table('service_details')->insert([
            'service_id' => 1,
            'employee_id' => 4,
            'manager_id' => 2
        ]);
        DB::table('service_details')->insert([
            'service_id' => 3,
            'employee_id' => 5,
            'manager_id' => 3
        ]);

        DB::table('service_details')->insert([
            'service_id' => 1,
            'employee_id' => 6,
            'manager_id' => 4
        ]);
        DB::table('service_details')->insert([
            'service_id' => 3,
            'employee_id' => 7,
            'manager_id' => 3
        ]);
        DB::table('service_details')->insert([
            'service_id' => 2,
            'employee_id' => 8,
            'manager_id' => 2
        ]);
        DB::table('service_details')->insert([
            'service_id' => 3,
            'employee_id' => 9,
            'manager_id' => 3
        ]);
        DB::table('service_details')->insert([
            'service_id' => 1,
            'employee_id' => 10,
            'manager_id' => 4
        ]);
    }
}
