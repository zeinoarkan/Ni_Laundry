<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

// --- 1. HALAMAN UTAMA (PUBLIC) ---
// Logika: Langsung masuk ke Dashboard Controller, tidak redirect ke login lagi
Route::get('/', [UserController::class, 'index'])->name('dashboard');

// --- 2. AUTHENTICATION ---
Route::get('/login', [AuthController::class, 'formLoginUser'])->name('login');
Route::post('/login', [AuthController::class, 'loginUser'])->middleware('throttle:5,1');
Route::get('/register', function() { return view('auth.register'); });
Route::post('/register', [AuthController::class, 'registerUser']);

Route::get('/admin/login', [AuthController::class, 'formLoginAdmin']);
Route::post('/admin/login', [AuthController::class, 'loginAdmin']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/forgot-password', [AuthController::class, 'formForgotPassword']);

Route::post('/forgot-password', [AuthController::class, 'sendOtp']);

Route::get('/verify-otp', [AuthController::class, 'formVerifyOtp']);

Route::post('/reset-password', [AuthController::class, 'processResetPassword']);

// --- 3. AREA PELANGGAN (MEMBER ONLY) ---
// Dashboard sudah dikeluarkan dari sini, sisanya tetap wajib login
Route::middleware('auth:web')->group(function () {
    Route::get('/layanan', [UserController::class, 'layanan']);
    Route::get('/riwayat', [UserController::class, 'riwayat']);
    
    Route::post('/pesan', [UserController::class, 'storePesanan']);
    Route::get('/pesanan/sukses/{id}', [UserController::class, 'paymentSuccess']);
});

// --- 4. AREA ADMIN (TETAP SAMA) ---
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    
    // CRUD Layanan
    Route::get('/admin/layanan', [AdminController::class, 'layananIndex']); 
    Route::get('/admin/layanan/create', [AdminController::class, 'layananCreate']); 
    Route::post('/admin/layanan', [AdminController::class, 'layananStore']); 
    Route::get('/admin/layanan/{id}/edit', [AdminController::class, 'layananEdit']); 
    Route::put('/admin/layanan/{id}', [AdminController::class, 'layananUpdate']); 
    Route::delete('/admin/layanan/{id}', [AdminController::class, 'layananDestroy']); 

    // Manajemen Pesanan
    Route::get('/admin/pesanan', [AdminController::class, 'pesananIndex']); 
    Route::get('/admin/pesanan/{id}/edit', [AdminController::class, 'pesananEdit']);
    Route::put('/admin/pesanan/{id}', [AdminController::class, 'pesananUpdate']); 
    Route::delete('/admin/pesanan/{id}', [AdminController::class, 'pesananDestroy']);
    Route::post('/admin/pesanan/{id}/update-status', [AdminController::class, 'updateStatus']);

    // Manajemen User
    Route::get('/admin/users', [AdminController::class, 'userAdminIndex']); 
    Route::get('/admin/users/create', [AdminController::class, 'userAdminCreate']); 
    Route::post('/admin/users', [AdminController::class, 'userAdminStore']); 
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'userAdminEdit']); 
    Route::put('/admin/users/{id}', [AdminController::class, 'userAdminUpdate']); 
    Route::delete('/admin/users/{id}', [AdminController::class, 'userAdminDestroy']); 

    Route::get('/admin/diskon', [AdminController::class, 'diskonIndex']);
    Route::post('/admin/diskon/{id}/reset', [AdminController::class, 'resetBonus']);
});