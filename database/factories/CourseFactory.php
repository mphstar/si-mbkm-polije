<?php

use App\Course;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(Course::class, function (Faker $faker) {
    return [
        'program_id' => 1,
        'name' => $faker->name,
        'semester' => 5,
        'tahun_kurikulum' => 2021,
    ];
});
