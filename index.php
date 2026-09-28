<?php
require_once 'koneksi.php';
if (!isset($_SESSION['role'])) { header("Location: login.php"); exit; }

$count_barang     = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tb_barang"))['total'];
$count_pinjam     = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tb_peminjaman WHERE status='Dipinjam'"))['total'];
$count_pengambilan= mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tb_pengambilan"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Sistem Consumable</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Consumable System</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link active" href="index.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="barang.php">Data Barang</a></li>
                <li class="nav-item"><a class="nav-link" href="pengambilan.php">Pengambilan Barang</a></li>
                <li class="nav-item"><a class="nav-link" href="peminjaman.php">Status Peminjaman</a></li>
                <li class="nav-item"><a class="nav-link" href="riwayat.php">Riwayat & Laporan</a></li>
            </ul>
            <span class="navbar-text me-3 text-white">
                <?= $_SESSION['nama']; ?> (<strong><?= $_SESSION['role']; ?></strong>)
            </span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container my-4">
    <h3 class="mb-4">Selamat Datang, <?= $_SESSION['nama']; ?></h3>
    
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card text-white bg-primary p-3 shadow-sm">
                <h5>Total Items Barang</h5>
                <h2><?= $count_barang ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning p-3 shadow-sm">
                <h5>Barang Sedang Dipinjam</h5>
                <h2><?= $count_pinjam ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success p-3 shadow-sm">
                <h5>Total Transaksi Pengambilan</h5>
                <h2><?= $count_pengambilan ?></h2>
            </div>
        </div>
    </div>
</div>
</body>
</html>