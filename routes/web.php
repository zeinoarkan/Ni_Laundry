<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

// 1. Redirect halaman awal langsung ke Login User
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Route Authentication (Login/Register)
Route::get('/login', [AuthController::class, 'formLoginUser'])->name('login');
Route::post('/login', [AuthController::class, 'loginUser']);
Route::get('/register', function() { return view('auth.register'); });
Route::post('/register', [AuthController::class, 'registerUser']);

Route::get('/admin/login', [AuthController::class, 'formLoginAdmin']);
Route::post('/admin/login', [AuthController::class, 'loginAdmin']);
Route::get('/logout', [AuthController::class, 'logout']);

// 3. Area Pelanggan (Wajib Login)
Route::middleware('auth:web')->group(function () {
    Route::get('/dashboard', [UserController::class, 'index']); 
    
    Route::get('/layanan', [UserController::class, 'layanan']);

    Route::get('/pesanan/sukses/{id}', [UserController::class, 'paymentSuccess']);
    Route::post('/pesan', [UserController::class, 'storePesanan']);
    Route::get('/riwayat', [UserController::class, 'riwayat']);
});

// 4. Area Admin (Wajib Login Admin)
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    
    // --- CRUD LAYANAN ---
    Route::get('/admin/layanan', [AdminController::class, 'layananIndex']); // Lihat semua
    Route::get('/admin/layanan/create', [AdminController::class, 'layananCreate']); // Form tambah
    Route::post('/admin/layanan', [AdminController::class, 'layananStore']); // Simpan baru
    Route::get('/admin/layanan/{id}/edit', [AdminController::class, 'layananEdit']); // Form edit
    Route::put('/admin/layanan/{id}', [AdminController::class, 'layananUpdate']); // Simpan edit
    Route::delete('/admin/layanan/{id}', [AdminController::class, 'layananDestroy']); // Hapus

    // --- MANAJEMEN PESANAN ---
    Route::get('/admin/pesanan', [AdminController::class, 'pesananIndex']); // Lihat semua list
    Route::get('/admin/pesanan/{id}/edit', [AdminController::class, 'pesananEdit']); // Form edit pesanan
    Route::put('/admin/pesanan/{id}', [AdminController::class, 'pesananUpdate']); // Update data pesanan
    Route::delete('/admin/pesanan/{id}', [AdminController::class, 'pesananDestroy']); // Hapus pesanan
    Route::post('/admin/pesanan/{id}/update-status', [AdminController::class, 'updateStatus']);

    Route::get('/admin/users', [AdminController::class, 'userAdminIndex']); // List Admin
    Route::get('/admin/users/create', [AdminController::class, 'userAdminCreate']); // Form Tambah
    Route::post('/admin/users', [AdminController::class, 'userAdminStore']); // Simpan
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'userAdminEdit']); // Form Edit
    Route::put('/admin/users/{id}', [AdminController::class, 'userAdminUpdate']); // Update
    Route::delete('/admin/users/{id}', [AdminController::class, 'userAdminDestroy']); // Hapus

    Route::get('/admin/diskon', [AdminController::class, 'diskonIndex']);
    Route::post('/admin/diskon/{id}/reset', [AdminController::class, 'resetBonus']);
});