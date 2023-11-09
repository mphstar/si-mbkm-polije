<?php

use Illuminate\Database\Seeder;
use App\User;
use App\ProgramStudy;
use App\Models\Students;
use App\Models\Lecturer;
use App\Models\Partner;
use App\Models\Mbkm;
use App\Models\Departmen;
use App\Models\RegisterMbkm;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'level' => 'admin',
            'password' => Hash::make('123'),
        ]);
        User::create([
            'name' => 'student',
            'email' => 'student@gmail.com',
            'email_verified_at' => now(),
            'level' => 'student',
            'password' => Hash::make('123'),
        ]);
        User::create([
            'name' => 'mitra',
            'email' => 'mitra@gmail.com',
            'email_verified_at' => now(),
            'level' => 'mitra',
            'password' => Hash::make('123'),
        ]);
        User::create([
            'name' => 'kaprodi',
            'email' => 'kaprodi@gmail.com',
            'email_verified_at' => now(),
            'level' => 'kaprodi',
            'password' => Hash::make('123'),
        ]);
        User::create([
            'name' => 'dospem',
            'email' => 'dospem@gmail.com',
            'email_verified_at' => now(),
            'level' => 'dospem',
            'password' => Hash::make('123'),
        ]);
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        factory(Lecturer::class, 1)->create();
        factory(Partner::class, 1)->create();
        factory(Mbkm::class, 10)->create();
        factory(RegisterMbkm::class, 1)->create();
        // $this->call(UsersTableSeeder::class);
    }
}