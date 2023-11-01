<?php

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
    Route::crud('partner', 'PartnerCrudController');

    Route::crud('acctive-account-mitra', 'AcctiveAccountMitraCrudController');

 
    Route::crud('validasi-mbkm', 'ValidasiMbkmCrudController');

    Route::crud('departmen', 'DepartmenCrudController');
    Route::crud('register-mbkm', 'RegisterMbkmCrudController');
    Route::crud('lecturer', 'LecturerCrudController');
    Route::get('mbkm/{id}/reg-mbkm', 'MbkmCrudController@register');
    Route::post('mbkm/{id}/addreg', 'MbkmCrudController@addreg');
    Route::get('mbkm-report', 'MbkmReportCrudController@viewReport');
    Route::crud('management-m-b-k-m', 'ManagementMBKMCrudController');
    Route::crud('penilaian-mitra', 'PenilaianMitraCrudController');
    Route::post('mbkm-report-upload', 'MbkmReportCrudController@upReport');
    Route::post('mbkm-report-rev', 'MbkmReportCrudController@revReport');
}); // this should be the absolute last line of this file