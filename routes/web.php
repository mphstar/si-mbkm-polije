<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

// Route::redirect('/', '/admin');

Route::get('/api', 'ApiController@index');

Route::get('/register/student', 'RegisterController@student');

Route::post('/register/student/proses', 'RegisterController@registerStudent');

Route::get('/register/mitra', 'RegisterController@mitra');

Route::post('/register/mitra/proses', 'RegisterController@registerMitra');

Route::redirect('/home', '/admin/dashboard');

// Route::view('/cetak_nilai', 'custom_view.cetak_nilai');

Route::get('/cetak_nilai', function () {
    return view('custom_view.cetak_nilai', [
        "data" => "mphstar"
    ]);
});

// Route::get('/auth', 'AuthController@redirectToGoogle')->name('google.login');
// Route::get('/auth/callback', 'AuthController@handleGoogleCallback');

// Route::get('/login', 'LoginController@login');