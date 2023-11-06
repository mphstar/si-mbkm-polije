<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\ProgramStudy;
use App\Student;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function student()
    {
        $study = ProgramStudy::all();
        return view('custom_view.register_student', compact('study'));
    }

    public function registerStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'nim' => 'required',
            'study' => 'required',
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
                'study_program_id' => $request->study,
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
