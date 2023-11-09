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
            'name' => 'user',
            'email' => 'user@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123'),
            // 'remember_token' => Str::random(10),
        ]);
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 10)->create();
        factory(Lecturer::class, 10)->create();
        factory(Partner::class, 10)->create();
        factory(Mbkm::class, 10)->create();
        factory(RegisterMbkm::class, 1)->create();
        // $this->call(UsersTableSeeder::class);
    }
}