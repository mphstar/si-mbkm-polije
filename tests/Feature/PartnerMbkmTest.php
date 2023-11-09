<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\User;
use App\Models\Partner;
use App\Models\Mbkm;
use App\Models\RegisterMbkm;
use App\Models\Departmen;
use App\Models\Students;
use App\ProgramStudy;
use App\Report;

class PartnerMbkmTest extends TestCase
{
    use RefreshDatabase;
    public function setUp() : void {
        parent::setUp();
        $user = User::create([
            'name' => 'mitra',
            'email' => 'mitra@gmail.com',
            'level' => 'mitra',
            'email_verified_at' => now(),
            'password' => Hash::make('123'),
            // 'remember_token' => Str::random(10),
        ]);
        $response = $this->post('/admin/login', [
            'email' => 'mitra@gmail.com', 
            'password' => '123', 
        ]);
        factory(Partner::class, 1)->create();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testViewListMbkm()
    {
        factory(Mbkm::class, 1)->create();
        $response = $this->get('/admin/management-m-b-k-m');
        $response->assertStatus(200);
    }

    public function testViewFormAddDataMbkm()
    {
        $response = $this->get('/admin/management-m-b-k-m/tambah_mbkm');
        $response->assertStatus(200);
    }

    public function testAddDataMbkm()
    {
        $requestData = [
            'partner_id' => 1,
            'program_name' => 'Program Test',
            'capacity' => 2,
            'task_count' => 5,
            'semester' => 5,
            'start_reg' => '2021-01-01',
            'end_reg' => '2021-02-02',
            'start_date' => '2021-03-03',
            'end_date' => '2021-04-04',
            'info' => 'Info Test',
            'nama_penanggung_jawab' => 'John',
            'jumlah_sks' => '10',
        ];
        $response = $this->post('/admin/management-m-b-k-m/tambahdatambkm', $requestData);
        $response->assertSessionHas('status', 'success');
        $response->assertStatus(302);
    }

    public function testAddDataMbkmError()
    {
        $requestData = [
            'partner_id' => 1,
            'program_name' => 'Program Test',
            'capacity' => 2,
            'task_count' => 5,
            'semester' => 5,
            'start_reg' => '2021-01-01',
            'end_reg' => '2021-02-02',
            'start_date' => '2021-03-03',
        ];
        $response = $this->post('/admin/management-m-b-k-m/tambahdatambkm', $requestData);
        $response->assertSessionHas('status', 'error');
        $response->assertStatus(302);
    }

    public function testViewValidateStudent()
    {
        $response = $this->get('/admin/register-mbkm');
        $response->assertStatus(200);
    }

    public function testValidateStudent()
    {
        factory(Mbkm::class, 1)->create();
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'pending',
        ]);
        $requestData = [
            'id' => 1,
            'status' => 'accepted',
        ];
        $response = $this->post('/admin/validasi-peserta', $requestData);
        $response->assertSessionHas('status', 'success');
        $response->assertStatus(302);
    }

    public function testViewValidateReport()
    {
        factory(Mbkm::class, 1)->create();
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'pending',
        ]);
        $response = $this->get('/admin/validasilaporan');
        $response->assertStatus(200);
    }

    public function testViewDetailReport()
    {
        factory(Mbkm::class, 1)->create([
            'end_date' => '2040-01-01'
        ]);
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'accepted',
        ]);
        factory(Report::class, 1)->create();
        $response = $this->get('/admin/validasilaporan/1/detail_laporan');
        $response->assertSessionHas('status', 'success');
        $response->assertStatus(200);
    }

    public function testUpdateStatusReport()
    {
        factory(Mbkm::class, 1)->create([
            'end_date' => '2040-01-01'
        ]);
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'accepted',
        ]);
        factory(Report::class, 1)->create();

        $requestData = [
            'id' => 1,
            'status' => 'accepted',
            'notes' => 'oke',
        ];
        $response = $this->post('/admin/validasilaporan/{id}/approve-laporan', $requestData);
        $response->assertSessionHas('status', 'success');
        $response->assertStatus(302);
    }
    
    public function testViewPartnerGrading()
    {
        $response = $this->get('/admin/penilaian-mitra');
        $response->assertStatus(200);
    }

    public function testViewFormPartnerGradingErrorWhereTaskUncomplete()
    {
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
        factory(Report::class, 1)->create([
            'reg_mbkm_id' => 1,
            'status' => 'accepted'
        ]);
        $response = $this->get('/admin/penilaian-mitra/1/updating');
        $response->assertSessionHas('status', 'report not complete');
    }

    public function testViewFormPartnerGradingSuccess()
    {
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
        $response = $this->get('/admin/penilaian-mitra/1/updating');
        $response->assertSessionHas('status', 'success');
    }

    public function testUploadPartnerGradingSuccess()
    {
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

        Storage::fake('uploads');
        $file = UploadedFile::fake()->create('document.pdf', 100);
        
        $path = $file->store('uploads',  'uploads');

        Storage::disk('uploads')->assertExists($path);
        $requestData = [
            'file' => $file,
        ];

        $response = $this->post('/admin/penilaian-mitra/1/penilaian', $requestData);
        $response->assertSessionHas('status', 'success');
        $response->assertStatus(302);
    }

    public function testUploadPartnerGradingError()
    {
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

        Storage::fake('uploads');
        $file = UploadedFile::fake()->create('document.png', 100);
        
        $path = $file->store('uploads',  'uploads');

        Storage::disk('uploads')->assertExists($path);
        $requestData = [
            'file' => $file,
        ];

        $response = $this->post('/admin/penilaian-mitra/1/penilaian', $requestData);
        $response->assertSessionHas('status', 'file not valid');
        $response->assertStatus(302);
    }
}

