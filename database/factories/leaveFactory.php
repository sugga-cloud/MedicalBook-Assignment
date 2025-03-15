<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Leave;
use App\User;
use Faker\Generator as Faker;

$factory->define(Leave::class, function (Faker $faker) {
    return [
        'employee_id' => User::inRandomOrder()->first()->id ?? $faker->numberBetween(1, 5), // Random user ID (1-5)
        'leave_type' => $faker->randomElement(['Sick Leave', 'Casual Leave', 'Annual Leave']),
        'date_from' => $faker->date(),
        'date_to' => $faker->date(),
        'days' => $faker->numberBetween(1, 10),
        'reason' => $faker->sentence(),
    ];
});
