<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\User;
use App\Report;
use App\Course;
use App\Models\Lecturer;
use App\Models\Partner;
use App\Models\Mbkm;
use App\Models\Departmen;
use App\ProgramStudy;
use App\Models\Students;
use App\Models\RegisterMbkm;

class KaprodiTest extends TestCase
{
    use RefreshDatabase;
    public function setUp() : void {
        parent::setUp();
        $user = User::create([
            'name' => 'kaprodi',
            'email' => 'kaprodi@gmail.com',
            'level' => 'kaprodi',
            'email_verified_at' => now(),
            'password' => Hash::make('123'),
        ]);
        $response = $this->post('/admin/login', [
            'email' => 'kaprodi@gmail.com', 
            'password' => '123', 
        ]);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testViewValidatePartner()
    {
        $response = $this->get('/admin/acctive-account-mitra');
        $response->assertStatus(200);
    }

    public function testViewValidateMbkm()
    {
        $response = $this->get('/admin/validasi-mbkm');
        $response->assertStatus(200);
    }

    public function testViewLecturer()
    {
        $response = $this->get('/admin/lecturer');
        $response->assertStatus(200);
    }

    public function testViewStudentManagement()
    {
        $response = $this->get('/admin/manage-student');
        $response->assertStatus(200);
    }

    public function testViewFormEditStudentManagementError()
    {
        $response = $this->get('/admin/manage-student/1/edit');
        $response->assertStatus(500);
    }

    public function testViewFormEditStudentManagementSuccess()
    {
        factory(Partner::class, 1)->create();
        factory(Mbkm::class, 1)->create([
            'end_date' => '2040-01-01',
            'task_count' => '2'
        ]);
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'accepted',
        ]);
        factory(Report::class, 2)->create([
            'reg_mbkm_id' => 1,
            'status' => 'accepted'
        ]);
        $response = $this->get('/admin/manage-student/1/edit');
        $response->assertStatus(200);
    }

    public function testUpdatePembimbing()
    {
        factory(Partner::class, 1)->create();
        factory(Mbkm::class, 1)->create([
            'end_date' => '2040-01-01',
            'task_count' => '2'
        ]);
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'accepted',
        ]);
        factory(Report::class, 2)->create([
            'reg_mbkm_id' => 1,
            'status' => 'accepted'
        ]);
        $requestData = [
            'pembimbing' => '1',
        ];
        $response = $this->post('/admin/manage-student/1/editDosen', $requestData);
        $response->assertSessionHas('test', 'success');
        $response->assertStatus(302);
    }

    public function testUpdateMatkul()
    {
        factory(Partner::class, 1)->create();
        factory(Mbkm::class, 1)->create([
            'end_date' => '2040-01-01',
            'task_count' => '2',
            'semester' => 5,
        ]);
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Course::class, 5)->create();
        factory(Students::class, 1)->create([
            'nim' => 'E41211364',
        ]);
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'accepted',
        ]);
        factory(Report::class, 2)->create([
            'reg_mbkm_id' => 1,
            'status' => 'accepted'
        ]);
        $requestData = [
            'ids' => [1, 2, 3, 4]
        ];
        $response = $this->post('/admin/manage-student/1/editMatkul', $requestData);
        $response->assertSessionHas('test', 'success');
        $response->assertStatus(302);
    }
}
