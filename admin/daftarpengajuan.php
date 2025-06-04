<?php
include '../koneksi.php';

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

// Pencarian data
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_query = $search ? "WHERE nama_anggota LIKE '%$search%'" : '';

$total_query = "SELECT COUNT(*) AS total FROM pengajuan_pinjaman $search_query";
$total_result = mysqli_query($koneksi, $total_query);
$total_data = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_data / $limit);

$query = "SELECT * FROM pengajuan_pinjaman $search_query ORDER BY tanggal_pengajuan DESC LIMIT $start, $limit";
$result = mysqli_query($koneksi, $query);

// Export to Excel
if (isset($_POST['export_excel'])) {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=riwayat_pengajuan_pinjaman.xls");
    echo "No\tNIK Anggota\tNama Anggota\tDepartemen\tSaldo Tabungan\tNo HP\tLama Angsuran\tAngsuran\tJasa\tJumlah Total\tNama Jaminan\tNo Jaminan\tJaminan Atas Nama\tJumlah Pinjaman\tStatus\tTanggal Pengajuan\n";

    $export_query = "SELECT * FROM pengajuan_pinjaman $search_query ORDER BY tanggal_pengajuan DESC";
    $export_result = mysqli_query($koneksi, $export_query);
    $no = 1;
    while ($row = mysqli_fetch_assoc($export_result)) {
        echo $no++ . "\t" .
            $row['nik_anggota'] . "\t" .
            $row['nama_anggota'] . "\t" .
            $row['departemen'] . "\t" .
            $row['saldo_tabungan'] . "\t" .
            $row['no_hp'] . "\t" .
            $row['lama_angsuran'] . "\t" .
            $row['angsuran'] . "\t" .
            $row['jasa'] . "\t" .
            $row['jumlah_total'] . "\t" .
            $row['nama_jaminan'] . "\t" .
            $row['jaminan_no'] . "\t" .
            $row['jaminan_atas_nama'] . "\t" .
            $row['jumlah_pinjaman'] . "\t" .
            $row['status_pengajuan'] . "\t" .
            $row['tanggal_pengajuan'] . "\n";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pengajuan Pinjaman</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: rgb(249, 249, 249);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            text-decoration: none;
            padding: 6px 10px;
            border: 1px solid #ddd;
            margin: 0 3px;
            border-radius: 4px;
            color: #007BFF;
        }

        .pagination a.active {
            background-color: #007BFF;
            color: white;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #28a745;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
        }

        .btn-back:hover {
            background-color: #218838;
        }

        .btn-back i {
            font-size: 18px;
        }

        .btn-export {
            display: inline-block;
            background-color: #007BFF;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
            float: right;
        }

        .btn-export:hover {
            background-color: #0056b3;
        }

        .search-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-container input[type="text"] {
            padding: 8px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-container button {
            padding: 8px 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Tombol Kembali ke Dashboard -->
    <a href="dashboard.php" class="btn-back">
        <i class="fas fa-home"></i> Kembali ke Dashboard
    </a>

    <!-- Kolom Cari -->
    <div class="search-container">
        <form method="get" action="">
            <input type="text" name="search" placeholder="Cari Nama Anggota..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Cari</button>
        </form>

        <!-- Tombol Export ke Excel -->
        <form method="post" style="display: inline;">
            <button type="submit" name="export_excel" class="btn-export">
                <i class="fas fa-file-excel"></i> Export ke Excel
            </button>
        </form>
    </div>

    <h2>Riwayat Pengajuan Pinjaman</h2>

    <table>
        <thead>
        <tr>
            <th>No</th>
            <th>NIK Anggota</th>
            <th>Nama Anggota</th>
            <th>Departemen</th>
            <th>Saldo Tabungan</th>
            <th>No HP</th>
            <th>Lama Angsuran</th>
            <th>Angsuran</th>
            <th>Jasa</th>
            <th>Jumlah Total</th>
            <th>Nama Jaminan</th>
            <th>No Jaminan</th>
            <th>Jaminan Atas Nama</th>
            <th>Jumlah Pinjaman</th>
            <th>Status</th>
            <th>Tanggal Pengajuan</th>
        </tr>
        </thead>
        <tbody>
        <?php $no = $start + 1; while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nik_anggota']) ?></td>
                <td><?= htmlspecialchars($row['nama_anggota']) ?></td>
                <td><?= htmlspecialchars($row['departemen']) ?></td>
                <td>Rp <?= number_format($row['saldo_tabungan'], 0, ',', '.') ?></td>
                <td><?= htmlspecialchars($row['no_hp']) ?></td>
                <td><?= htmlspecialchars($row['lama_angsuran']) ?> bulan</td>
                <td>Rp <?= number_format($row['angsuran'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($row['jasa'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($row['jumlah_total'], 0, ',', '.') ?></td>
                <td><?= htmlspecialchars($row['nama_jaminan']) ?></td>
                <td><?= htmlspecialchars($row['jaminan_no']) ?></td>
                <td><?= htmlspecialchars($row['jaminan_atas_nama']) ?></td>
                <td>Rp <?= number_format($row['jumlah_pinjaman'], 0, ',', '.') ?></td>
                <td><?= htmlspecialchars($row['status_pengajuan']) ?></td>
                <td><?= date("d-m-Y", strtotime($row['tanggal_pengajuan'])) ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
            <a href="?page=<?= $i ?>&search=<?= htmlspecialchars($search) ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
</div>
</body>
</html>