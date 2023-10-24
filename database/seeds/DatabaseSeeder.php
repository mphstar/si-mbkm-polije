<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Student;
use Illuminate\Support\Facades\Hash;

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
            'remember_token' => Str::random(10),
        ]);
        Student::create([
            'name' => 'student',
            'address' => '123 Main Street',
            'phone' => '1234567890',
            'nim' => '123456789',
            'email' => 'student@gmail.com',
            'username' => 'student',
            'password' => Hash::make('123'),
        ]);
        // $this->call(UsersTableSeeder::class);
    }
}
