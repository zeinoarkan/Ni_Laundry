<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 

// 1. Load Composer Autoloader
require __DIR__ . '/../vendor/autoload.php';

// 2. Load Bootstrap App (Laravel 11 style)
$app = require_once __DIR__ . '/../bootstrap/app.php';

// === FIX VERCEL FOR LARAVEL 11 ===
// Paksa aplikasi menggunakan folder sementara untuk storage & cache
// karena folder bawaan Vercel tidak bisa ditulis (Read-Only).
$app->useStoragePath('/tmp');

// Opsional: Paksa path public jika aset tidak terbaca
$app->bind('path.public', function() {
    return __DIR__ . '/../public';
});
// =================================

// 3. Handle Request
$request = Illuminate\Http\Request::capture();
$response = $app->handle($request);

$response->send();

$app->terminate($request, $response);