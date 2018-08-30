<?php

use Faker\Generator as Faker;

$factory->define(App\Employee::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(1,10),
        'service_id' => $faker->numberBetween(1,4),
        'timeoff_granted' => $faker->numberBetween(1,25),
        'timeoff_in_progress' => $faker->numberBetween(1,25),
        'taken_timeoff' => $faker->numberBetween(1,25),
        'total_timeoff' => $faker->numberBetween(1,75),
        'active' => $faker->numberBetween(0,1),
        'manager' => $faker->numberBetween(0,1)
    ];
});
