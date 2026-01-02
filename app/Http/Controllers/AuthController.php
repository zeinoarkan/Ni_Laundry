<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // ADMIN
    public function formLoginAdmin() { return view('auth.login-admin'); }

    public function loginAdmin(Request $request) {
        if (Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password])) {
            return redirect()->intended('/admin/dashboard');
        }
        return back()->with('error', 'Login Gagal!');
    }

    // PELANGGAN
    public function formLoginUser() { return view('auth.login-user'); }
    
    public function loginUser(Request $request) {
        $user = Pelanggan::where('nama', $request->nama)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::guard('web')->login($user);
            
            return redirect('/'); 
        }
        return back()->with('error', 'Username atau Password salah');
    }

    public function registerUser(Request $request) {
        Pelanggan::create([
            'nama' => $request->nama,
            'password' => Hash::make($request->password), 
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
        
        return redirect('/'); 
    }

    public function formForgotPassword() {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request) {
        $request->validate([
            'no_hp' => 'required',
        ]);

        $user = Pelanggan::where('no_hp', $request->no_hp)->first();

        if (!$user) {
            return back()->with('error', 'Nomor HP tidak terdaftar dalam sistem kami.');
        }

        $otp = rand(100000, 999999);

        Session::put('reset_otp', $otp);
        Session::put('reset_user_id', $user->id_pelanggan); // Sesuaikan primary key tabel Anda
        Session::put('reset_expires', now()->addMinutes(5));

        $message = "*RESET PASSWORD NI LAUNDRY*\n\n";
        $message .= "Halo {$user->nama},\n";
        $message .= "Kode OTP Anda adalah: *{$otp}*\n\n";
        $message .= "Kode ini berlaku selama 5 menit. Jangan berikan kepada siapapun.";

        $this->kirimPesanFonnte($user->no_hp, $message);

        return redirect('/verify-otp')->with('success', 'Kode OTP telah dikirim ke WhatsApp Anda.');
    }

    public function formVerifyOtp() {
        if (!Session::has('reset_otp')) {
            return redirect('/forgot-password')->with('error', 'Sesi habis, silakan ulangi permintaan.');
        }
        return view('auth.verify-otp');
    }

    public function processResetPassword(Request $request) {
        $request->validate([
            'otp' => 'required|numeric',
            'password' => 'required|min:5'
        ]);

        $sessionOtp = Session::get('reset_otp');
        $sessionExpires = Session::get('reset_expires');
        $userId = Session::get('reset_user_id');

        if (!$sessionOtp || now()->greaterThan($sessionExpires)) {
            return redirect('/forgot-password')->with('error', 'Kode OTP kadaluarsa. Silakan minta ulang.');
        }

        if ($request->otp != $sessionOtp) {
            return back()->with('error', 'Kode OTP salah!');
        }

        $user = Pelanggan::find($userId);
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            
            Session::forget(['reset_otp', 'reset_user_id', 'reset_expires']);

            return redirect('/login')->with('success', 'Password berhasil diubah! Silakan login.');
        }

        return back()->with('error', 'Terjadi kesalahan sistem.');
    }

    private function kirimPesanFonnte($target, $pesan) {
        $token = 'Z4RJR27QU6JaxbXVAt2a'; 

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.fonnte.com/send',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array(
            'target' => $target,
            'message' => $pesan,
            'countryCode' => '62',
          ),
          CURLOPT_HTTPHEADER => array(
            "Authorization: $token"
          ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        
        return $response;
    }
}