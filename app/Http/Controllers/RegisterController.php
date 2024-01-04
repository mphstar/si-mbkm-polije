<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Students;
use App\ProgramStudy;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Prologue\Alerts\Facades\Alert;

class RegisterController extends Controller
{
    public function student(Request $request)
    {
        $api = new ClassApi();
        // $user = Socialite::driver('google')->user();

        return view('custom_view.register_student', [
            "jurusan" => $api->getJurusan($request),
            "prodi" => $api->getProdi($request),
        ]);
    }

    public function registerStudent(Request $request)
    {
        if (is_numeric($request->semester)) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required',
                'nim' => 'required|unique:students,nim',
                'semester' => 'required',
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

            try {
                if ($user) {

                    Students::create([
                        'name' => $request->name,
                        'address' => $request->address,
                        'phone' => $request->phone,
                        'nim' => $request->nim,
                        'program_studi' => $request->program_studi,
                        'jurusan' => $request->jurusan,
                        'semester' => $request->semester,
                        'users_id' => $user->id
                    ]);

                    // $this->guard()->login($user);
                    backpack_auth()->login($user);

                    return redirect('/admin');
                }
            } catch (\Throwable $th) {
                Alert::error('Maaf terjadi kesalahan saat menambahkan data')->flash();
                return back();
            }
        } else {
            // Handle invalid input
            Alert::error('Maaf terjadi kesalahan saat menambahkan data')->flash();
            return back();
        }
        // dd($request->all());


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
