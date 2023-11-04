
<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\DosenMiddleware;
use App\Http\Middleware\KaprodiMiddleware;
use App\Http\Middleware\PartnerMiddleware;
use App\Http\Middleware\StudentMiddleware;
use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('tags', 'TagsCrudController');
    Route::crud('mbkm', 'MbkmCrudController');

    Route::middleware([PartnerMiddleware::class])->group(function () {
        Route::crud('partner', 'PartnerCrudController');
        Route::crud('management-m-b-k-m', 'ManagementMBKMCrudController');
        Route::crud('register-mbkm', 'RegisterMbkmCrudController');
        Route::crud('validasilaporan', 'ValidasilaporanCrudController');
        Route::crud('penilaian-mitra', 'PenilaianMitraCrudController');
        Route::get('penilaian-mitra/{id}/updating', 'PenilaianMitraCrudController@updating')->name("grader_partner");
        Route::post('penilaian-mitra/{id}/penilaian ', 'PenilaianMitraCrudController@penilaian');
    });
    
    Route::middleware([KaprodiMiddleware::class])->group(function () {
        Route::crud('acctive-account-mitra', 'AcctiveAccountMitraCrudController');
        Route::crud('validasi-mbkm', 'ValidasiMbkmCrudController');
        Route::crud('departmen', 'DepartmenCrudController');
        Route::crud('lecturer', 'LecturerCrudController');
        Route::crud('manage-student', 'ManageStudentCrudController');
        Route::get('manage-student/{id}/edit', 'ManageStudentCrudController@formEdit');
        Route::post('manage-student/{id}/editDosen', 'ManageStudentCrudController@editDosen');
        Route::post('manage-student/{id}/editMatkul', 'ManageStudentCrudController@editMatkul');
    });
    
    Route::middleware([DosenMiddleware::class])->group(function () {
        Route::crud('nilaimbkm', 'NilaimbkmCrudController');
        Route::crud('nilaimbkm', 'NilaimbkmCrudController');
        Route::get('/nilaimbkm/{id}/inputnilai', 'NilaimbkmCrudController@inputNilai');
        Route::post('/nilaimbkm/{id}/prosesNilai', 'NilaimbkmCrudController@prosesNilai');
        Route::crud('progress-mahasiswa', 'ProgressMahasiswaCrudController');
        
    });
    Route::middleware([StudentMiddleware::class])->group(function () {
        Route::get('mbkm/{id}/reg-mbkm', 'MbkmCrudController@register');
        Route::post('mbkm/{id}/addreg', 'MbkmCrudController@addreg');
        Route::get('mbkm-report', 'MbkmReportCrudController@viewReport');
        Route::post('mbkm-report-upload', 'MbkmReportCrudController@upReport');
        Route::post('mbkm-report-rev', 'MbkmReportCrudController@revReport');
        Route::get('validasilaporan/{id}/detail_laporan ', 'ValidasilaporanCrudController@detail_laporan')->name("detail_laporan");
        Route::crud('status-reg', 'StatusRegCrudController');
        Route::post('validasilaporan/{id}/approve-laporan', 'ValidasilaporanCrudController@validasilaporan');
    });
    
    Route::get('/download/{name}', 'DownloadController@download');
}); // this should be the absolute last line of this file