<?php

use App\Models\RegisterMbkm;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(RegisterMbkm::class, function (Faker $faker) {
    return [
        'student_id' => 1,
        'mbkm_id' => 1,
        'status' => 'pending',
        'created_at' => now(),
        'updated_at' => now()
    ];
});
