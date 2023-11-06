<?php

use App\Models\Partner;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(Partner::class, function (Faker $faker) {
    return [
        'partner_name' => $faker->company,
        'address' => $faker->address,
        'phone' => $faker->phoneNumber,
        'status' => 'pending',
        'jenis_mitra' => 'luar kampus',
        'users_id' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ];
});
