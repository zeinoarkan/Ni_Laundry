<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pelanggan - Ni Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

    <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center mb-4 text-primary">Ni Laundry</h3>
        
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="/login" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Username / Nama</label>
                <input type="text" name="nama" class="form-control" required placeholder="Masukkan nama anda">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="********">
            </div>
            <button type="submit" class="btn btn-primary w-100">Masuk</button>
        </form>
        
        <div class="text-center mt-3">
            <small>Belum punya akun? <a href="/register">Daftar disini</a></small>
            <br>
            <small><a href="/admin/login" class="text-secondary">Login sebagai Admin</a></small>
        </div>
    </div>

</body>
</html>