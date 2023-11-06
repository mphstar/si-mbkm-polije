<?php

use App\ProgramStudy;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(ProgramStudy::class, function (Faker $faker) {
    return [
        'department_id' => 1,
        'name' => $faker->sentence,
        'created_at' => now(),
        'updated_at' => now()
    ];
});
