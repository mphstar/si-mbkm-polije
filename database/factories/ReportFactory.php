<?php

use App\Report;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(Report::class, function (Faker $faker) {
    return [
        'reg_mbkm_id' => 1,
        'file' => 'file.pdf',
        'file_info' => 'Test File',
        'status' => 'pending',
        'upload_date' => now(),
        'created_at' => now(),
        'updated_at' => now()
    ];
});
