<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
     {
         DB::table('user_types')->insert([
             'name' => "Admin",
         ]);

         DB::table('user_types')->insert([
            'name' => "Directeur Géneral",
        ]);
     
        DB::table('user_types')->insert([
            'name' => "Responsable service",
        ]);

         DB::table('user_types')->insert([
             'name' => "Agents",
         ]);
     
     }
}
