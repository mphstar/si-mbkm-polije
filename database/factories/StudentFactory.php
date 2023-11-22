<?php

use App\Models\Students;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(Students::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'address' => 'jember',
        'phone' => $faker->phoneNumber,
        'nim' => $faker->unique()->userName,
        'jurusan' => 'Teknologi Informasi',
        'program_studi' => 'Teknik Informatika',
        'users_id' => 1,
    ];
});
