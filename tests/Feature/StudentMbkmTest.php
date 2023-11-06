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
use App\Http\Controllers\Admin\MbkmCrudController;


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
            // 'remember_token' => Str::random(10),
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
    
    public function testRegisterSuccess()
    {
        factory(Partner::class, 1)->create();
        factory(Mbkm::class, 1)->create();
        factory(Departmen::class, 1)->create();
        factory(ProgramStudy::class, 1)->create();
        factory(Students::class, 1)->create();

        // Storage::fake('uploads');
        // $file = UploadedFile::fake()->create('document.png', 100);
        
        // $path = $file->store('uploads',  'uploads');

        // Storage::disk('uploads')->assertExists($path);
        $requestData = [
            'student_id' => '1',
            'mbkm_id' => '1',
            'status' => 'pending',
            'file' => 'pict.png',
        ];
        $response = $this->post('/admin/mbkm/1/addreg', $requestData);
        $response->assertSessionHasErrors(['haha']);
        // $response->assertStatus(302);

        // Storage::disk('local')->assertExists('uploads/' . $file->hashName());
        // Storage::disk('uploads')->assertExists("storage/uploads/{$file->hashName()}");
    }
}
