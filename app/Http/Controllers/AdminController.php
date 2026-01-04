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
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanKeuanganNiLaundry;

class AdminController extends Controller
{
    public function dashboard(Request $request) {
    // 1. Data Keuangan (Real Cash Flow)
    $pemasukan_hari_ini = Pesanan::whereDate('tanggal_pesan', Carbon::today())
        ->sum('jumlah_bayar'); 

    $pemasukan_bulan_ini = Pesanan::whereMonth('tanggal_pesan', Carbon::now()->month)
        ->whereYear('tanggal_pesan', Carbon::now()->year)
        ->sum('jumlah_bayar');

    // Piutang = Total Harga - Jumlah Bayar (Untuk pesanan yang belum lunas)
    $piutang = Pesanan::whereColumn('total_harga', '>', 'jumlah_bayar')
        ->where('status_pesanan', '!=', 'Dibatalkan')
        ->sum(DB::raw('total_harga - jumlah_bayar'));

    // 2. Data Operasional (Counter Status)
    $status_counts = [
        'baru' => Pesanan::where('status_pesanan', 'Menunggu Pembayaran')->count(),
        'proses' => Pesanan::where('status_pesanan', 'Diproses')->count(),
        'siap' => Pesanan::where('status_pesanan', 'Selesai')->count(), 
    ];

    // 3. LOGIKA CHART DINAMIS
    $filter = $request->input('filter', 'mingguan');
    $chart_data = [];
    $chart_label = [];
    $chart_title = ''; // Inisialisasi variabel judul

    if ($filter == 'bulanan') {
        // --- MODE BULANAN (Jan - Des Tahun Ini) ---
        $chart_title = 'Pemasukan Tahun ' . date('Y');
        
        for ($i = 1; $i <= 12; $i++) {
            $date = Carbon::create(null, $i, 1);
            $chart_label[] = $date->format('F'); 
            
            $income = Pesanan::whereYear('tanggal_pesan', Carbon::now()->year)
                ->whereMonth('tanggal_pesan', $i)
                ->sum('jumlah_bayar');
                
            $chart_data[] = $income;
        }

    } elseif ($filter == 'tahunan') {
        // --- MODE TAHUNAN (5 Tahun Terakhir) ---
        $chart_title = 'Pemasukan 5 Tahun Terakhir';
        
        for ($i = 4; $i >= 0; $i--) {
            $year = Carbon::now()->subYears($i)->year;
            $chart_label[] = $year;
            
            $income = Pesanan::whereYear('tanggal_pesan', $year)
                ->sum('jumlah_bayar');
                
            $chart_data[] = $income;
        }

    } else {
        // --- MODE MINGGUAN (7 Hari Terakhir) - DEFAULT ---
        $chart_title = 'Pemasukan 7 Hari Terakhir';
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chart_label[] = $date->format('d M');
            
            $income = Pesanan::whereDate('tanggal_pesan', $date)
                ->sum('jumlah_bayar');
                
            $chart_data[] = $income;
        }
    }

    // 4. Data Pesanan Terbaru
    $pesanan_terbaru = Pesanan::with(['pelanggan', 'layanan'])
        ->where('status_pesanan', 'Selesai')
        ->whereColumn('jumlah_bayar', '>=', 'total_harga')
        ->orderBy('id_pesanan', 'desc')
        ->take(5)
        ->get();

    // 5. PACKING DATA KE VIEW
    $data = [
        'total_pelanggan' => Pelanggan::count(),
        'pemasukan_hari_ini' => $pemasukan_hari_ini,
        'pemasukan_bulan_ini' => $pemasukan_bulan_ini,
        'piutang' => $piutang,
        'status_counts' => $status_counts,
        
        // Data Grafik
        'chart_label' => $chart_label, 
        'chart_data' => $chart_data,
        'chart_title' => $chart_title, // <--- INI WAJIB ADA
        'current_filter' => $filter,   // <--- INI JUGA WAJIB ADA
        
        'pesanan_terbaru' => $pesanan_terbaru
    ];

    return view('admin.dashboard', $data);
}

    public function updateStatus(Request $request, $id) {
    // 1. Ambil data pesanan
    $pesanan = Pesanan::with(['layanan', 'pelanggan'])->findOrFail($id);
    
    $status_lama = $pesanan->status_pesanan;
    $pesanan->status_pesanan = $request->status_pesanan;
    $pesanan->save();

    // 2. Cek Logika Status 'Selesai'
    if ($request->status_pesanan == 'Selesai' && $status_lama != 'Selesai') {
        
        $pelanggan = $pesanan->pelanggan; 
        
        if ($pelanggan && $pesanan->layanan) {
            
            // --- LOGIKA POIN (TETAP SAMA) ---
            $harga_per_kg = $pesanan->layanan->harga;
            $berat_poin = 0;

            if ($harga_per_kg > 0) {
                // Konversi Rupiah ke Berat untuk Poin
                $berat_poin = $pesanan->total_harga / $harga_per_kg;
            }

            $pelanggan->progres_kg += $berat_poin;

            // Cek Bonus Kelipatan 8
            while ($pelanggan->progres_kg >= 8) {
                $pelanggan->increment('bonus'); 
                $pelanggan->progres_kg -= 8;    
            }
            
            $pelanggan->save();
            // --------------------------------

            // --- LOGIKA PESAN WA DINAMIS (BARU) ---
            try {
                    // Cek Status Pembayaran
                    $sudahBayar = $pesanan->jumlah_bayar;
                    $totalTagihan = $pesanan->total_harga;
                    $sisaTagihan = $totalTagihan - $sudahBayar;
                    
                    // Anggap lunas jika sisa tagihan <= 0
                    $isLunas = $sisaTagihan <= 0;

                    $pesanWA = "Halo Kak *{$pelanggan->nama}*!\n\n";
                    $pesanWA .= "Update status pesanan *#{$pesanan->id_pesanan}*:\n";
                    $pesanWA .= "Status: *SELESAI*\n\n";
                    
                    // PERBAIKAN DI SINI:
                    // Menggunakan titik (.) untuk menggabungkan string dengan logika if/else singkat
                    $satuan = ($pesanan->layanan->jenis == 'Kiloan') ? 'Kg' : 'Pcs';
                    $pesanWA .= "Total Berat/Jml: {$pesanan->berat} {$satuan}\n";

                    if ($isLunas) {
                        // SKENARIO A: SUDAH LUNAS
                        $pesanWA .= "Status Bayar: *LUNAS*\n\n";
                        $pesanWA .= "Cucian Anda sudah bersih dan wangi. Silakan diambil di outlet kami atau hubungi admin untuk pengantaran.\n\n";
                    } else {
                        // SKENARIO B: BELUM LUNAS (UTANG)
                        $pesanWA .= "Total Tagihan: Rp " . number_format($totalTagihan, 0, ',', '.') . "\n";
                        $pesanWA .= "Sudah Dibayar: Rp " . number_format($sudahBayar, 0, ',', '.') . "\n";
                        $pesanWA .= "Kekurangan: *Rp " . number_format($sisaTagihan, 0, ',', '.') . "*\n\n";
                        
                        $pesanWA .= "Cucian sudah siap! Mohon selesaikan pembayaran saat pengambilan, atau klik link di bawah ini untuk pembayaran online:\n";
                        // Pastikan domain sesuai
                        $pesanWA .= url('/pesanan') . " \n\n"; 
                    }

                    $pesanWA .= "----------------\n";
                    $pesanWA .= "Poin Loyalty Anda: {$pelanggan->progres_kg}/8 Poin\n";
                    $pesanWA .= "Terima kasih telah menggunakan Ni Laundry! 🙏";

                    $this->sendWhatsapp($pelanggan->no_hp, $pesanWA);
                    
                } catch (\Exception $e) {
                    \Log::error("Gagal kirim WA pesanan selesai: " . $e->getMessage());
                }

        }
    }

    return back()->with('success', 'Status berhasil diubah menjadi Selesai.');
}

// Tambahkan ini di dalam class AdminController

public function bayarTunai($id) {
    // 1. Ambil Data
    $pesanan = Pesanan::with('pelanggan')->findOrFail($id);

    // 2. Cek Validasi
    if ($pesanan->total_harga <= 0) {
        return back()->with('error', 'Pesanan belum ditimbang (Total harga 0).');
    }

    // 3. Set Lunas (Cash)
    $pesanan->jumlah_bayar = $pesanan->total_harga; // Bayar Full
    
    // Opsi: Jika status masih 'Menunggu Pembayaran', otomatis ubah ke 'Diproses'
    if ($pesanan->status_pesanan == 'Menunggu Pembayaran') {
        $pesanan->status_pesanan = 'Diproses';
    }
    
    $pesanan->save();

    // 4. Kirim WA Kwitansi Lunas (Opsional tapi Keren)
    try {
        $pelanggan = $pesanan->pelanggan;
        $pesanWA = "Terima kasih Kak *{$pelanggan->nama}*!\n\n";
        $pesanWA .= "Pembayaran TUNAI untuk pesanan *#{$pesanan->id_pesanan}* telah kami terima.\n";
        $pesanWA .= "Nominal: Rp " . number_format($pesanan->total_harga, 0, ',', '.') . "\n";
        $pesanWA .= "Status Bayar: *LUNAS*\n\n";
        $pesanWA .= "Kami akan segera memproses/menyerahkan cucian Anda.";
        
        $this->sendWhatsapp($pelanggan->no_hp, $pesanWA);
    } catch (\Exception $e) {
        // Silent error
    }

    return back()->with('success', 'Pembayaran Tunai berhasil dicatat. Status LUNAS.');
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
    $pesanan = Pesanan::with('pelanggan')->findOrFail($id);
    
    // Siapkan data update
    $dataUpdate = [
        'berat' => $request->berat,
        'total_harga' => $request->total_harga, 
        'status_pesanan' => $request->status_pesanan,
    ];

    // LOGIKA BAYAR CASH MANUAL (Di halaman Edit)
    // Hanya update jumlah_bayar jika admin mengisinya di form
    if ($request->filled('jumlah_bayar')) {
        $dataUpdate['jumlah_bayar'] = $request->jumlah_bayar;
    }
    // Jika tidak diisi, biarkan nilai lama (jangan di-reset ke 0)

    $pesanan->update($dataUpdate);

        // --- TAMBAHAN LOGIKA WA ---
        // Jika Admin mengubah status jadi "Menunggu Pembayaran", kirim WA tagihan ke User
        if ($request->status_pesanan == 'Menunggu Pembayaran') {
            try {
                $pelanggan = $pesanan->pelanggan;
                $pesanWA = "Halo Kak *{$pelanggan->nama}*! \n\n";
                $pesanWA .= "Cucian Anda (#{$pesanan->id_pesanan}) sudah kami timbang.\n";
                $pesanWA .= "Berat: *{$request->berat} Kg*\n";
                $pesanWA .= "Total Tagihan: *Rp " . number_format($request->total_harga, 0, ',', '.') . "*\n\n";
                $pesanWA .= "Silakan buka menu *Riwayat* di aplikasi/web untuk melakukan pembayaran agar cucian segera diproses. Terima kasih!";

                $this->sendWhatsapp($pelanggan->no_hp, $pesanWA);
            } catch (\Exception $e) {
                // Abaikan jika WA gagal, tetap lanjut redirect
            }
        }
        // ---------------------------

        return redirect('/admin/pesanan')->with('success', 'Pesanan diperbarui. Notifikasi tagihan (jika ada) telah dikirim ke pelanggan.');
    }

    public function processRefund($id) {
        // 1. Ambil Data
        $pesanan = Pesanan::with(['pelanggan', 'layanan'])->findOrFail($id);

        // 2. Validasi: Hanya bisa refund jika sudah ada pembayaran
        if ($pesanan->jumlah_bayar <= 0) {
            return back()->with('error', 'Pesanan ini belum dibayar, tidak ada dana yang bisa di-refund.');
        }

        // Simpan jumlah yang di-refund untuk pesan WA
        $nominalRefund = $pesanan->jumlah_bayar;

        // 3. LOGIKA TARIK KEMBALI POIN (Anti-Cheat)
        // Jika status sebelumnya 'Selesai', poin yang didapat harus ditarik lagi.
        if ($pesanan->status_pesanan == 'Selesai') {
            $pelanggan = $pesanan->pelanggan;
            
            if ($pelanggan && $pesanan->layanan) {
                $harga_layanan = $pesanan->layanan->harga;
                
                // Hitung berapa poin yang dulu didapat dari pesanan ini
                $berat_poin_dihapus = ($harga_layanan > 0) 
                    ? floor($pesanan->total_harga / $harga_layanan) 
                    : 0;

                $pelanggan->progres_kg -= $berat_poin_dihapus;

                // Logika mundur jika poin minus (ambil dari bonus/set 0)
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

        // 4. Update Data Pesanan
        $pesanan->jumlah_bayar = 0; // Uang dianggap keluar dari kasir
        $pesanan->status_pesanan = 'Dikembalikan'; // Status khusus Refund
        $pesanan->save();

        // 5. Kirim WA Notifikasi Refund
        try {
            $pelanggan = $pesanan->pelanggan;
            $pesanWA = "Halo Kak *{$pelanggan->nama}*,\n\n";
            $pesanWA .= "Pengembalian Dana (Refund) untuk pesanan *#{$pesanan->id_pesanan}* telah diproses.\n";
            $pesanWA .= "Nominal Refund: *Rp " . number_format($nominalRefund, 0, ',', '.') . "*\n";
            $pesanWA .= "Status Pesanan: *DIKEMBALIKAN / BATAL*\n\n";
            
            if($nominalRefund > 0) {
                $pesanWA .= "Dana telah kami kembalikan (Tunai/Transfer). Silakan cek mutasi atau konfirmasi ke admin.\n";
            }
            
            $pesanWA .= "Mohon maaf atas ketidaknyamanannya. 🙏";

            $this->sendWhatsapp($pelanggan->no_hp, $pesanWA);

        } catch (\Exception $e) {
            \Log::error("Gagal kirim WA Refund: " . $e->getMessage());
        }

        return back()->with('success', 'Refund berhasil diproses. Status diubah menjadi Dikembalikan & Poin ditarik (jika ada).');
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

    public function exportExcel(Request $request) 
    {
        $filter = $request->input('filter', 'mingguan');
        $namaFile = 'Laporan_Keuangan_' . ucfirst($filter) . '_' . date('d-m-Y') . '.xlsx';
        
        return Excel::download(new LaporanKeuanganNiLaundry($filter), $namaFile);
    }
}