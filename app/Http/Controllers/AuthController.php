<?php

// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // --- ADMIN ---
    public function formLoginAdmin() { return view('auth.login-admin'); }

    public function loginAdmin(Request $request) {
        if (Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password])) {
            return redirect()->intended('/admin/dashboard');
        }
        return back()->with('error', 'Login Gagal!');
    }

    // --- PELANGGAN ---
    public function formLoginUser() { return view('auth.login-user'); }
    
    public function loginUser(Request $request) {
        // Cek login berdasarkan Nama (sesuai DB) atau No HP jika mau diubah
        $user = Pelanggan::where('nama', $request->nama)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::guard('web')->login($user);
            return redirect('/dashboard');
        }
        return back()->with('error', 'Username atau Password salah');
    }

    public function registerUser(Request $request) {
        Pelanggan::create([
            'nama' => $request->nama,
            'password' => Hash::make($request->password), // Hashing password
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat
        ]);
        return redirect('/login')->with('success', 'Berhasil daftar, silakan login');
    }
    
    public function logout() {
        if(Auth::guard('admin')->check()){
            Auth::guard('admin')->logout();
            return redirect('/admin/login');
        }
        Auth::guard('web')->logout();
        return redirect('/login');
    }
}
