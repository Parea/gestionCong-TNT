<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(UserTypeTableSeeder::class);
        $this->call(ServiceTableSeeder::class);
        $this->call(ServiceDetailTableSeeder::class);
        $this->call(EmployeeTableSeeder::class);
        $this->call(FormTimeoffTableSeeder::class);
        $this->call(AdminTableSeeder::class);
        $this->call(ValidationTimeoffTableSeeder::class);
        $this->call(TimeoffCountTableSeeder::class);
    }
}
