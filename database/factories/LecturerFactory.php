<?php

use App\Models\Lecturer;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(Lecturer::class, function (Faker $faker) {
    return [
        'lecturer_name' => $faker->company,
        'address' => $faker->address,
        'phone' => $faker->phoneNumber,
        'nip' => $faker->unique()->userName,
        'users_id' => 1,
        'status' => 'dosen pembimbing',
    ];
});
