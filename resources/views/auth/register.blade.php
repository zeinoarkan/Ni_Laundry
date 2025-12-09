<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar - Ni Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card shadow p-4" style="width: 400px;">
        <h4 class="text-center mb-3">Daftar Akun Baru</h4>
        <form action="/register" method="POST">
            @csrf
            <div class="mb-2"><label>Nama Lengkap</label><input type="text" name="nama" class="form-control" required></div>
            <div class="mb-2"><label>Password</label><input type="password" name="password" class="form-control" required></div>
            <div class="mb-2"><label>No HP</label><input type="text" name="no_hp" class="form-control" required></div>
            <div class="mb-3"><label>Alamat</label><textarea name="alamat" class="form-control" required></textarea></div>
            <button type="submit" class="btn btn-success w-100">Daftar Sekarang</button>
        </form>
        <div class="text-center mt-3"><a href="/login">Sudah punya akun? Login</a></div>
    </div>
</body>
</html>