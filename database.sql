CREATE DATABASE IF NOT EXISTS `db_consumable`;
USE `db_consumable`;

CREATE TABLE IF NOT EXISTS `tb_pengguna` (
  `id_pengguna` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `role` enum('Admin','Staff') NOT NULL,
  PRIMARY KEY (`id_pengguna`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_pengguna` (`id_pengguna`, `username`, `password`, `nama`, `role`) VALUES
(1, 'admin', 'admin123', 'Administrator Utama', 'Admin'),
(2, 'staff', 'staff123', 'Staff Operasional', 'Staff');

CREATE TABLE IF NOT EXISTS `tb_barang` (
  `id_barang` int(11) NOT NULL AUTO_INCREMENT,
  `nama_barang` varchar(100) NOT NULL,
  `jenis_barang` enum('Habis pakai','Pinjaman') NOT NULL,
  `stok` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `satuan` varchar(10) NOT NULL,
  PRIMARY KEY (`id_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_barang` (`id_barang`, `nama_barang`, `jenis_barang`, `stok`, `satuan`) VALUES
(1, 'Kertas A4 80gr', 'Habis pakai', 50, 'Rim'),
(2, 'Proyektor EPSON', 'Pinjaman', 3, 'Unit');

CREATE TABLE IF NOT EXISTS `tb_pengambilan` (
  `id_pengambilan` int(11) NOT NULL AUTO_INCREMENT,
  `nama_staff` varchar(100) NOT NULL,
  `tanggal` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_pengambilan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tb_detail_pengambilan` (
  `id_detail` int(11) NOT NULL AUTO_INCREMENT,
  `id_pengambilan` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `id_pengambilan` (`id_pengambilan`),
  KEY `id_barang` (`id_barang`),
  CONSTRAINT `fk_detail_pengambilan` FOREIGN KEY (`id_pengambilan`) REFERENCES `tb_pengambilan` (`id_pengambilan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_detail_barang` FOREIGN KEY (`id_barang`) REFERENCES `tb_barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tb_peminjaman` (
  `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT,
  `id_detail` int(11) NOT NULL,
  `tanggal_pinjam` datetime NOT NULL DEFAULT current_timestamp(),
  `tanggal_kembali` datetime DEFAULT NULL,
  `status` enum('Dipinjam','Kembali') NOT NULL DEFAULT 'Dipinjam',
  PRIMARY KEY (`id_peminjaman`),
  KEY `id_detail` (`id_detail`),
  CONSTRAINT `fk_peminjaman_detail` FOREIGN KEY (`id_detail`) REFERENCES `tb_detail_pengambilan` (`id_detail`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;