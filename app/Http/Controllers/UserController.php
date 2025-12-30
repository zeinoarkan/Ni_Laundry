<?php

namespace App\Http\Controllers;

use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index() {
        return view('user.dashboard'); 
    }

    public function layanan() {
        $layanan = Layanan::all(); 
        return view('user.layanan', compact('layanan'));
    }

    public function storePesanan(Request $request) {
        $layanan = Layanan::find($request->id_layanan);
        $user = Auth::user();
        
        $berat_asli = $request->berat ?? 1; 
        $berat_tagihan = $berat_asli;       
        $status_promo = false;              

        // Logika Promo (> 8kg diskon 1kg)
        if ($berat_asli > 8) {
            $berat_tagihan = $berat_asli - 1;
            $status_promo = true;
        }

        $total_bayar = $berat_tagihan * $layanan->harga;
        
        // Tentukan status awal
        $status_awal = ($total_bayar <= 0) ? 'Diproses' : 'Pending';

        // 1. SIMPAN KE DATABASE DULU
        $pesanan = Pesanan::create([
            'id_pelanggan' => $user->id_pelanggan,
            'id_layanan' => $request->id_layanan,
            'berat' => $berat_asli,      
            'total_harga' => $total_bayar, 
            'status_pesanan' => $status_awal,
            'tanggal_pesan' => now(), // Gunakan helper now() lebih simpel
            'metode' => $request->metode,
            'jumlah_bayar' => 0
        ]);

        // Jika Gratis (Rp 0), langsung redirect tanpa ke Midtrans
        if ($total_bayar <= 0) {
            return redirect('/riwayat')->with('success', 'Pesanan GRATIS (Promo > 8Kg).');
        }

        // 2. KONFIGURASI MIDTRANS
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // === [PERBAIKAN UTAMA ADA DI SINI] ===
        // Kita buat Order ID Unik dengan format: ORD-{ID_PESANAN}-{KODE_ACAK}
        // Contoh: ORD-15-654a3b12
        $custom_order_id = 'ORD-' . $pesanan->id_pesanan . '-' . uniqid();

        $params = array(
            'transaction_details' => array(
                'order_id' => $custom_order_id, // Gunakan ID unik ini
                'gross_amount' => (int) $total_bayar, // Pastikan integer
            ),
            'customer_details' => array(
                'first_name' => $user->nama,
                'phone' => $user->no_hp,
            ),
            'callbacks' => array(
                'finish' => url('/riwayat'),
            )
        );

        try {
            // Request Snap Token ke Midtrans
            $snapToken = Snap::getSnapToken($params);
            
            // Simpan Token ke Database
            $pesanan->snap_token = $snapToken;
            $pesanan->save();
            
            $pesan_sukses = $status_promo 
                ? 'Selamat! Anda dapat potongan 1 Kg karena mencuci lebih dari 8 Kg.' 
                : 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.';

            return redirect('/riwayat')->with('success', $pesan_sukses);

        } catch (\Exception $e) {
            // Jika gagal request ke Midtrans, hapus pesanan agar tidak nyampah di DB
            $pesanan->delete(); 
            return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }

    public function riwayat() {
        $pesanan = $pesanan = Pesanan::with('layanan')
           ->where('id_pelanggan', Auth::id())
           ->orderBy('id_pesanan', 'desc')
           ->get();
        return view('user.riwayat', compact('pesanan'));
    }

    public function paymentSuccess($id) {
        $pesanan = Pesanan::find($id);
        
        if($pesanan) {
            $pesanan->status_pesanan = 'Diproses'; 
            $pesanan->jumlah_bayar = $pesanan->total_harga; 
            $pesanan->save();
        }

        return redirect('/riwayat')->with('success', 'Pembayaran Berhasil! Pesanan sedang diproses.');
    }
}