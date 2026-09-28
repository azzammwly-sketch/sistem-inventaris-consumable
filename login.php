<?php
require_once 'koneksi.php';

$error = '';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $query  = mysqli_query($koneksi, "SELECT * FROM tb_pengguna WHERE username='$username'");
    $user   = mysqli_fetch_assoc($query);

    if ($user) {
        // Cek password (mendukung password_hash maupun text biasa jika data awal)
        if (password_verify($password, $user['password']) || $password === $user['password']) {
            $_SESSION['id_pengguna'] = $user['id_pengguna'];
            $_SESSION['username']    = $user['username'];
            $_SESSION['nama']        = $user['nama'];
            $_SESSION['role']        = $user['role'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistem Consumable</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center min-vh-100">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="card-title text-center mb-4 font-weight-bold">Sistem Inventaris</h4>
                    <?php if ($error): ?>
                        <div class="alert alert-danger p-2 fs-6"><?= $error ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary w-100">Masuk</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>