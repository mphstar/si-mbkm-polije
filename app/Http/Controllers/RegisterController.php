<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\ProgramStudy;
use App\Student;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class RegisterController extends Controller
{
    public function student(Request $request)
    {
        // dd('dwa');
        $token = new ClassApi();
        // $user = Socialite::driver('google')->user();

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
                $result = $http->get(url("http://api.polije.ac.id/resources/global/unit"), [
                    'headers' => [
                        'Accept' => 'application/json',
                        'User-Agent' => '/Postman/i',
                        'Authorization' => 'Bearer ' . $data
                    ],
                    //Parameter API
                    'query' => [
                        'debug' => true,
                        'jenis' => 'JURUSAN'
                    ]
                ]);

                $result1 = $http->get(url("http://api.polije.ac.id/resources/global/unit"), [
                    'headers' => [
                        'Accept' => 'application/json',
                        'User-Agent' => '/Postman/i',
                        'Authorization' => 'Bearer ' . $data
                    ],
                    //Parameter API
                    'query' => [
                        'debug' => true,
                        'jenis' => 'PROGRAM STUDI'
                    ]
                ]);

                $respone = json_decode($result->getBody());
                $respone1 = json_decode($result1->getBody());

                if ($respone) {
                    
                    return view('custom_view.register_student', [
                        "jurusan" => $respone,
                        "prodi" => $respone1,
                    ]);
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
        
    }

    public function registerStudent(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'nim' => 'required',
            'jurusan' => 'required',
            'program_studi' => 'required',
            'address' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'level' => 'student'
        ]);

        if ($user) {

            Student::create([
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'nim' => $request->nim,
                'program_studi' => $request->program_studi,
                'jurusan' => $request->jurusan,
                'users_id' => $user->id
            ]);

            // $this->guard()->login($user);
            backpack_auth()->login($user);

            return redirect('/admin');
        }
    }
    public function mitra()
    {
        return view('custom_view.register_mitra');
    }

    public function registerMitra(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'jenis' => 'required',
            'address' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'level' => 'mitra'
        ]);

        if ($user) {

            Partner::create([
                'partner_name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'users_id' => $user->id,
                'status' => 'pending',
                'jenis_mitra' => $request->jenis
            ]);

            // $this->guard()->login($user);
            backpack_auth()->login($user);

            return redirect('/admin');
        }
    }
}
