<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function formLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        if (!$username && !$password) {
            return back()->with('error', 'harap isi form terlebih dahulu');
        }

        if (!$username || !$password) {
            return back()->with('error', 'harap isi semua form');
        }

        if ($username === env('ADMIN_USERNAME')) {

            if (!Hash::check($password, env('ADMIN_PASSWORD_HASH'))) {
                return back()->with('error', 'password salah! isi form dengan benar')->withInput();
            }

            session([
                'login' => true,
                'role' => 'admin',
                'username' => $username
            ]);

            return redirect('/dashboardAdmin');
        }
        if ($username === env('PEMILIK_USERNAME')) {

            if (!Hash::check($password, env('PEMILIK_PASSWORD_HASH'))) {
                return back()->with('error', 'password salah! isi form dengan benar')->withInput();
            }

            session([
                'login' => true,
                'role' => 'pemilik',
                'username' => $username
            ]);

            return redirect('/dashboardPU');
        }

        return back()->with('error', 'isi form dengan benar')->withInput();
    }

    public function logout()
    {
        session()->flush();
        return redirect('/login')->with('success', 'Berhasil logout');
    }
}