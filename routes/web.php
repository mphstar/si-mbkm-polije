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

// Route::get('/', function () {
//     return view('welcome');
// });s

Route::get('/', function () {
    dd(backpack_auth()->user());
});

Route::get('/register/student', 'RegisterController@student');

Route::post('/register/student/proses', 'RegisterController@registerStudent');

Route::get('/register/mitra', 'RegisterController@mitra');

Route::post('/register/mitra/proses', 'RegisterController@registerMitra');