<?php

use App\Models\Partner;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(Partner::class, function (Faker $faker) {
    return [
        'partner_name' => $faker->company,
        'address' => $faker->address,
        'phone' => $faker->phoneNumber,
        'email' => $faker->unique()->safeEmail,
        'status' => 'pending',
        'jenis_mitra' => 'luar kampus',
        'username' => $faker->userName,
        'password' => Hash::make('password'), // Ganti 'password' dengan kata sandi yang diinginkan
        'created_at' => now(),
        'updated_at' => now()
    ];
});
