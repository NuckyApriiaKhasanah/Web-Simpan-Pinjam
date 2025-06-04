<?php
// Setel koneksi ke database
$host = "localhost"; // Sesuaikan dengan host database Anda
$user = "root";      // Sesuaikan dengan username MySQL Anda
$pass = "";          // Sesuaikan dengan password MySQL Anda
$dbname = "koperasi_db"; // Ganti dengan nama database Anda

// Membuat koneksi
$koneksi = mysqli_connect($host, $user, $pass, $dbname);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
