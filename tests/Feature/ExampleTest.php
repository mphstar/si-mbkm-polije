<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;
// use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $controller = new MbkmCrudController();

        $fakeRequest = new Request();
        $fakeRequest->initialize([], ['file' => new \Illuminate\Http\UploadedFile('/path/to/your/test/file.zip', 'file.zip')]); // Ganti dengan path file yang ingin Anda gunakan untuk pengujian

        $response = $controller->addreg($fakeRequest);

        // Assertion untuk memastikan bahwa berhasil mendaftar
        $this->assertEquals('http://example.com/admin/mbkm', $response->getTargetUrl()); // Ganti dengan URL yang sesuai setelah berhasil mendaftar

    }
}
