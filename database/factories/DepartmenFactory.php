<?php

use App\Models\Departmen;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(Departmen::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'created_at' => now(),
        'updated_at' => now()
    ];
});
