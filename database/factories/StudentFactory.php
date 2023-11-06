<?php

use App\Models\Students;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(Students::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'address' => $faker->address,
        'phone' => $faker->phoneNumber,
        'nim' => $faker->unique()->userName,
        'study_program_id' => 1,
        'users_id' => 1,
    ];
});
