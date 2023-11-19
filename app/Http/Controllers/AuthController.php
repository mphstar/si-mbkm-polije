<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Students;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        $token = new ClassApi();
        $user = Socialite::driver('google')->user();
        // dd($user);
        $parts = explode("@", $user->email);
        // dd($token->generateToken()->access_token);

        try {
            $http = new Client();

            //Check sesi token ada atau tidak
            if ($request->session()->has('access_token')) {
                $data = $request->session()->get('access_token');
            } else {
                //jika tidak ada, maka mengambil access token

                $data = $token->generateToken()->access_token;
                //simpan access token ke sesi
                $request->session()->put('access_token', $data);
            }

            // dd($data);
            //Jika token sudah didapatkan, masukkan URl API untuk mengambil data
            if ($data) {
                $result = $http->get(url("http://api.polije.ac.id/resources/akademik/mahasiswa"), [
                    'headers' => [
                        'Accept' => 'application/json',
                        'User-Agent' => '/Postman/i',
                        'Authorization' => 'Bearer ' . $data
                    ],
                    //Parameter API
                    'query' => [
                        'debug' => true,
                        'nim' => $parts[0]
                    ]
                ]);

                // dd(json_decode($result->getBody()));
                $respone = json_decode($result->getBody())[0];
                if($respone){
                    $data_user = User::where('email', $user->email)->first();
                    if($data_user){
                        Auth::login($data_user);
                        return redirect()->intended('https://google.com');
                    } else {

                        $newuser = User::create([
                            "name" => $respone->nama_lengkap,
                            "email" => $user->email,
                            "password" => bcrypt("12345678"),
                            "level" => "student"
                        ]);

                        $student = Students::create([
                            "name" => $respone->nama_lengkap,
                            "address" => $respone->alamat_domisili,
                            "phone" => $respone->no_telp,
                            "nim" => $respone->nim,
                            "program_studi" => $respone->program_studi,
                            "users_id" => $newuser->id
                        ]);
                        
                        Auth::login($newuser);
                        return redirect()->intended('https://google.com');
                    }
                }

                //Jika token tidak di autorisasi, tampilkan halaman error.
            } else {
                $data['title'] = '403';
                $data['name'] = 'No Authorization.';

                return response()
                    ->view('errors.403', 403);
            }
        } catch (\Exception $e) {
            dd($e);
            abort($e->getCode());
        }


        // Check if the user exists in your system based on their email or other unique identifier.
        // If not, create a new user account.
        // Log in the user using JWT or other authentication method.
    }
}
