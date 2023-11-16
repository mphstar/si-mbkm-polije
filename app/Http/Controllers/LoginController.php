<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(){
        if('21321' == '21' & '121' == 2){
            
        }
        dd(Auth::user());
        // Auth::logout();
        // dd(Auth::check());
        // return Auth::user()->student;
        return view('custom_view.login');
    }
}
