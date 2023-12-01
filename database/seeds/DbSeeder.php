<?php

use App\Course;
use App\Models\Departmen;
use App\Models\JenisMbkm;
use App\Models\Partner;
use App\Models\Students;
use App\Models\TemplateNilai;
use App\ProgramStudy;
use App\User;
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
        // Create student
        $userStudent = User::create([
            "name" => "Bintang",
            "email" => "student@gmail.com",
            "password" => bcrypt("12345678"),
            "level" => "student"
        ]);

        $userStudent->student()->create([
            "name" => "Bintang",
            "address" => "Jln Letjend Panjaitan Gg 10 No 50",
            "phone" => "0895393933040",
            "nim" => "e41210618",
            "jurusan" => "897fc4d4-1073-11ec-96e8-fefcfe8d8c7c",
            "program_studi" => "436b1b62-1130-11ec-b446-fefcfe8d8c7c",
            "semester" => 5,

        ]);

        // Create Admin
        $userAdmin = User::create([
            "name" => "Admin",
            "email" => "admin@gmail.com",
            "password" => bcrypt("12345678"),
            "level" => "admin"
        ]);

        // Create mitra external
        Partner::create([
            "partner_name" => "Mitra Luar",
            "address" => "Jln Mastrip",
            "phone" => "0895393933040",
            "status" => "accepted",
            "jenis_mitra" => "luar kampus",
        ]);

        // Create Mitra Internal
        $userMitraDalam = User::create([
            "name" => "Mitra Dalam",
            "email" => "mitradalam@gmail.com",
            "password" => bcrypt("12345678"),
            "level" => "mitra"
        ]);

        $userMitraDalam->partner()->create([
            "partner_name" => "Mitra Dalam",
            "address" => "Jln Mastrip",
            "phone" => "0895393933040",
            "status" => "accepted",
            "jenis_mitra" => "dalam kampus",
        ]);

        // Create Kaprodi Teknologi Informasi
        $userKaprodiTeknologiInformasi = User::create([
            "name" => "Kaprodi TIF",
            "email" => "kaproditif@gmail.com",
            "password" => bcrypt("12345678"),
            "level" => "kaprodi"
        ]);

        $userKaprodiTeknologiInformasi->lecturer()->create([
            "lecturer_name" => "Kaprodi TIF",
            "address" => "Jln Mastrip",
            "phone" => "082211221122",
            "nip" => "199205282018032001",
            "jurusan" => "897fc4d4-1073-11ec-96e8-fefcfe8d8c7c",
            "program_studi" => "436b1b62-1130-11ec-b446-fefcfe8d8c7c",
            "status" => "kaprodi",
        ]);

        // Create Dosepem
        $userDospem = User::create([
            "name" => "Dosen Pembimbing",
            "email" => "dospem@gmail.com",
            "password" => bcrypt("12345678"),
            "level" => "dospem"
        ]);

        $userDospem->lecturer()->create([
            "lecturer_name" => "Dosen Pembimbing",
            "address" => "Jln Mastrip",
            "phone" => "082211221122",
            "nip" => "D19930831202103201",
            "jurusan" => "897fc4d4-1073-11ec-96e8-fefcfe8d8c7c",
            "status" => "dosen pembimbing",
        ]);

        // Jenis MBKM
        JenisMbkm::create([
            'jenismbkm' => "Program CF",
            'kategori_jenis' => "internal"
        ]);
        JenisMbkm::create([
            'jenismbkm' => "Matching Fund",
            'kategori_jenis' => "internal"
        ]);
        JenisMbkm::create([
            'jenismbkm' => "MSIB Studi Independen",
            'kategori_jenis' => "external"
        ]);
        JenisMbkm::create([
            'jenismbkm' => "MSIB Magang",
            'kategori_jenis' => "external"
        ]);

        TemplateNilai::create([
            "id" => 1,
            "nama" => "Form Pengajuan MBKM Eksternal",
            "file" => "1701151939-Form Pengajuan MBKM Eksternal.pdf"
        ]);
        TemplateNilai::create([
            "id" => 2,
            "nama" => "Template Penilaian Mitra",
            "file" => "1701151939-Template Penilaian Mitra.pdf"
        ]);
    }
}
