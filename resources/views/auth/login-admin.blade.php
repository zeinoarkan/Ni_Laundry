<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login Admin - Ni Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card shadow p-4" style="width: 400px;">
        <h4 class="text-center mb-3">ADMINISTRATOR</h4>
        <form action="/admin/login" method="POST">
            @csrf
            <div class="mb-3"><label>Username</label><input type="text" name="username" class="form-control" required></div>
            <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
            <button type="submit" class="btn btn-danger w-100">Login Admin</button>
        </form>
        <div class="text-center mt-3"><a href="/login">Kembali ke halaman user</a></div>
    </div>
</body>
</html>