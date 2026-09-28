<?php
require_once 'koneksi.php';
if (!isset($_SESSION['role'])) { header("Location: login.php"); exit; }

$queryLaporan = mysqli_query($koneksi, "
    SELECT 
        pg.tanggal,
        pg.nama_staff,
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
    <title>Laporan Pengambilan Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body class="p-4" onload="window.print()">
    <div class="text-center mb-4">
        <h2>LAPORAN PENGAMBILAN & PEMINJAMAN BARANG</h2>
        <p>Sistem Informasi Inventory & Consumables</p>
        <hr>
    </div>

    <button onclick="window.print()" class="btn btn-primary mb-3 no-print">Cetak Laporan</button>

    <table class="table table-bordered align-middle">
        <thead>
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
            <?php $no = 1; while ($row = mysqli_fetch_assoc($queryLaporan)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['tanggal'] ?></td>
                <td><?= htmlspecialchars($row['nama_staff']) ?></td>
                <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                <td><?= $row['jenis_barang'] ?></td>
                <td><?= $row['jumlah'] ?> <?= htmlspecialchars($row['satuan']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>