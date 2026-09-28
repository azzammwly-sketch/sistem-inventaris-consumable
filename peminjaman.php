<?php
require_once 'koneksi.php';
if (!isset($_SESSION['role'])) { header("Location: login.php"); exit; }

// Proses Pengembalian Barang
if (isset($_GET['kembali'])) {
    $id_peminjaman = (int)$_GET['kembali'];

    $q = mysqli_query($koneksi, "
        SELECT p.id_peminjaman, dp.id_barang, dp.jumlah 
        FROM tb_peminjaman p
        JOIN tb_detail_pengambilan dp ON p.id_detail = dp.id_detail
        WHERE p.id_peminjaman = $id_peminjaman AND p.status = 'Dipinjam'
    ");
    $data = mysqli_fetch_assoc($q);

    if ($data) {
        $id_barang = $data['id_barang'];
        $jumlah    = $data['jumlah'];

        // Update status peminjaman
        mysqli_query($koneksi, "UPDATE tb_peminjaman SET status='Kembali', tanggal_kembali=NOW() WHERE id_peminjaman=$id_peminjaman");

        // Kembalikan stok barang
        mysqli_query($koneksi, "UPDATE tb_barang SET stok = stok + $jumlah WHERE id_barang=$id_barang");
    }

    header("Location: peminjaman.php");
    exit;
}

$queryPeminjaman = mysqli_query($koneksi, "
    SELECT 
        p.id_peminjaman,
        pg.nama_staff,
        b.nama_barang,
        dp.jumlah,
        b.satuan,
        p.tanggal_pinjam,
        p.tanggal_kembali,
        p.status
    FROM tb_peminjaman p
    JOIN tb_detail_pengambilan dp ON p.id_detail = dp.id_detail
    JOIN tb_pengambilan pg ON dp.id_pengambilan = pg.id_pengambilan
    JOIN tb_barang b ON dp.id_barang = b.id_barang
    ORDER BY p.id_peminjaman DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Peminjaman Barang</title>
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
                <li class="nav-item"><a class="nav-link active" href="peminjaman.php">Status Peminjaman</a></li>
                <li class="nav-item"><a class="nav-link" href="riwayat.php">Riwayat & Laporan</a></li>
            </ul>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container my-4">
    <h3 class="mb-3">Daftar Peminjaman Barang</h3>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Staff</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($queryPeminjaman)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama_staff']) ?></td>
                <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                <td><?= $row['jumlah'] ?> <?= htmlspecialchars($row['satuan']) ?></td>
                <td><?= $row['tanggal_pinjam'] ?></td>
                <td><?= $row['tanggal_kembali'] ? $row['tanggal_kembali'] : '-' ?></td>
                <td>
                    <span class="badge <?= $row['status'] === 'Dipinjam' ? 'bg-danger' : 'bg-success' ?>">
                        <?= $row['status'] ?>
                    </span>
                </td>
                <td>
                    <?php if ($row['status'] === 'Dipinjam'): ?>
                        <a href="peminjaman.php?kembali=<?= $row['id_peminjaman'] ?>" 
                           class="btn btn-sm btn-success" 
                           onclick="return confirm('Konfirmasi pengembalian barang ini?')">
                           Kembalikan Barang
                        </a>
                    <?php else: ?>
                        <span class="text-muted">Selesai</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>