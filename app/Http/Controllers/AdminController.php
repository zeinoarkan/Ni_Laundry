<?php

// app/Http/Controllers/AdminController.php
namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Layanan;
use App\Models\Pelanggan;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard() {
        $data = [
            'total_pesanan' => Pesanan::count(),
            'total_pelanggan' => Pelanggan::count(),
            'pendapatan' => Pesanan::where('status_pesanan', 'Selesai')->sum('total_harga'),
            'pesanan_terbaru' => Pesanan::with(['pelanggan', 'layanan'])
                     ->orderBy('id_pesanan', 'desc') // Ganti latest() dengan ini
                     ->take(5)
                     ->get()
        ];
        return view('admin.dashboard', $data);
    }

    public function updateStatus(Request $request, $id) {
        $pesanan = Pesanan::with(['layanan', 'pelanggan'])->findOrFail($id);
        
        $status_lama = $pesanan->status_pesanan;
        $pesanan->status_pesanan = $request->status_pesanan;
        $pesanan->save();

        if ($request->status_pesanan == 'Selesai' && $status_lama != 'Selesai') {
            
            $pelanggan = $pesanan->pelanggan; 
            
            if ($pelanggan && $pesanan->layanan) {
                
                $harga_per_kg = $pesanan->layanan->harga;
                $berat_poin = 0;

                if ($harga_per_kg > 0) {
                    $berat_poin = $pesanan->total_harga / $harga_per_kg;
                }

                $pelanggan->progres_kg += $berat_poin;

                while ($pelanggan->progres_kg >= 8) {
                    $pelanggan->increment('bonus'); 
                    $pelanggan->progres_kg -= 8;    
                }
                
                $pelanggan->save();

                try {
                    $pesanWA = "Halo Kak *{$pelanggan->nama}*! \n\n";
                    $pesanWA .= "Kabar gembira, cucian Anda dengan ID Pesanan *#{$pesanan->id_pesanan}* sudah *SELESAI* dan siap diambil/diantar.\n\n";
                    $pesanWA .= "Total Berat: {$pesanan->berat} Kg\n";
                    $pesanWA .= "Total Tagihan: Rp " . number_format($pesanan->total_harga, 0, ',', '.') . "\n\n";
                    
                    $pesanWA .= "Progres Poin: {$pelanggan->progres_kg}/8 Kg\n";
                    
                    $pesanWA .= "Terima kasih telah mempercayakan pakaian Anda pada Ni Laundry!";

                    $this->sendWhatsapp($pelanggan->no_hp, $pesanWA);
                    
                } catch (\Exception $e) {
                }

            }
        }

        return back()->with('success', 'Status Selesai. Poin dihitung berdasarkan nominal bayar.');
    }

    // CRUD LAYANAN
    
    public function layananIndex() {
        $layanan = Layanan::all();
        return view('admin.layanan.index', compact('layanan'));
    }

    public function layananCreate() {
        return view('admin.layanan.create');
    }

    public function layananStore(Request $request) {
        Layanan::create([
            'nama_layanan' => $request->nama_layanan,
            'harga' => $request->harga,
            'jenis' => $request->jenis,
            'id_admin' => Auth::guard('admin')->id() // Ambil ID Admin yg login
        ]);
        return redirect('/admin/layanan')->with('success', 'Layanan berhasil ditambahkan');
    }

    public function layananEdit($id) {
        $layanan = Layanan::findOrFail($id);
        return view('admin.layanan.edit', compact('layanan'));
    }

    public function layananUpdate(Request $request, $id) {
        $layanan = Layanan::findOrFail($id);
        $layanan->update([
            'nama_layanan' => $request->nama_layanan,
            'harga' => $request->harga,
            'jenis' => $request->jenis
        ]);
        return redirect('/admin/layanan')->with('success', 'Layanan berhasil diupdate');
    }

    public function layananDestroy($id) {
        Layanan::findOrFail($id)->delete();
        return redirect('/admin/layanan')->with('success', 'Layanan dihapus');
    }

    // MANAJEMEN PESANAN

    public function pesananIndex(Request $request) {
        $query = Pesanan::with(['pelanggan', 'layanan']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            
            $query->where(function($q) use ($search) {
                
                $q->whereHas('pelanggan', function($subQ) use ($search) {
                    $subQ->where('nama', 'like', '%' . $search . '%');
                })
                ->orWhereHas('layanan', function($subQ) use ($search) {
                    $subQ->where('nama_layanan', 'like', '%' . $search . '%')
                         ->orWhere('jenis', 'like', '%' . $search . '%');
                })
                ->orWhere('status_pesanan', 'like', '%' . $search . '%')
                ->orWhere('id_pesanan', 'like', '%' . $search . '%')
                ->orWhere('tanggal_pesan', 'like', '%' . $search . '%');
                
                $bulanIndo = [
                    'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
                    'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
                    'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12
                ];

                foreach ($bulanIndo as $nama => $angka) {
                    if (stripos($nama, $search) !== false) {
                        $q->orWhereMonth('tanggal_pesan', $angka);
                    }
                }
            });
        }

        $pesanan = $query->orderBy('id_pesanan', 'desc')->get();

        return view('admin.pesanan.index', compact('pesanan'));
    }

    public function pesananEdit($id) {
       $pesanan = Pesanan::where('id_pesanan', $id)->firstOrFail();
        
        // dd($pesanan);
        return view('admin.pesanan.edit', compact('pesanan'));
    }

    public function pesananUpdate(Request $request, $id) {
        $pesanan = Pesanan::findOrFail($id);
        
        $pesanan->update([
            'berat' => $request->berat,
            'total_harga' => $request->total_harga, // Admin bisa manual set harga
            'status_pesanan' => $request->status_pesanan,
            'jumlah_bayar' => $request->jumlah_bayar ?? 0
        ]);

        return redirect('/admin/pesanan')->with('success', 'Data pesanan diperbarui');
    }

    public function pesananDestroy($id) {
    $pesanan = Pesanan::with(['layanan', 'pelanggan'])->findOrFail($id);

    if ($pesanan->status_pesanan == 'Selesai') {
        
        $pelanggan = $pesanan->pelanggan;
        
        if ($pelanggan && $pesanan->layanan) {
            
            $harga_layanan = $pesanan->layanan->harga;
            
            $berat_poin_dihapus = ($harga_layanan > 0) 
                ? floor($pesanan->total_harga / $harga_layanan) 
                : 0;

            $pelanggan->progres_kg -= $berat_poin_dihapus;

            while ($pelanggan->progres_kg < 0) {
                if ($pelanggan->bonus > 0) {
                    $pelanggan->decrement('bonus'); 
                    $pelanggan->progres_kg += 8;    
                } else {
                    $pelanggan->progres_kg = 0;     
                    break; 
                }
            }
            
            $pelanggan->save();
        }
    }

    $pesanan->delete();

    if (Pesanan::count() == 0) {
        DB::statement('ALTER TABLE pesanan AUTO_INCREMENT = 1');
    }    
    
    return back()->with('success', 'Pesanan berhasil dihapus.');
}

    // CRUD ADMIN (PENGGUNA)

    public function userAdminIndex() {
        $admins = Admin::all();
        return view('admin.users.index', compact('admins'));
    }

    public function userAdminCreate() {
        return view('admin.users.create');
    }

    public function userAdminStore(Request $request) {
        $request->validate([
            'username' => 'required|unique:admin,username',
            'password' => 'required|min:6'
        ]);

        Admin::create([
            'username' => $request->username,
            'password' => Hash::make($request->password) 
        ]);

        return redirect('/admin/users')->with('success', 'Admin baru berhasil ditambahkan');
    }

    public function userAdminEdit($id) {
        $admin = Admin::findOrFail($id);
        return view('admin.users.edit', compact('admin'));
    }

    public function userAdminUpdate(Request $request, $id) {
        $admin = Admin::findOrFail($id);

        $data = [
            'username' => $request->username
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect('/admin/users')->with('success', 'Data admin diperbarui');
    }

    public function userAdminDestroy($id) {
        if ($id == Auth::guard('admin')->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun yang sedang digunakan!');
        }

        Admin::findOrFail($id)->delete();
        return redirect('/admin/users')->with('success', 'Admin berhasil dihapus');
    }

    public function diskonIndex() {
        $pelanggan = Pelanggan::orderBy('bonus', 'desc')
                              ->orderBy('progres_kg', 'desc')
                              ->get();
                              
        return view('admin.diskon.index', compact('pelanggan'));
    }

    public function resetBonus($id) {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->bonus = 0; 
        $pelanggan->save();
        
        return back()->with('success', 'Bonus pelanggan berhasil di-reset manual.');
    }

    private function sendWhatsapp($nomor, $pesan) {
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
            'target' => $nomor,
            'message' => $pesan,
            'countryCode' => '62', 
          ),
          CURLOPT_HTTPHEADER => array(
            'Authorization: Z4RJR27QU6JaxbXVAt2a' 
          ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        
        return $response;
    }

    
}