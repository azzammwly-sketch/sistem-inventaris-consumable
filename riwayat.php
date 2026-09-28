<?php
require_once 'koneksi.php';
if (!isset($_SESSION['role'])) { header("Location: login.php"); exit; }

$queryRiwayat = mysqli_query($koneksi, "
    SELECT 
        pg.id_pengambilan,
        pg.nama_staff,
        pg.tanggal,
        b.nama_barang,
        b.jenis_barang,
        dp.jumlah,
        b.satuan
    FROM tb_pengambilan pg
    JOIN tb_detail_pengambilan dp ON pg.id_pengambilan = dp.id_pengambilan
    JOIN tb_barang b ON dp.id_barang = b.id_barang
    ORDER BY pg.tanggal DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pengambilan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Consumable System</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="barang.php">Data Barang</a></li>
                <li class="nav-item"><a class="nav-link" href="pengambilan.php">Pengambilan Barang</a></li>
                <li class="nav-item"><a class="nav-link" href="peminjaman.php">Status Peminjaman</a></li>
                <li class="nav-item"><a class="nav-link active" href="riwayat.php">Riwayat & Laporan</a></li>
            </ul>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Riwayat Transaksi Pengambilan</h3>
        <a href="cetak_laporan.php" target="_blank" class="btn btn-secondary">Cetak / Export PDF</a>
    </div>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Staff</th>
                <th>Nama Barang</th>
                <th>Jenis</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($r = mysqli_fetch_assoc($queryRiwayat)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $r['tanggal'] ?></td>
                <td><?= htmlspecialchars($r['nama_staff']) ?></td>
                <td><?= htmlspecialchars($r['nama_barang']) ?></td>
                <td><span class="badge bg-info text-dark"><?= $r['jenis_barang'] ?></span></td>
                <td><?= $r['jumlah'] ?> <?= htmlspecialchars($r['satuan']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>