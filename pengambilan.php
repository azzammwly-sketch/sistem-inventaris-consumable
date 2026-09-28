<?php
require_once 'koneksi.php';
if (!isset($_SESSION['role'])) { header("Location: login.php"); exit; }

$pesan = '';

if (isset($_POST['submit_pengambilan'])) {
    $nama_staff = mysqli_real_escape_string($koneksi, $_POST['nama_staff']);
    $id_barang  = (int)$_POST['id_barang'];
    $jumlah     = (int)$_POST['jumlah'];

    // Cek Stok
    $qBarang = mysqli_query($koneksi, "SELECT * FROM tb_barang WHERE id_barang = $id_barang");
    $barang  = mysqli_fetch_assoc($qBarang);

    if ($barang && $barang['stok'] >= $jumlah) {
        // Transaksi DB
        mysqli_query($koneksi, "INSERT INTO tb_pengambilan (nama_staff, tanggal) VALUES ('$nama_staff', NOW())");
        $id_pengambilan = mysqli_insert_id($koneksi);

        mysqli_query($koneksi, "INSERT INTO tb_detail_pengambilan (id_pengambilan, id_barang, jumlah) VALUES ($id_pengambilan, $id_barang, $jumlah)");
        $id_detail = mysqli_insert_id($koneksi);

        // Potong stok
        mysqli_query($koneksi, "UPDATE tb_barang SET stok = stok - $jumlah WHERE id_barang = $id_barang");

        // Jika jenis barang adalah Pinjaman -> catat di tb_peminjaman
        if ($barang['jenis_barang'] === 'Pinjaman') {
            mysqli_query($koneksi, "INSERT INTO tb_peminjaman (id_detail, tanggal_pinjam, status) VALUES ($id_detail, NOW(), 'Dipinjam')");
        }

        $pesan = '<div class="alert alert-success">Pengambilan barang berhasil dicatat!</div>';
    } else {
        $pesan = '<div class="alert alert-danger">Jumlah melebihi stok yang tersedia!</div>';
    }
}

$barangList = mysqli_query($koneksi, "SELECT * FROM tb_barang WHERE stok > 0 ORDER BY nama_barang ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Pengambilan Barang</title>
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
                <li class="nav-item"><a class="nav-link active" href="pengambilan.php">Pengambilan Barang</a></li>
                <li class="nav-item"><a class="nav-link" href="peminjaman.php">Status Peminjaman</a></li>
                <li class="nav-item"><a class="nav-link" href="riwayat.php">Riwayat & Laporan</a></li>
            </ul>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container my-4" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Form Pengambilan / Peminjaman Barang</h5>
        </div>
        <div class="card-body">
            <?= $pesan ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nama Staff / Pengambil</label>
                    <input type="text" name="nama_staff" class="form-control" value="<?= htmlspecialchars($_SESSION['nama']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pilih Barang</label>
                    <select name="id_barang" class="form-select" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php while ($b = mysqli_fetch_assoc($barangList)): ?>
                            <option value="<?= $b['id_barang'] ?>">
                                <?= htmlspecialchars($b['nama_barang']) ?> (Kategori: <?= $b['jenis_barang'] ?>, Stok: <?= $b['stok'] ?> <?= $b['satuan'] ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="jumlah" class="form-control" min="1" required>
                </div>
                <button type="submit" name="submit_pengambilan" class="btn btn-success w-100">Submit Pengambilan</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>