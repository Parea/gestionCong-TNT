<?php

use Faker\Generator as Faker;

$factory->define(App\ServiceDetail::class, function (Faker $faker) {
    return [
        'service_id' => $faker->numberBetween(1,4),
        'employee_id' => $faker->numberBetween(1,10),
        'manager_id' => $faker->numberBetween(1,5)
    ];
});
