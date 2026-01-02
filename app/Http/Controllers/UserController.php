<?php

namespace App\Http\Controllers;

use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\User;
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
        $request->validate([
            'id_layanan' => 'required',
            'berat' => 'required|numeric|min:1',
            'metode' => 'required',
        ]);

        $layanan = Layanan::find($request->id_layanan);
        
        $user = User::find(Auth::id()); 

        $berat_input = $request->berat;
        $poin_lama   = $user->progres_kg ?? 0;
        
        $total_akumulasi = $poin_lama + $berat_input;

        $jumlah_gratis = floor($total_akumulasi / 9);

        $potongan_saat_ini = 0;
        if($jumlah_gratis > 0) {
            $potongan_saat_ini = min($jumlah_gratis, $berat_input);
        }

        $sisa_poin_baru = $total_akumulasi % 9;

        $berat_tagihan = $berat_input - $potongan_saat_ini;
        $total_bayar   = $berat_tagihan * $layanan->harga;
        
        $status_awal = ($total_bayar <= 0) ? 'Diproses' : 'Pending';

        $pesanan = Pesanan::create([
            'id_pelanggan' => $user->id_pelanggan,
            'id_layanan'   => $request->id_layanan,
            'berat'        => $berat_input,      
            'total_harga'  => $total_bayar, 
            'status_pesanan' => $status_awal,
            'tanggal_pesan'  => now(),
            'metode'       => $request->metode,
            'jumlah_bayar' => 0
        ]);

        if ($total_bayar <= 0) {
            $user->progres_kg = $sisa_poin_baru;
            $user->save();
            
            return redirect()->route('riwayat')->with('success', "Pesanan GRATIS (Tukar Poin). Sisa progres kg Anda: {$sisa_poin_baru}");
        }

        if (!config('midtrans.server_key')) return back()->with('error', 'Server Key Error');
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $params = array(
            'transaction_details' => array(
                'order_id' => 'ORD-' . $pesanan->id_pesanan . '-' . time(),
                'gross_amount' => (int) $total_bayar,
            ),
            'customer_details' => array(
                'first_name' => $user->nama,
                'phone' => $user->no_hp,
            ),
        );

        try {
            $snapToken = Snap::getSnapToken($params);
            $pesanan->snap_token = $snapToken;
            $pesanan->save();
            
            if ($potongan_saat_ini > 0) {
                $pesan = "Anda punya simpanan {$poin_lama} Kg. Ditambah order ini, Anda dapat GRATIS {$potongan_saat_ini} Kg!";
            } else {
                $pesan = "Order dibuat. Bayar sekarang agar berat {$berat_input}kg ini ditambahkan ke poin progres Anda.";
            }

            return redirect()->route('riwayat')->with('success', $pesan);

        } catch (\Exception $e) {
            $pesanan->delete(); 
            return back()->with('error', $e->getMessage());
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
        
        if($pesanan && $pesanan->status_pesanan != 'Diproses') {
            $pesanan->status_pesanan = 'Diproses'; 
            $pesanan->jumlah_bayar = $pesanan->total_harga; 
            $pesanan->save();

            $user = User::find($pesanan->id_pelanggan);
            if($user) {
                $poin_lama = $user->progres_kg ?? 0;
                $total_baru = $poin_lama + $pesanan->berat;
                $user->progres_kg = $total_baru % 9;
                $user->save();
            }
        }
        return redirect()->route('riwayat')->with('success', 'Pembayaran Berhasil! Poin laundry Anda diperbarui.');
    }
    }
    
