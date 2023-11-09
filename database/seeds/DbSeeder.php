<?php

use App\Models\Departmen;
use App\ProgramStudy;
use Illuminate\Database\Seeder;

class DbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $department = [
            array(
                "id" => 1,
                "name" => "Teknologi Informasi"
            ),
            array(
                "id" => 2,
                "name" => "Kesehatan"
            ),
        ];

        foreach ($department as $key => $value) {
            Departmen::create($value);
        }

        $program = [
            array(
                "id" => 1,
                "department_id" => 1,
                "name" => "Teknik Informatika"
            ),
            array(
                "id" => 2,
                "department_id" => 1,
                "name" => "Teknik Komputer"
            ),
            array(
                "id" => 3,
                "department_id" => 1,
                "name" => "Management Informatika"
            ),
        ];

        foreach ($program as $key => $value) {
            ProgramStudy::create($value);
        }

        $course = [
            array(
                "program_id" => 1,
                "name" => "Aplikasi Sistem Tertanam",
                "semester" => 5,
                "tahun_kurikulum" => 2021,
                "sks" => 2
            ),
            array(
                "program_id" => 1,
                "name" => "Sistem Cerdas",
                "semester" => 5,
                "tahun_kurikulum" => 2021,
                "sks" => 2
            ),
            array(
                "program_id" => 1,
                "name" => "Multimedia Permainan",
                "semester" => 5,
                "tahun_kurikulum" => 2021,
                "sks" => 2
            ),
            array(
                "program_id" => 1,
                "name" => "Workshop Sistem Cerdas",
                "semester" => 5,
                "tahun_kurikulum" => 2021,
                "sks" => 4
            ),
        ];
    }
}
