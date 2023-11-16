<?php

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\JenisMbkm;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(JenisMbkm::class, function (Faker $faker) {
    return [
        'partner_id' => 1,
        'departments_id' => 1,
         // Ganti dengan ID partner yang sesuai
        'program_name' => $faker->sentence,
        'capacity' => $faker->numberBetween(50, 200),
        'start_date' => $faker->date,
        'start_reg' => $faker->date,
        'end_reg' => $faker->date,
        'end_date' => $faker->date,
        'info' => $faker->sentence,
        'status_acc' => 'pending',
        'is_active' => 'inactive',
        'id_jenis'=>'1',
        'task_count' => $faker->numberBetween(1, 10),
        'semester' => $faker->randomDigitNotNull,
        'nama_penanggung_jawab' => $faker->name,
        'jumlah_sks' => $faker->numberBetween(10, 20),
        'created_at' => now(),
        'updated_at' => now()
    ];
});

