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
        $pesanan = Pesanan::find($id);
        
        // Simpan status lama untuk pengecekan
        $status_lama = $pesanan->status_pesanan;
        
        // Update Status
        $pesanan->status_pesanan = $request->status_pesanan;
        $pesanan->save();

        // LOGIKA PROMO/BONUS (Sesuai SRS)
        // Jika status berubah jadi 'Selesai' dan sebelumnya belum selesai
        if ($request->status_pesanan == 'Selesai' && $status_lama != 'Selesai') {
            $pelanggan = Pelanggan::find($pesanan->id_pelanggan);
            
            // Tambah progres berat
            $pelanggan->progres_kg += $pesanan->berat;
            
            // Cek jika sudah mencapai 8 KG (Dapat Bonus)
            if ($pelanggan->progres_kg >= 8) {
                $pelanggan->bonus = 1; // Dapat tiket gratis
                $pelanggan->progres_kg = $pelanggan->progres_kg - 8; // Kurangi poin yg dipakai
            }
            
            $pelanggan->save();
        }

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
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

    public function pesananIndex() {
        // Tampilkan semua pesanan, urutkan dari yg terbaru
        $pesanan = Pesanan::with(['pelanggan', 'layanan'])
                   ->orderBy('id_pesanan', 'desc')
                   ->get();
        return view('admin.pesanan.index', compact('pesanan'));
    }

    public function pesananEdit($id) {
        $pesanan = Pesanan::with(['pelanggan', 'layanan'])->findOrFail($id);
        return view('admin.pesanan.edit', compact('pesanan'));
    }

    public function pesananUpdate(Request $request, $id) {
        $pesanan = Pesanan::findOrFail($id);
        
        // Admin bisa update berat dan status
        $pesanan->update([
            'berat' => $request->berat,
            'total_harga' => $request->total_harga, // Admin bisa manual set harga
            'status_pesanan' => $request->status_pesanan,
            'jumlah_bayar' => $request->jumlah_bayar ?? 0
        ]);

        return redirect('/admin/pesanan')->with('success', 'Data pesanan diperbarui');
    }

    public function pesananDestroy($id) {
        Pesanan::findOrFail($id)->delete();
        return redirect('/admin/pesanan')->with('success', 'Pesanan dihapus');
    }

    // CRUD ADMIN (PENGGUNA)

    public function userAdminIndex() {
        // Ambil semua admin
        $admins = Admin::all();
        return view('admin.users.index', compact('admins'));
    }

    public function userAdminCreate() {
        return view('admin.users.create');
    }

    public function userAdminStore(Request $request) {
        // Validasi sederhana (opsional)
        $request->validate([
            'username' => 'required|unique:admin,username',
            'password' => 'required|min:6'
        ]);

        Admin::create([
            'username' => $request->username,
            'password' => Hash::make($request->password) // Enkripsi password
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

        // Hanya update password jika input tidak kosong
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect('/admin/users')->with('success', 'Data admin diperbarui');
    }

    public function userAdminDestroy($id) {
        // Mencegah admin menghapus dirinya sendiri saat sedang login
        if ($id == Auth::guard('admin')->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun yang sedang digunakan!');
        }

        Admin::findOrFail($id)->delete();
        return redirect('/admin/users')->with('success', 'Admin berhasil dihapus');
    }

    
}