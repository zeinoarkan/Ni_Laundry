<?php

namespace App\Http\Controllers;

use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index() {
        return view('user.dashboard'); 
    }

    public function layanan() {
        $layanan = Layanan::all(); 
        return view('user.layanan', compact('layanan'));
    }

    // --- INPUT PESANAN ---
    public function storePesanan(Request $request) {
        $user = Auth::user();
        
        Pesanan::create([
            'id_pelanggan' => $user->id_pelanggan,
            'id_layanan' => $request->id_layanan,
            'berat' => 0,      
            'total_harga' => 0, 
            'status_pesanan' => 'Pending', // Status awal
            'tanggal_pesan' => now(), 
            'metode' => $request->metode,
            'jumlah_bayar' => 0
        ]);

        return redirect('/riwayat')->with('success', 'Pesanan berhasil dibuat. Mohon tunggu konfirmasi admin/penjemputan.');
    }

    public function riwayat() {
        $pesanan = Pesanan::with('layanan')
            ->where('id_pelanggan', Auth::id())
            ->orderBy('id_pesanan', 'desc')
            ->get();
        return view('user.riwayat', compact('pesanan'));
    }

    // --- FITUR BARU: BATALKAN PESANAN ---
    public function cancelPesanan($id) {
        $pesanan = Pesanan::where('id_pelanggan', Auth::id())->findOrFail($id);

        // LOGIKA BATASAN CANCEL:
        // Hanya boleh cancel jika status masih 'Pending'.
        // Jika sudah 'Menunggu Pembayaran' (artinya sudah ditimbang) atau 'Diproses', tidak bisa cancel.
        if ($pesanan->status_pesanan !== 'Pending') {
            return back()->with('error', 'Pesanan tidak bisa dibatalkan karena sudah diproses/ditimbang oleh petugas.');
        }

        $pesanan->delete();

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    // --- GENERATE PEMBAYARAN (LOGIKA DIPERBARUI) ---
    public function bayar($id) {
        $pesanan = Pesanan::with(['pelanggan', 'layanan'])->findOrFail($id);

        // 1. Cek apakah Admin sudah input harga (sudah ditimbang)
        if ($pesanan->total_harga <= 0) {
             return response()->json(['error' => 'Pesanan sedang dihitung/ditimbang. Mohon tunggu admin.'], 400);
        }

        // 2. Cek apakah sudah lunas
        if ($pesanan->jumlah_bayar >= $pesanan->total_harga && $pesanan->total_harga > 0) {
            return response()->json(['error' => 'Pesanan ini sudah lunas.'], 400);
        }

        // 3. LOGIKA FLEKSIBEL:
        // Boleh bayar jika status: 'Menunggu Pembayaran', 'Diproses', atau 'Selesai' (selama belum lunas)
        // Kita reject hanya jika statusnya 'Pending' (karena harga belum ada) atau 'Dibatalkan'
        if ($pesanan->status_pesanan == 'Pending' || $pesanan->status_pesanan == 'Dibatalkan') {
             return response()->json(['error' => 'Status pesanan belum siap untuk pembayaran.'], 400);
        }

        // KONFIGURASI MIDTRANS
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Buat Order ID unik setiap klik bayar untuk menghindari "Order ID has been paid" dari Midtrans jika user gagal bayar sebelumnya
        // Format: ORD-IDPESANAN-TIMESTAMP
        $custom_order_id = 'ORD-' . $pesanan->id_pesanan . '-' . time();

        $params = array(
            'transaction_details' => array(
                'order_id' => $custom_order_id,
                'gross_amount' => (int) $pesanan->total_harga,
            ),
            'customer_details' => array(
                'first_name' => $pesanan->pelanggan->nama,
                'phone' => $pesanan->pelanggan->no_hp,
            ),
        );

        try {
            $snapToken = Snap::getSnapToken($params);
            
            // Simpan token (opsional, untuk log)
            $pesanan->snap_token = $snapToken;
            $pesanan->save();
            
            return response()->json([
                'snapToken' => $snapToken,
                'order_id'  => $pesanan->id_pesanan
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // --- CALLBACK SUKSES (LOGIKA DIPERBARUI) ---
    public function paymentSuccess($id) {
        $pesanan = Pesanan::find($id);
        
        if($pesanan) {
            // Tandai sudah bayar
            $pesanan->jumlah_bayar = $pesanan->total_harga; 

            // LOGIKA STATUS SETELAH BAYAR:
            // 1. Jika statusnya 'Menunggu Pembayaran', ubah jadi 'Diproses'.
            // 2. Jika statusnya sudah 'Diproses' atau 'Selesai', JANGAN diubah (biarkan tetap berjalan).
            if ($pesanan->status_pesanan == 'Menunggu Pembayaran') {
                $pesanan->status_pesanan = 'Diproses';
            }
            
            $pesanan->save();
        }

        return redirect('/riwayat')->with('success', 'Pembayaran Berhasil! Terima kasih.');
    }
}