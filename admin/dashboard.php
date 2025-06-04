<?php
session_start();

// Ganti ini dengan $_SESSION['nama'] jika login sudah berjalan
$nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : "Admin";

// Simulasi jumlah pengajuan pinjaman baru (ganti dengan query database Anda nanti)
$jumlah_pengajuan_masuk = 3;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin Koperasi</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* CSS yang sama dengan sebelumnya */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }

    body {
      display: flex;
      min-height: 100vh;
      overflow: hidden;
      background-color: #f4f7fb;
    }

    .sidebar {
      width: 260px;
      background: linear-gradient(to bottom right, #0a1930, #1e2e4e);
      color: white;
      padding: 20px;
      position: fixed;
      height: 100vh;
      box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    }

    .sidebar h2 {
      text-align: center;
      font-size: 18px;
      margin-bottom: 30px;
      background-color: rgba(255,255,255,0.1);
      padding: 15px;
      border-radius: 8px;
    }

    .sidebar ul {
      list-style: none;
    }

    .sidebar ul li {
      margin-bottom: 12px;
    }

    .sidebar ul li a {
      text-decoration: none;
      color: #cceeff;
      display: block;
      padding: 12px;
      border-radius: 8px;
      background-color: rgba(255,255,255,0.05);
      transition: background-color 0.3s ease;
    }

    .sidebar ul li a:hover {
      background-color: rgba(255,255,255,0.15);
    }

    .logout a {
      margin-top: 30px;
      display: block;
      background-color: #ff4d4d;
      padding: 12px;
      border-radius: 8px;
      color: white;
      text-align: center;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }

    .logout a:hover {
      background-color: #e63e3e;
    }

    .content {
      margin-left: 260px;
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      position: relative;
      padding: 40px;
      background: url('img/kinockb.jpg') center center / cover no-repeat;
    }

    .overlay {
      position: absolute;
      inset: 0;
      background-color: rgba(0, 0, 0, 0.5);
    }

    .content-text {
      position: relative;
      color: white;
      z-index: 1;
      text-align: center;
      max-width: 700px;
    }

    .content-text h1 {
      font-size: 34px;
      margin-bottom: 20px;
      font-weight: 600;
    }

    .content-text .quote {
      background-color: rgba(255, 255, 255, 0.2);
      padding: 18px 25px;
      border-radius: 12px;
      font-size: 16px;
      line-height: 1.7;
    }

    footer {
      position: absolute;
      bottom: 15px;
      left: 50%;
      transform: translateX(-50%);
      color: white;
      z-index: 1;
      font-size: 14px;
    }

    @media (max-width: 768px) {
      .sidebar {
        display: none;
      }

      .content {
        margin-left: 0;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

  <div class="sidebar">
    <h2>Selamat Datang,<br><?php echo htmlspecialchars($nama); ?>!</h2>
    <ul>
      <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="daftarpengajuan.php">Daftar Pengajuan Pinjaman</a></li>
      <li><a href="dataanggota.php">Data Anggota</a></li>
      <li><a href="serahterima.php">Serah Terima Pinjaman</a></li>
      <li><a href="laporansimpanpinjam.php">Laporan Simpan Pinjam</a></li>
    </ul>
    <div class="logout">
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <div class="content">
    <div class="overlay"></div>
    <div class="content-text">
      <h1>Aplikasi Simpan Pinjam Koperasi P2K3<br>PT Kino Indonesia Tbk</h1>
      <div class="quote">
        "Kelola pengajuan, validasi anggota, dan pantau transaksi secara efisien untuk membangun koperasi yang amanah dan transparan."
      </div>
    </div>
    <footer>© 2025 Koperasi P2K3 - Kino Indonesia Tbk</footer>
  </div>

</body>
</html>