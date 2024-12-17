<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;


class SesiController extends BaseController
{
    public function index()
    {
        $title = 'Login';
        return view('login', compact('title'));
    }

    public function welcome()
    {
        $title = "Welcome";
        return view('welcome', compact('title'));
    }

    public function regisPage()
    {
        $title = "Register";
        return view('register', compact('title'));
    }

    public function regisCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:users,name',
            'password' => 'required|confirmed',
            'email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._%+-]+@pnc\.ac\.id$/',
                'unique:users,email',
            ],
        ], [
            'name.unique' => 'Nama sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'email.regex' => 'Email tidak sesuai dengan format yang diperbolehkan.',
            'email.unique' => 'Email sudah terdaftar.',
        ]);


        // Cek apakah tabel users kosong
        $role = User::count() === 0 ? 'admin' : 'dosen';

        // Buat pengguna baru dengan peran yang sesuai
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $role,
        ]);

        event(new Registered($user));

        return redirect()->route('loginPage')->with(['success' => 'Berhasil mendaftar. Silakan verifikasi email Anda.']);
    }


    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required',
                'password' => 'required'
            ],
            [
                'email.required' => 'email wajib diisi',
                'password.required' => 'password wajib diisi'
            ]
        );

        $infoLogin = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($infoLogin)) {
            return redirect('/home');
            exit();
        } else {
            return redirect('/login')->withErrors('email atau password yang dimasukkan salah')->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('');
    }

    public function verifyEmail($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('loginPage')->with(['error' => 'Token verifikasi tidak valid.']);
        }

        $user->update([
            'email_verified_at' => now(),
            'verification_token' => null,
        ]);

        return redirect()->route('loginPage')->with(['success' => 'Email berhasil diverifikasi. Silakan login.']);
    }
}
