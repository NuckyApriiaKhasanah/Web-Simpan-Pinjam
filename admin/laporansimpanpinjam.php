<?php
// Koneksi database
$koneksi = mysqli_connect("localhost", "root", "", "koperasi_db");

// Tambah data
if (isset($_POST['tambah'])) {
    $tanggal = $_POST['tanggal'];
    $nik_anggota = $_POST['nik_anggota'];
    $nama = $_POST['nama'];
    $wajib = $_POST['simpanan_wajib'];
    $sukarela = $_POST['simpanan_sukarela'];
    $jumlah = $wajib + $sukarela;
    $pinjaman = $_POST['pinjaman'];
    $angsuran = $_POST['angsuran'];
    $jasa = $_POST['jasa'];
    $sisa_pinjaman = $pinjaman - $angsuran;

    mysqli_query($koneksi, "INSERT INTO laporan_simpan_pinjam 
        (tanggal, nik_anggota, nama, simpanan_wajib, simpanan_sukarela, jumlah_simpanan, pinjaman, angsuran, jasa, sisa_pinjaman)
        VALUES ('$tanggal', '$nik_anggota', '$nama', '$wajib', '$sukarela', '$jumlah', '$pinjaman', '$angsuran', '$jasa', '$sisa_pinjaman')");

    header("Location: laporanSimpanPinjam.php");
    exit;
}

// Hapus data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM laporan_simpan_pinjam WHERE id=$id");
    header("Location: laporanSimpanPinjam.php");
    exit;
}

// Update data
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $tanggal = $_POST['tanggal'];
    $nik_anggota = $_POST['nik_anggota'];
    $nama = $_POST['nama'];
    $wajib = $_POST['simpanan_wajib'];
    $sukarela = $_POST['simpanan_sukarela'];
    $jumlah = $wajib + $sukarela;
    $pinjaman = $_POST['pinjaman'];
    $angsuran = $_POST['angsuran'];
    $jasa = $_POST['jasa'];
    $sisa_pinjaman = $pinjaman - $angsuran;

    mysqli_query($koneksi, "UPDATE laporan_simpan_pinjam SET 
        tanggal='$tanggal', 
        nik_anggota='$nik_anggota', 
        nama='$nama', 
        simpanan_wajib='$wajib', 
        simpanan_sukarela='$sukarela', 
        jumlah_simpanan='$jumlah', 
        pinjaman='$pinjaman', 
        angsuran='$angsuran', 
        jasa='$jasa', 
        sisa_pinjaman='$sisa_pinjaman' 
        WHERE id=$id");
    
    header("Location: laporanSimpanPinjam.php");
    exit;
}

// Hitung total simpanan dan pinjaman
$query_total = "SELECT 
    SUM(simpanan_wajib + simpanan_sukarela) as total_simpanan,
    SUM(pinjaman) as total_pinjaman,
    SUM(sisa_pinjaman) as total_sisa_pinjaman
    FROM laporan_simpan_pinjam";

if (!empty($search)) {
    $query_total .= " WHERE nama LIKE '%$search%' OR nik_anggota LIKE '%$search%'";
}

$result_total = mysqli_query($koneksi, $query_total);
$total = mysqli_fetch_assoc($result_total);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Simpan Pinjam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .table-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .btn-action {
            margin: 0 2px;
        }
        .summary-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .summary-box {
            flex: 1;
            padding: 15px;
            border-radius: 5px;
            color: white;
            text-align: center;
        }
        .total-simpanan {
            background-color: #28a745;
            margin-right: 10px;
        }
        .total-pinjaman {
            background-color: #dc3545;
            margin-left: 10px;
        }
        .summary-title {
            font-size: 16px;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body class="container py-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h3 class="mb-0">Laporan Simpan Pinjam</h3>
                <div>
                    <a href="dashboard.php" class="btn btn-light me-2">
                        <i class="fas fa-home"></i> Kembali ke Dashboard
                    </a>
                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#tambahModal">
                        <i class="fas fa-plus"></i> Tambah Data
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Tombol Export ke Excel -->
            <a href="?export=excel" class="btn btn-success mb-3">
                <i class="fas fa-file-excel"></i> Export ke Excel
            </a>

            <!-- Form Pencarian -->
            <form method="GET" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari Nama atau NIK Anggota..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </form>

            <!-- Summary Boxes -->
            <div class="summary-container">
                <div class="summary-box total-simpanan">
                    <div class="summary-title">TOTAL SIMPANAN</div>
                    <div class="summary-value">Rp <?= number_format($total['total_simpanan'] ?? 0, 0, ',', '.') ?></div>
                </div>
                <div class="summary-box total-pinjaman">
                    <div class="summary-title">TOTAL PINJAMAN</div>
                    <div class="summary-value">Rp <?= number_format($total['total_pinjaman'] ?? 0, 0, ',', '.') ?></div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-header">
                        <tr>
                            <th rowspan="2">TANGGAL</th>
                            <th rowspan="2">NIK ANGGOTA</th>
                            <th rowspan="2">NAMA</th>
                            <th colspan="3">SIMPANAN</th>
                            <th rowspan="2">TOTAL PINJAMAN</th>
                            <th colspan="2">PINJAMAN</th>
                            <th rowspan="2">SISA PINJAMAN</th>
                            <th rowspan="2">AKSI</th>
                        </tr>
                        <tr>
                            <th>WAJIB</th>
                            <th>SUKARELA</th>
                            <th>JUMLAH</th>
                            <th>ANGSURAN</th>
                            <th>JASA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Logika Pencarian
                        $search = isset($_GET['search']) ? $_GET['search'] : '';
                        $query = "SELECT * FROM laporan_simpan_pinjam";
                        if (!empty($search)) {
                            $query .= " WHERE nama LIKE '%$search%' OR nik_anggota LIKE '%$search%'";
                        }
                        $query .= " ORDER BY tanggal DESC";
                        $data = mysqli_query($koneksi, $query);

                        while ($d = mysqli_fetch_array($data)) {
                        ?>
                        <tr>
                            <td><?= date('d-m-Y', strtotime($d['tanggal'])) ?></td>
                            <td><?= htmlspecialchars($d['nik_anggota']) ?></td>
                            <td><?= htmlspecialchars($d['nama']) ?></td>
                            <td class="text-end"><?= number_format($d['simpanan_wajib'], 0, ',', '.') ?></td>
                            <td class="text-end"><?= number_format($d['simpanan_sukarela'], 0, ',', '.') ?></td>
                            <td class="text-end"><?= number_format($d['jumlah_simpanan'], 0, ',', '.') ?></td>
                            <td class="text-end"><?= number_format($d['pinjaman'], 0, ',', '.') ?></td>
                            <td class="text-end"><?= number_format($d['angsuran'], 0, ',', '.') ?></td>
                            <td class="text-end"><?= number_format($d['jasa'], 0, ',', '.') ?></td>
                            <td class="text-end"><?= number_format($d['sisa_pinjaman'], 0, ',', '.') ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-action" data-bs-toggle="modal" data-bs-target="#editModal<?= $d['id'] ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="?hapus=<?= $d['id'] ?>" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Yakin hapus data ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editModal<?= $d['id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST">
                                        <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Data Simpan Pinjam</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Tanggal</label>
                                                <input type="date" name="tanggal" class="form-control" value="<?= $d['tanggal'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>NIK Anggota</label>
                                                <input type="text" name="nik_anggota" class="form-control" value="<?= $d['nik_anggota'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Nama</label>
                                                <input type="text" name="nama" class="form-control" value="<?= $d['nama'] ?>" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label>Simpanan Wajib</label>
                                                    <input type="number" name="simpanan_wajib" class="form-control" value="<?= $d['simpanan_wajib'] ?>" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label>Simpanan Sukarela</label>
                                                    <input type="number" name="simpanan_sukarela" class="form-control" value="<?= $d['simpanan_sukarela'] ?>" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label>Total Pinjaman</label>
                                                <input type="number" name="pinjaman" class="form-control" value="<?= $d['pinjaman'] ?>" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label>Angsuran</label>
                                                    <input type="number" name="angsuran" class="form-control" value="<?= $d['angsuran'] ?>" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label>Jasa</label>
                                                    <input type="number" name="jasa" class="form-control" value="<?= $d['jasa'] ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Simpan Pinjam</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>NIK Anggota</label>
                            <input type="text" name="nik_anggota" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Simpanan Wajib</label>
                                <input type="number" name="simpanan_wajib" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Simpanan Sukarela</label>
                                <input type="number" name="simpanan_sukarela" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Total Pinjaman</label>
                            <input type="number" name="pinjaman" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Angsuran</label>
                                <input type="number" name="angsuran" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Jasa</label>
                                <input type="number" name="jasa" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" name="tambah" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>