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
        factory('App\ServiceDetail', 25)->create();
    }
}
