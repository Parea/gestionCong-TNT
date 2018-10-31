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
            'password' => bcrypt('admin'),
            'avatar' => 'npc.png',
            'gender' => '',
            'user_type_id' => 1,
            'created_at' => '2018-07-05 12:03:37',
            'updated_at' => '2018-07-05 12:03:37'
        ]);
        DB::table('users')->insert([
            'lastname' => 'DUGUE',
            'firstname' => 'Paul',
            'email' => 'paul.dugue@hotmail.fr',
            'password' => bcrypt('pauldugue'),
            'avatar' => 'npc.png',
            'gender' => 'homme',
            'user_type_id' => 2,
            'created_at' => '2018-07-05 12:03:37',
            'updated_at' => '2018-07-05 12:03:37'
        ]);
        DB::table('users')->insert([
            'lastname' => 'ADAMS',
            'firstname' => 'Roonui',
            'email' => 'roonui.adams@hotmail.fr',
            'password' => bcrypt('adamsroonui'),
            'avatar' => 'npc.png',
            'gender' => 'homme',
            'user_type_id' => 3,
            'created_at' => '2018-07-05 12:03:37',
            'updated_at' => '2018-07-05 12:03:37'
        ]);
        DB::table('users')->insert([
            'lastname' => 'MOU',
            'firstname' => 'Sandrine',
            'email' => 'sandrine.mou@hotmail.fr',
            'password' => bcrypt('sandrinemou'),
            'avatar' => 'npc.png',
            'gender' => 'femme',
            'user_type_id' => 3,
            'created_at' => '2018-07-05 12:03:37',
            'updated_at' => '2018-07-05 12:03:37'
        ]);
        DB::table('users')->insert([
         'lastname' => 'TAIE',
         'firstname' => 'Taataparea',
         'email' => 'parea.taie@hotmail.fr',
         'password' => bcrypt('pareataie'),
         'avatar' => 'parea.jpg',
         'gender' => 'homme',
         'user_type_id' => 4,
         'created_at' => '2018-07-05 12:03:37',
         'updated_at' => '2018-07-05 12:03:37'
        ]);
        DB::table('users')->insert([
            'lastname' => 'MAUI',
            'firstname' => 'Apetahi',
            'email' => 'apetahi.maui@hotmail.fr',
            'password' => bcrypt('apetahimaui'),
            'avatar' => 'npc.png',
            'gender' => 'femme',
            'user_type_id' => 4,
            'created_at' => '2018-07-05 12:03:37',
            'updated_at' => '2018-07-05 12:03:37'
        ]);
        DB::table('users')->insert([
            'lastname' => 'TKT',
            'firstname' => 'Cedric',
            'email' => 'cedric.tkt@hotmail.fr',
            'password' => bcrypt('cedtkt'),
            'avatar' => 'npc.png',
            'gender' => 'homme',
            'user_type_id' => 4,
            'created_at' => '2018-07-05 12:03:37',
            'updated_at' => '2018-07-05 12:03:37'
        ]);
        DB::table('users')->insert([
            'lastname' => 'TAIE',
            'firstname' => 'Boris',
            'email' => 'boris.taie@hotmail.fr',
            'password' => bcrypt('boristaie'),
            'avatar' => 'npc.png',
            'gender' => 'homme',
            'user_type_id' => 4,
            'created_at' => '2018-07-05 12:03:37',
            'updated_at' => '2018-07-05 12:03:37'
        ]);
        DB::table('users')->insert([
            'lastname' => 'TATA',
            'firstname' => 'Lolo',
            'email' => 'tata.lolot@hotmail.fr',
            'password' => bcrypt('tatalolo'),
            'avatar' => 'npc.png',
            'gender' => 'femme',
            'user_type_id' => 4,
            'created_at' => '2018-07-05 12:03:37',
            'updated_at' => '2018-07-05 12:03:37'
        ]);
        DB::table('users')->insert([
            'lastname' => 'TARIHAA',
            'firstname' => 'Teraitea',
            'email' => 'tarihaa.terai@hotmail.fr',
            'password' => bcrypt('tarihaaterai'),
            'avatar' => 'npc.png',
            'gender' => 'homme',
            'user_type_id' => 4,
            'created_at' => '2018-07-05 12:03:37',
            'updated_at' => '2018-07-05 12:03:37'
        ]);
        
    }
}
