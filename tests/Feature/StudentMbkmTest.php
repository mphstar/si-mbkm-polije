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
            'name' => 'student',
            'email' => 'student@gmail.com',
            'level' => 'student',
            'email_verified_at' => now(),
            'password' => Hash::make('123'),
        ]);
        factory(Partner::class, 1)->create();
        factory(Mbkm::class, 1)->create([
            'end_date' => '2040-01-01'
        ]);
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();
        $response = $this->post('/admin/login', [
            'email' => 'student@gmail.com', 
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
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'pending',
        ]);
        $response = $this->get('/admin/mbkm/1/reg-mbkm');
        $response->assertSessionHas('test', 'pending');
        $response->assertStatus(302);
    }

    public function testViewFormRegisterWhereStatusAccepted()
    {
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'accepted',
        ]);
        $response = $this->get('/admin/mbkm/1/reg-mbkm');
        $response->assertSessionHas('test', 'sudah mendaftar');
        $response->assertStatus(302);
    }

    public function testViewFormRegisterWhereStatusReject()
    {
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'rejected',
        ]);
        $response = $this->get('/admin/mbkm/1/reg-mbkm');
        $response->assertStatus(200);
    }
    
    public function testRegisterError()
    {
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

    public function testViewReportErrorWhereNothingData()
    {
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'rejected',
        ]);
        $response = $this->get('/admin/mbkm-report');
        $response->assertSessionHas('status', 'error');
    }

    public function testViewReport()
    {
        factory(RegisterMbkm::class, 1)->create([
            'status' => 'accepted',
        ]);
        $response = $this->get('/admin/mbkm-report');
        $response->assertSessionHas('status', 'success');
    }

    public function testUploadReportErrorWhereFileNotPdf()
    {
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

    public function testUploadReportSuccess()
    {
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

    public function testEditReportError()
    {
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

    public function testEditReportSuccess()
    {
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
            'upload_date' => '2023-02-02',
        ];

        $response = $this->post('/admin/mbkm-report-rev', $requestData);
        $response->assertSessionHas('test', 'success');
        $response->assertStatus(302);
    }
}
