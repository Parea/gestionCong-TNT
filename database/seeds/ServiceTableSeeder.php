<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->insert([
            'name' => 'Service comptabilité',
            'color' => 'blue'
        ]);

        DB::table('services')->insert([
            'name' => 'Service technique',
            'color' => 'red'
        ]);

        DB::table('services')->insert([
            'name' => "Service bureau d'études",
            'color' => 'green'
        ]);

        DB::table('services')->insert([
            'name' => 'Service commercial',
            'color' => 'yellow'
        ]);
    }
}
