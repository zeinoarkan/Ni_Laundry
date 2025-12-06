<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index() {
    $layanan = Layanan::all(); 
    return view('user.dashboard', compact('layanan'));
    }

    public function storePesanan(Request $request) {
        $layanan = Layanan::find($request->id_layanan);
        
        $berat = $request->berat ?? 0; 
        $total = $berat * $layanan->harga;

        Pesanan::create([
            'id_pelanggan' => Auth::id(), 
            'id_layanan' => $request->id_layanan,
            'berat' => $berat,
            'total_harga' => $total,
            'status_pesanan' => 'Pending',
            'tanggal_pesan' => Carbon::now(),
            'metode' => 'Antar Jemput', 
            'jumlah_bayar' => 0
        ]);

        return redirect('/riwayat')->with('success', 'Pesanan berhasil dibuat! Menunggu konfirmasi Admin.');
    }

    public function riwayat() {
        $pesanan = $pesanan = Pesanan::with('layanan')
           ->where('id_pelanggan', Auth::id())
           ->orderBy('id_pesanan', 'desc')
           ->get();
        return view('user.riwayat', compact('pesanan'));
    }
}