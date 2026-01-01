<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes (FIXED VERSION)
|--------------------------------------------------------------------------
*/

// --- 1. PUBLIC ROUTES (Bisa diakses siapa saja) ---
Route::get('/', [UserController::class, 'index'])->name('home');
Route::get('/layanan', [UserController::class, 'layanan'])->name('layanan'); // SUDAH DIPINDAHKAN KESINI

// --- 2. AUTHENTICATION ---
// Guest Only (Hanya bisa diakses jika BELUM login)
Route::get('/login', [AuthController::class, 'formLoginUser'])->name('login');
Route::post('/login', [AuthController::class, 'loginUser'])->middleware('throttle:5,1');
Route::get('/register', function() { return view('auth.register'); });
Route::post('/register', [AuthController::class, 'registerUser']);

Route::get('/admin/login', [AuthController::class, 'formLoginAdmin']);
Route::post('/admin/login', [AuthController::class, 'loginAdmin']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'formForgotPassword']);

Route::post('/forgot-password', [AuthController::class, 'sendOtp']);

Route::get('/verify-otp', [AuthController::class, 'formVerifyOtp']);

Route::post('/reset-password', [AuthController::class, 'processResetPassword']);

// --- 3. AREA MEMBER (Wajib Login User) ---
Route::middleware('auth:web')->group(function () {
    // Route layanan sudah dipindah ke Public agar tamu bisa lihat harga
    Route::get('/riwayat', [UserController::class, 'riwayat'])->name('riwayat');
    
    // Transaksi
    Route::post('/pesan', [UserController::class, 'storePesanan'])->name('pesan.store');
    Route::get('/pesanan/sukses/{id}', [UserController::class, 'paymentSuccess'])->name('pesan.sukses');
});

// --- 4. AREA ADMIN (Wajib Login Admin) ---
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // CRUD Layanan
    Route::resource('/admin/layanan', AdminController::class)->except(['show']); 
    // Tip: 'resource' otomatis membuat route index, create, store, edit, update, destroy
    // Tapi jika mau manual seperti kodemu sebelumnya, tidak masalah, asalkan konsisten.

    // Manajemen Pesanan
    Route::get('/admin/pesanan', [AdminController::class, 'pesananIndex'])->name('admin.pesanan.index');
    Route::get('/admin/pesanan/{id}/edit', [AdminController::class, 'pesananEdit'])->name('admin.pesanan.edit');
    Route::put('/admin/pesanan/{id}', [AdminController::class, 'pesananUpdate'])->name('admin.pesanan.update');
    Route::delete('/admin/pesanan/{id}', [AdminController::class, 'pesananDestroy'])->name('admin.pesanan.destroy');
    Route::post('/admin/pesanan/{id}/update-status', [AdminController::class, 'updateStatus'])->name('admin.pesanan.status');

    // Manajemen User
    Route::get('/admin/users', [AdminController::class, 'userAdminIndex'])->name('admin.users.index');
    // ... sisa route admin user ...
    
    Route::get('/admin/diskon', [AdminController::class, 'diskonIndex'])->name('admin.diskon');
    Route::post('/admin/diskon/{id}/reset', [AdminController::class, 'resetBonus'])->name('admin.diskon.reset');
});