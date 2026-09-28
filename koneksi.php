<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_consumable"; // Ubah menjadi 'db_consumable'

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>