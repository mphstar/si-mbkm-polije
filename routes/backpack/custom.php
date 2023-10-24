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
<<<<<<< HEAD
 
    Route::crud('tag', 'TagCrudController');

    Route::crud('lecture', 'LectureCrudController');
=======
    Route::crud('tags', 'TagsCrudController');
>>>>>>> e918c433e036c036a83b7c6c3317e8bb2ef03b10
    Route::crud('partner', 'PartnerCrudController');
 
    Route::crud('validasi-mbkm', 'ValidasiMbkmCrudController');
}); // this should be the absolute last line of this file