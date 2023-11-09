<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\User;
use App\Course;
use App\Models\Lecturer;
use App\Models\Partner;
use App\Models\Mbkm;
use App\Models\Departmen;
use App\ProgramStudy;
use App\Models\Students;
use App\Models\RegisterMbkm;

class PembimbingTest extends TestCase
{
    use RefreshDatabase;
    public function setUp() : void {
        parent::setUp();
        $user = User::create([
            'name' => 'dospem',
            'email' => 'dospem@gmail.com',
            'level' => 'dospem',
            'email_verified_at' => now(),
            'password' => Hash::make('123'),
        ]);
        $response = $this->post('/admin/login', [
            'email' => 'dospem@gmail.com', 
            'password' => '123', 
        ]);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testViewMbkmGrade()
    {
        factory(Partner::class, 1)->create();
        factory(Mbkm::class, 1)->create([
            'end_date' => '2030-01-01'
        ]);
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        factory(Lecturer::class, 1)->create();
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'accepted',
            'pembimbing' => 1,
            'partner_grade' => 'grade.zip',
        ]);
        $response = $this->get('/admin/nilaimbkm');
        $response->assertStatus(200);
    }
    public function testViewStudentProgress()
    {
        factory(Partner::class, 1)->create();
        factory(Mbkm::class, 1)->create([
            'end_date' => '2030-01-01'
        ]);
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        factory(Lecturer::class, 1)->create();
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'accepted',
            'pembimbing' => 1,
            'partner_grade' => 'grade.zip',
        ]);
        $response = $this->get('/admin/progress-mahasiswa');
        $response->assertStatus(200);
    }

    public function testViewInputNilai()
    {
        factory(Partner::class, 1)->create();
        factory(Mbkm::class, 1)->create([
            'end_date' => '2030-01-01'
        ]);
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        factory(Lecturer::class, 1)->create();
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'accepted',
            'pembimbing' => 1,
            'partner_grade' => 'grade.zip',
        ]);
        $response = $this->get('/admin/nilaimbkm/1/inputnilai');
        $response->assertStatus(200);
    }

    public function testInputNilai()
    {
        factory(Partner::class, 1)->create();
        factory(Mbkm::class, 1)->create([
            'end_date' => '2030-01-01'
        ]);
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        factory(Lecturer::class, 1)->create();
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'accepted',
            'pembimbing' => 1,
            'partner_grade' => 'grade.zip',
        ]);
        factory(Course::class, 5)->create();
        $requestData = [
            '1' => '100',
            '2' => '80',
        ];
        $response = $this->post('/admin/nilaimbkm/1/prosesNilai', $requestData);
        $response->assertSessionHas('test', 'success');
        $response->assertStatus(302);
    }
}
