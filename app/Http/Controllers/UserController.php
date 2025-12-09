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
        // 1. Ambil Data
        $layanan = Layanan::find($request->id_layanan);
        $user = Auth::user();
        
        $berat_asli = $request->berat ?? 1; // Berat yang diinput user
        $berat_tagihan = $berat_asli;       // Berat yang harus dibayar
        $status_promo = false;              // Penanda dapat diskon atau tidak

        // 2. LOGIKA PROMO LANGSUNG (> 8KG GRATIS 1KG)
        // Jika pesanan ini beratnya LEBIH DARI 8 Kg
        if ($berat_asli > 8) {
            $berat_tagihan = $berat_asli - 1; // Kurangi bayaran 1 Kg
            $status_promo = true;
        }

        // Hitung Total Bayar
        $total_bayar = $berat_tagihan * $layanan->harga;

        // 3. Simpan Pesanan
        $status_awal = ($total_bayar <= 0) ? 'Diproses' : 'Pending';

        $pesanan = Pesanan::create([
            'id_pelanggan' => $user->id_pelanggan,
            'id_layanan' => $request->id_layanan,
            'berat' => $berat_asli,      // Simpan berat asli (misal: 9 Kg)
            'total_harga' => $total_bayar, // Simpan harga diskon (harga 8 Kg)
            'status_pesanan' => $status_awal,
            'tanggal_pesan' => Carbon::now(),
            'metode' => $request->metode,
            'jumlah_bayar' => 0
        ]);

        // 4. Jika Total Rp 0 (Gratis total), Langsung sukses
        if ($total_bayar <= 0) {
            return redirect('/riwayat')->with('success', 'Pesanan GRATIS (Promo > 8Kg).');
        }

        // 5. Config Midtrans & Snap Token
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $params = array(
            'transaction_details' => array(
                'order_id' => $pesanan->id_pesanan,
                'gross_amount' => $total_bayar, // Tagih harga diskon
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
            $snapToken = Snap::getSnapToken($params);
            $pesanan->snap_token = $snapToken;
            $pesanan->save();
            
            // Pesan notifikasi berbeda jika dapat diskon
            $pesan_sukses = $status_promo 
                ? 'Selamat! Anda dapat potongan 1 Kg karena mencuci lebih dari 8 Kg.' 
                : 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.';

            return redirect('/riwayat')->with('success', $pesan_sukses);

        } catch (\Exception $e) {
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
        
        // Cek apakah pesanan ada
        if($pesanan) {
            $pesanan->status_pesanan = 'Diproses'; // Ubah status
            $pesanan->jumlah_bayar = $pesanan->total_harga; // Anggap lunas
            $pesanan->save();
        }

        return redirect('/riwayat')->with('success', 'Pembayaran Berhasil! Pesanan sedang diproses.');
    }
}