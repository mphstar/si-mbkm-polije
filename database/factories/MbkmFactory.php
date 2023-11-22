<?php

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Mbkm;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Mbkm::class, function (Faker $faker) {
    return [
        'partner_id' => 1, // Ganti dengan ID partner yang sesuai
        'jurusan' => 'Teknologi Informasi',
        'program_name' => $faker->sentence,
        'capacity' => $faker->numberBetween(50, 200),
        'start_date' => $faker->date,
        'start_reg' => $faker->date,
        'end_reg' => $faker->date,
        'end_date' => $faker->date,
        'info' => $faker->sentence,
        'status_acc' => 'pending',
        'is_active' => 'inactive',
        'task_count' => $faker->numberBetween(1, 10),
        'semester' => $faker->randomDigitNotNull,
        'nama_penanggung_jawab' => $faker->name,
        'jumlah_sks' => $faker->numberBetween(10, 20),
        'id_jenis' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ];
});

