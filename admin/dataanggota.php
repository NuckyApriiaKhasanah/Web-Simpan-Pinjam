<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "koperasi_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Pagination setup
$limit = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Halaman yang sedang ditampilkan
$offset = ($page - 1) * $limit; // Offset untuk query SQL

// Pencarian
$search = isset($_POST['search_input']) ? $conn->real_escape_string($_POST['search_input']) : '';
$sql = "SELECT nama, email, no_telepon, alamat, tanggal_bergabung, departemen 
        FROM data_anggota_koperasi
        WHERE nama LIKE '%$search%' OR email LIKE '%$search%' 
        ORDER BY nama ASC
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Total data anggota
$totalQuery = "SELECT COUNT(*) as total FROM data_anggota_koperasi WHERE nama LIKE '%$search%' OR email LIKE '%$search%'";
$totalResult = $conn->query($totalQuery);
$totalData = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalData / $limit);

// Export Excel
if (isset($_GET['export']) && $_GET['export'] == 'excel') {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="data_anggota.xls"');
    echo "Nama\tEmail\tNo Telepon\tAlamat\tTanggal Bergabung\tDepartemen\n";
    while ($row = $result->fetch_assoc()) {
        echo "{$row['nama']}\t{$row['email']}\t{$row['no_telepon']}\t{$row['alamat']}\t{$row['tanggal_bergabung']}\t{$row['departemen']}\n";
    }
    exit;
}

$departemenOptions = ['Keuangan', 'Sumber Daya Manusia', 'Operasional', 'Pemasaran', 'IT'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Anggota Koperasi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        /* General Styling */
        body {
            font-family: 'Arial', sans-serif;
        }

        /* Image Background */
        .bg-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1; /* Let the content appear above the image */
            object-fit: cover; /* Ensures image covers the entire background */
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background-color: rgba(0, 123, 255, 0.7); /* Semi-transparent background */
            border-radius: 10px;
            color: white;
        }

        .header h2 {
            font-size: 28px;
            font-weight: bold;
        }

        .header img {
            width: 50px;
            margin-bottom: 10px;
        }

        /* Styling for the "Kembali ke Beranda" button */
        .back-button {
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px 20px;
            border-radius: 30px;
            background-color: #28a745;
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .back-button i {
            margin-right: 8px;
        }

        .back-button:hover {
            background-color: #218838;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        /* Search Form */
        .card-search {
            border: none;
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-search input,
        .card-search button,
        .card-search a {
            border-radius: 25px;
        }

        .card-search button {
            font-size: 16px;
        }

        /* Table Styling */
        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .table th, .table td {
            vertical-align: middle;
            padding: 12px;
            text-align: center;
        }

        .table th {
            background-color: #007bff;
            color: white;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
            transition: background-color 0.3s ease;
        }

        .table-bordered {
            border: none;
        }

        /* Pagination Styling */
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .table th, .table td {
                font-size: 14px;
            }
        }
    </style>
</head>
<body class="container py-4">

    <!-- Background Image -->
    <img src="img/kinockb.jpg" alt="Background Image" class="bg-img">

    <!-- Header Section with Logo and Title -->
    <div class="header">
        <h2>Data Anggota Koperasi P2K3 PT Kino Indonesia Tbk</h2>
    </div>

    <!-- Button Kembali ke Beranda -->
    <a href="dashboard.php" class="btn back-button">
        <i class="bi bi-house-door"></i> Kembali ke Dashboard
    </a>

    <!-- Search and Export Section -->
    <div class="card card-search">
        <form method="POST" class="d-flex">
            <input type="text" name="search_input" class="form-control me-2" placeholder="Cari Nama/Email..." value="<?= $search ?>" required>
            <button type="submit" class="btn btn-secondary me-2">
                <i class="bi bi-search"></i> Cari
            </button>
            <a href="?export=excel" class="btn btn-success">
                <i class="bi bi-file-earmark-spreadsheet"></i> Export Excel
            </a>
        </form>
    </div>

    <!-- Tabel Anggota -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No Telepon</th>
                    <th>Alamat</th>
                    <th>Departemen</th>
                    <th>Tanggal Bergabung</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['nama'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['no_telepon'] ?></td>
                        <td><?= $row['alamat'] ?></td>
                        <td><?= $row['departemen'] ?></td>
                        <td><?= $row['tanggal_bergabung'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=1&search_input=<?= $search ?>" aria-label="First">
                    <span aria-hidden="true">&laquo;&laquo;</span>
                </a>
            </li>
            <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page - 1 ?>&search_input=<?= $search ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&search_input=<?= $search ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= ($page == $totalPages) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page + 1 ?>&search_input=<?= $search ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo
