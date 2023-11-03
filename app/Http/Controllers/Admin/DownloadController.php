<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DownloadController extends Controller
{
    //

    public function download($name){
        return Response::download($name);
    }
}
