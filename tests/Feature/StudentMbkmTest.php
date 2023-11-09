<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Carbon\Carbon;
use App\User;
use App\Models\Partner;
use App\Models\Mbkm;
use App\Models\RegisterMbkm;
use App\Models\Departmen;
use App\Models\Students;
use App\ProgramStudy;
use App\Report;


class StudentMbkmTest extends TestCase
{
    use RefreshDatabase;
    public function setUp() : void {
        parent::setUp();
        $user = User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'level' => 'student',
            'email_verified_at' => now(),
            'password' => Hash::make('123'),
        ]);
        $response = $this->post('/admin/login', [
            'email' => 'user@gmail.com', 
            'password' => '123', 
        ]);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testViewMbkmPrograms()
    {
        $response = $this->get('/admin/mbkm');
        $response->assertStatus(200);
    }
    
    public function testViewFormRegisterErrorWhereStatusPending()
    {
        factory(Partner::class, 1)->create();
        factory(Mbkm::class, 1)->create();
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'pending',
        ]);
        $response = $this->get('/admin/mbkm/1/reg-mbkm');
        $response->assertStatus(302);
    }

    public function testViewFormRegisterWhereStatusAccepted()
    {
        factory(Partner::class, 1)->create();
        factory(Mbkm::class, 1)->create();
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'accepted',
        ]);
        $response = $this->get('/admin/mbkm/1/reg-mbkm');
        $response->assertStatus(302);
    }

    public function testViewFormRegisterWhereStatusReject()
    {
        factory(Partner::class, 1)->create();
        factory(Mbkm::class, 1)->create();
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'rejected',
        ]);
        $response = $this->get('/admin/mbkm/1/reg-mbkm');
        $response->assertStatus(200);
    }
    
    public function testRegisterError()
    {
        factory(Partner::class, 1)->create();
        factory(Mbkm::class, 1)->create();
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        Storage::fake('uploads');
        $file = UploadedFile::fake()->create('document.png', 100);
        
        $path = $file->store('uploads',  'uploads');

        Storage::disk('uploads')->assertExists($path);
        $requestData = [
            'student_id' => '1',
            'mbkm_id' => '1',
            'status' => 'pending',
            'file' => $file,
        ];

        $response = $this->post('/admin/mbkm/1/addreg', $requestData);
        $response->assertSessionHas('status', 'fileNotValid');
        $response->assertStatus(302);
    }

    public function testRegisterSuccess()
    {
        factory(Partner::class, 1)->create();
        factory(Mbkm::class, 1)->create();
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();

        Storage::fake('uploads');
        $file = UploadedFile::fake()->create('document.zip', 100);
        
        $path = $file->store('uploads',  'uploads');

        Storage::disk('uploads')->assertExists($path);
        $requestData = [
            'student_id' => '1',
            'mbkm_id' => '1',
            'status' => 'pending',
            'file' => $file,
        ];

        $response = $this->post('/admin/mbkm/1/addreg', $requestData);
        $response->assertSessionHas('status', 'success');
        $response->assertStatus(302);
    }

    public function testViewReport()
    {
        factory(Partner::class, 1)->create();
        factory(Mbkm::class, 1)->create([
            'end_date' => '2040-01-01'
        ]);
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'accepted',
        ]);

        $response = $this->get('/admin/mbkm-report');
        $response->assertSessionHas('status', 'success');
    }
    public function testViewReportErrorWhereNothingData()
    {
        factory(Partner::class, 1)->create();
        factory(Mbkm::class, 1)->create([
            'end_date' => '2030-01-01'
        ]);
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        
        $response = $this->get('/admin/mbkm-report');
        $response->assertSessionHas('status', 'error');
    }
    public function testUploadReportSuccess()
    {
        factory(Partner::class, 1)->create();
        factory(Mbkm::class, 1)->create([
            'end_date' => '2030-01-01'
        ]);
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'accepted',
        ]);

        Storage::fake('uploads');
        $file = UploadedFile::fake()->create('document.pdf', 100);
        
        $path = $file->store('uploads',  'uploads');

        Storage::disk('uploads')->assertExists($path);
        $requestData = [
            'file_info' => 'test',
            'file' => $file,
            'reg_mbkm_id' => 1,
            'upload_date' => Carbon::now()->toDateString(),
        ];

        $response = $this->post('/admin/mbkm-report-upload', $requestData);
        $response->assertSessionHas('status', 'success');
        $response->assertStatus(302);
    }

    public function testUploadReportErrorWhereFileNotPdf()
    {
        factory(Partner::class, 1)->create();
        factory(Mbkm::class, 1)->create([
            'end_date' => '2030-01-01'
        ]);
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'accepted',
        ]);

        Storage::fake('uploads');
        $file = UploadedFile::fake()->create('document.png', 100);
        
        $path = $file->store('uploads',  'uploads');

        Storage::disk('uploads')->assertExists($path);
        $requestData = [
            'file_info' => 'test',
            'file' => $file,
            'reg_mbkm_id' => 1,
            'upload_date' => Carbon::now()->toDateString(),
        ];

        $response = $this->post('/admin/mbkm-report-upload', $requestData);
        $response->assertSessionHas('status', 'error');
        $response->assertStatus(302);
    }

    public function testEditReportSuccess()
    {
        factory(Partner::class, 1)->create();
        factory(Mbkm::class, 1)->create([
            'end_date' => '2030-01-01'
        ]);
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'accepted',
        ]);
        factory(Report::class,1)->create();

        Storage::fake('uploads');
        $file = UploadedFile::fake()->create('document.pdf', 100);
        
        $path = $file->store('uploads',  'uploads');

        Storage::disk('uploads')->assertExists($path);
        $requestData = [
            'id' => 1,
            'file_info' => 'test',
            'file' => $file,
            'upload_date' => Carbon::now()->toDateString(),
        ];

        $response = $this->post('/admin/mbkm-report-rev', $requestData);
        $response->assertSessionHas('status', 'success');
        $response->assertStatus(302);
    }

    public function testEditReportError()
    {
        factory(Partner::class, 1)->create();
        factory(Mbkm::class, 1)->create([
            'end_date' => '2030-01-01'
        ]);
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'accepted',
        ]);

        Storage::fake('uploads');
        $file = UploadedFile::fake()->create('document.png', 100);
        
        $path = $file->store('uploads',  'uploads');

        Storage::disk('uploads')->assertExists($path);
        $requestData = [
            'id' => 1,
            'file_info' => 'test',
            'file' => $file,
            'upload_date' => Carbon::now()->toDateString(),
        ];

        $response = $this->post('/admin/mbkm-report-rev', $requestData);
        $response->assertSessionHas('status', 'error');
        $response->assertStatus(302);
    }
}
