<?php
require_once 'koneksi.php';
if (!isset($_SESSION['role'])) { header("Location: login.php"); exit; }

$isAdmin = ($_SESSION['role'] === 'Admin');

// Tambah Barang
if ($isAdmin && isset($_POST['tambah'])) {
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
    $jenis  = $_POST['jenis_barang'];
    $stok   = (int)$_POST['stok'];
    $satuan = mysqli_real_escape_string($koneksi, $_POST['satuan']);

    mysqli_query($koneksi, "INSERT INTO tb_barang (nama_barang, jenis_barang, stok, satuan) VALUES ('$nama', '$jenis', $stok, '$satuan')");
    header("Location: barang.php");
    exit;
}

// Edit Barang
if ($isAdmin && isset($_POST['edit'])) {
    $id     = (int)$_POST['id_barang'];
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
    $jenis  = $_POST['jenis_barang'];
    $stok   = (int)$_POST['stok'];
    $satuan = mysqli_real_escape_string($koneksi, $_POST['satuan']);

    mysqli_query($koneksi, "UPDATE tb_barang SET nama_barang='$nama', jenis_barang='$jenis', stok=$stok, satuan='$satuan' WHERE id_barang=$id");
    header("Location: barang.php");
    exit;
}

// Hapus Barang
if ($isAdmin && isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM tb_barang WHERE id_barang=$id");
    header("Location: barang.php");
    exit;
}

$barangList = mysqli_query($koneksi, "SELECT * FROM tb_barang ORDER BY id_barang DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Consumable System</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="barang.php">Data Barang</a></li>
                <li class="nav-item"><a class="nav-link" href="pengambilan.php">Pengambilan Barang</a></li>
                <li class="nav-item"><a class="nav-link" href="peminjaman.php">Status Peminjaman</a></li>
                <li class="nav-item"><a class="nav-link" href="riwayat.php">Riwayat & Laporan</a></li>
            </ul>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Daftar Barang</h3>
        <?php if ($isAdmin): ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Barang</button>
        <?php endif; ?>
    </div>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Jenis Barang</th>
                <th>Stok</th>
                <th>Satuan</th>
                <?php if ($isAdmin): ?><th>Aksi</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($b = mysqli_fetch_assoc($barangList)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($b['nama_barang']) ?></td>
                <td>
                    <span class="badge <?= $b['jenis_barang'] === 'Habis pakai' ? 'bg-secondary' : 'bg-info text-dark' ?>">
                        <?= $b['jenis_barang'] ?>
                    </span>
                </td>
                <td><?= $b['stok'] ?></td>
                <td><?= htmlspecialchars($b['satuan']) ?></td>
                <?php if ($isAdmin): ?>
                <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $b['id_barang'] ?>">Edit</button>
                    <a href="barang.php?hapus=<?= $b['id_barang'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus barang ini?')">Hapus</a>
                </td>
                <?php endif; ?>
            </tr>

            <?php if ($isAdmin): ?>
            <!-- Modal Edit -->
            <div class="modal fade" id="modalEdit<?= $b['id_barang'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Barang</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id_barang" value="<?= $b['id_barang'] ?>">
                            <div class="mb-3">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" name="nama_barang" class="form-control" value="<?= htmlspecialchars($b['nama_barang']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis Barang</label>
                                <select name="jenis_barang" class="form-select" required>
                                    <option value="Habis pakai" <?= $b['jenis_barang'] === 'Habis pakai' ? 'selected' : '' ?>>Habis pakai</option>
                                    <option value="Pinjaman" <?= $b['jenis_barang'] === 'Pinjaman' ? 'selected' : '' ?>>Pinjaman</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stok</label>
                                <input type="number" name="stok" class="form-control" value="<?= $b['stok'] ?>" min="0" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Satuan</label>
                                <input type="text" name="satuan" class="form-control" value="<?= htmlspecialchars($b['satuan']) ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="edit" class="btn btn-success">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php if ($isAdmin): ?>
<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Barang Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Barang</label>
                    <select name="jenis_barang" class="form-select" required>
                        <option value="Habis pakai">Habis pakai</option>
                        <option value="Pinjaman">Pinjaman</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number" name="stok" class="form-control" min="0" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="satuan" class="form-control" placeholder="Pcs, Pack, Unit, dll" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>