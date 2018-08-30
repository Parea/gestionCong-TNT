<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'lastname' => 'ADMIN',
            'firstname' => 'admin',
            'email' => 'admin@hotmail.fr',
            'password' => 'admin',
            'user_type_id' => 1,
            'avatar' => 'npc.png',
            'created_at' => '2018-07-05 12:03:37',
            'updated_at' => '2018-07-05 12:03:37'
        ]);
        DB::table('users')->insert([
            'lastname' => 'DUGUE',
            'firstname' => 'Paul',
            'email' => 'paul.dugue@hotmail.fr',
            'password' => 'pauldugue',
            'user_type_id' => 2,
            'avatar' => 'npc.png',
            'created_at' => '2018-07-05 12:03:37',
            'updated_at' => '2018-07-05 12:03:37'
        ]);
        DB::table('users')->insert([
            'lastname' => 'ADAMS',
            'firstname' => 'Roonui',
            'email' => 'roonui.adams@hotmail.fr',
            'password' => 'adamsroonui',
            'user_type_id' => 3,
            'avatar' => 'npc.png',
            'created_at' => '2018-07-05 12:03:37',
            'updated_at' => '2018-07-05 12:03:37'
        ]);
        DB::table('users')->insert([
         'lastname' => 'TAIE',
         'firstname' => 'Taataparea',
         'email' => 'parea.taie@hotmail.fr',
         'password' => 'pareataie',
         'user_type_id' => 3,
         'avatar' => 'npc.png',
         'created_at' => '2018-07-05 12:03:37',
         'updated_at' => '2018-07-05 12:03:37'
        ]);
        DB::table('users')->insert([
            'lastname' => 'MAUI',
            'firstname' => 'Apetahi',
            'email' => 'apetahi.maui@hotmail.fr',
            'password' => 'apetahimaui',
            'user_type_id' => 3,
            'avatar' => 'npc.png',
            'created_at' => '2018-07-05 12:03:37',
            'updated_at' => '2018-07-05 12:03:37'
        ]);
        DB::table('users')->insert([
            'lastname' => 'TKT',
            'firstname' => 'Cedric',
            'email' => 'cedric.tkt@hotmail.fr',
            'password' => 'cedtkt',
            'user_type_id' => 3,
            'avatar' => 'npc.png',
            'created_at' => '2018-07-05 12:03:37',
            'updated_at' => '2018-07-05 12:03:37'
        ]);
        
    }
}
