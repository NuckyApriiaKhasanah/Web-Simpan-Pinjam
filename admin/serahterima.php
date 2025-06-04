<?php
session_start();
include '../koneksi.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik_anggota = $_POST['nik_anggota'];
    $nama_anggota = $_POST['nama_anggota'];
    $dept = $_POST['dept'];
    $saldo_tabungan = $_POST['saldo_tabungan'];
    $no_hp = $_POST['no_hp'];
    $jumlah_pinjaman = $_POST['jumlah_pinjaman'];
    $jangka_waktu = $_POST['jangka_waktu'];
    $angsuran = $_POST['angsuran'];
    $jasa = $_POST['jasa'];
    $tanggal = $_POST['tanggal'];

    $total = floatval($angsuran) + floatval($jasa);

    $query = "INSERT INTO serah_terima (nik_anggota, nama_anggota, dept, saldo_tabungan, no_hp, jumlah_pinjaman, jangka_waktu, angsuran, jasa, total, tanggal_serah_terima) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("ssssssssdds", $nik_anggota, $nama_anggota, $dept, $saldo_tabungan, $no_hp, $jumlah_pinjaman, $jangka_waktu, $angsuran, $jasa, $total, $tanggal);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil disimpan dan dikirim ke anggota.');</script>";
        echo "<script>window.location.href = 'riwayatserahterima.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data. Silakan coba lagi.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Form Serah Terima Pinjaman</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    @media print {
      body * {
        visibility: hidden;
      }
      #form-print, #form-print * {
        visibility: visible;
      }
      #form-print {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
      }
    }
    
    /* Modal Preview */
    .modal {
      display: none;
      position: fixed;
      z-index: 100;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.4);
    }
    
    .modal-content {
      background-color: #fefefe;
      margin: 5% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      max-width: 800px;
    }
    
    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }
    
    .close:hover {
      color: black;
    }
  </style>
  <script>
    function updateNamaPenerima() {
      const namaAnggota = document.querySelector('input[name="nama_anggota"]').value;
      document.getElementById('nama-penerima').textContent = namaAnggota ? `(${namaAnggota})` : '(Anggota)';
      document.getElementById('preview-nama-penerima').textContent = namaAnggota ? `(${namaAnggota})` : '(Anggota)';
    }

    function hitungAngsuranDanJasa() {
      const jumlahPinjaman = parseFloat(document.querySelector('input[name="jumlah_pinjaman"]').value) || 0;
      const jangkaWaktu = parseInt(document.querySelector('input[name="jangka_waktu"]').value) || 1;
      
      if (jumlahPinjaman > 0 && jangkaWaktu > 0) {
        // Hitung angsuran (jumlah pinjaman dibagi jangka waktu)
        const angsuran = jumlahPinjaman / jangkaWaktu;
        document.querySelector('input[name="angsuran"]').value = angsuran.toFixed(2);
        
        // Hitung jasa (10% dari total pinjaman dibagi jangka waktu)
        const jasa = (jumlahPinjaman * 0.1) / jangkaWaktu;
        document.querySelector('input[name="jasa"]').value = jasa.toFixed(2);
        
        // Hitung total
        hitungJumlah();
      }
    }

    function hitungJumlah() {
      const angsuran = parseFloat(document.querySelector('input[name="angsuran"]').value) || 0;
      const jasa = parseFloat(document.querySelector('input[name="jasa"]').value) || 0;
      const jumlah = angsuran + jasa;
      document.querySelector('input[name="jumlah"]').value = jumlah.toFixed(2);
    }

    function previewForm() {
      // Ambil semua nilai dari form
      const nikAnggota = document.querySelector('input[name="nik_anggota"]').value;
      const namaAnggota = document.querySelector('input[name="nama_anggota"]').value;
      const dept = document.querySelector('select[name="dept"]').value;
      const saldoTabungan = document.querySelector('input[name="saldo_tabungan"]').value;
      const noHp = document.querySelector('input[name="no_hp"]').value;
      const jumlahPinjaman = document.querySelector('input[name="jumlah_pinjaman"]').value;
      const jangkaWaktu = document.querySelector('input[name="jangka_waktu"]').value;
      const angsuran = document.querySelector('input[name="angsuran"]').value;
      const jasa = document.querySelector('input[name="jasa"]').value;
      const jumlah = document.querySelector('input[name="jumlah"]').value;
      const tanggal = document.querySelector('input[name="tanggal"]').value;
      
      // Format tanggal untuk ditampilkan
      const formattedDate = formatTanggal(tanggal);
      
      // Tampilkan data di modal preview
      document.getElementById('preview-no-anggota').textContent = noAnggota;
      document.getElementById('preview-nama-anggota').textContent = namaAnggota;
      document.getElementById('preview-dept').textContent = dept;
      document.getElementById('preview-saldo-tabungan').textContent = saldoTabungan;
      document.getElementById('preview-no-hp').textContent = noHp;
      document.getElementById('preview-jumlah-pinjaman').textContent = jumlahPinjaman;
      document.getElementById('preview-jangka-waktu').textContent = jangkaWaktu;
      document.getElementById('preview-angsuran').textContent = angsuran;
      document.getElementById('preview-jasa').textContent = jasa;
      document.getElementById('preview-jumlah').textContent = jumlah;
      document.getElementById('preview-tanggal').textContent = formattedDate;
      
      // Tampilkan modal
      document.getElementById('previewModal').style.display = 'block';
    }
    
    function formatTanggal(tanggal) {
      if (!tanggal) return '';
      
      const options = { day: 'numeric', month: 'long', year: 'numeric' };
      const date = new Date(tanggal);
      return date.toLocaleDateString('id-ID', options);
    }
    
    function closePreview() {
      document.getElementById('previewModal').style.display = 'none';
    }
    
    function cetakForm() {
      window.print();
    }
    
    // Tutup modal ketika klik di luar area modal
    window.onclick = function(event) {
      const modal = document.getElementById('previewModal');
      if (event.target == modal) {
        modal.style.display = 'none';
      }
    }
  </script>
</head>
<body class="bg-gray-100 p-8">

  <div id="form-print" class="max-w-4xl mx-auto bg-white p-8 border border-black text-sm leading-relaxed font-serif">
    <form id="pinjaman-form" action="" method="post">
      <!-- Header -->
      <div class="flex items-center justify-between mb-4">
        <img src="img/kino.jpg" alt="Logo" class="w-150 h-20 object-contain">
        <div class="text-center flex-1 -ml-20">
          <p class="font-bold text-base leading-tight">Lembaga Bipartit<br>P2K3</p>
          <p class="text-sm">Pandu Potensi Untuk Kesejahteraan Karyawan Kino</p>
        </div>
      </div>

      <hr class="my-2 border-black">

      <!-- Judul -->
      <div class="text-center mb-4">
        <p class="font-bold">KOPERASI SIMPAN PINJAM P2K3</p>
        <p class="font-semibold underline">Serah Terima Pinjaman</p>
      </div>

      <!-- Data Anggota -->
      <div class="space-y-2">
        <p>Saya Yang Bertanda Tangan Dibawah Ini :</p>
        <label>NIK. Anggota:
          <input type="text" name="nik_anggota" class="border-b border-black w-1/2 ml-2 outline-none" required />
        </label><br />
        <label>Nama Anggota:
          <input type="text" name="nama_anggota" oninput="updateNamaPenerima()" class="border-b border-black w-1/2 ml-2 outline-none" required />
        </label><br />
        <label>Dept/Bagian:
          <select name="dept" class="border-b border-black w-1/2 ml-2 outline-none" required>
            <option value="">-- Pilih Departemen --</option>
            <option value="Keuangan">Departemen Keuangan</option>
            <option value="SDM">Departemen SDM</option>
            <option value="Operasional">Departemen Operasional</option>
            <option value="IT">Departemen IT</option>
            <option value="Pemasaran">Departemen Pemasaran</option>
          </select>
        </label><br />
        <label>Saldo Tabungan:
          <input type="text" name="saldo_tabungan" class="border-b border-black w-1/2 ml-2 outline-none" />
        </label><br />
        <label>No. HP:
          <input type="text" name="no_hp" class="border-b border-black w-1/2 ml-2 outline-none" />
        </label>
      </div>

      <!-- Info Pinjaman -->
      <div class="mt-6 space-y-2">
        <label>Telah menerima Pinjaman Sebesar Rp.
          <input type="text" name="jumlah_pinjaman" class="border-b border-black w-1/3 ml-2 outline-none" onchange="hitungAngsuranDanJasa()" />
        </label><br />
        <label class="ml-4">Diangsur:
          <input type="text" name="jangka_waktu" class="border-b border-black w-16 mx-2 outline-none" onchange="hitungAngsuranDanJasa()" /> Bulan
        </label><br />
        <label class="ml-4">Dengan Angsuran: Rp.
          <input type="text" name="angsuran" class="border-b border-black w-1/3 ml-2 outline-none" readonly />
        </label><br />
        <label class="ml-4">Jasa: Rp.
          <input type="text" name="jasa" class="border-b border-black w-1/3 ml-2 outline-none" readonly />
        </label><br />
        <label class="ml-4">Jumlah: Rp.
          <input type="text" name="jumlah" class="border-b border-black w-1/3 ml-2 outline-none" readonly /> /bulan
        </label>
      </div>

      <!-- Tanggal & TTD -->
      <div class="mt-6">
        <label>Sukabumi,
          <input type="date" name="tanggal" class="border-b border-black w-1/4 ml-2 outline-none" />
        </label>
      </div>

      <div class="grid grid-cols-2 mt-6 text-center">
        <div>
          <p>Diserahkan Oleh,</p>
          <div class="h-16"></div>
          <p class="mt-2">(Yuliana)</p>
        </div>
        <div>
          <p>Diterima Oleh,</p>
          <div class="h-16"></div>
          <p id="nama-penerima">(Anggota)</p>
        </div>
      </div>

      <!-- Catatan -->
      <div class="mt-6 text-justify text-xs italic">
        Apabila dalam jangka waktu 3 (tiga bulan) berturut-turut tidak membayar angsuran dan jasa maka akan dipotong langsung dari tabungan.
      </div>

      <!-- Tombol Aksi -->
      <div class="text-center mt-8 space-x-4">
        <button type="submit" class="bg-purple-500 text-white py-2 px-4 rounded hover:bg-purple-600">
          Simpan & Kirim ke Anggota
        </button>
        <button type="button" onclick="cetakForm()" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
          Cetak
        </button>
      </div>
    </form>
  </div>

  <!-- Tombol Kembali -->
  <div class="mt-6 text-center">
    <a href="dashboard.php" class="inline-flex items-center bg-blue-700 text-white py-2 px-4 rounded hover:bg-blue-800">
        <i class="fas fa-home mr-2"></i> Kembali ke Dashboard
    </a>
  </div>

  <!-- Modal Preview -->
  <div id="previewModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closePreview()">&times;</span>
      <h2 class="text-xl font-bold mb-4">Preview Form Serah Terima Pinjaman</h2>
      
      <div class="border border-gray-300 p-4">
        <div class="flex items-center justify-between mb-4">
          <img src="img/kino.jpg" alt="Logo" class="w-150 h-20 object-contain">
          <div class="text-center flex-1 -ml-20">
            <p class="font-bold text-base leading-tight">Lembaga Bipartit<br>P2K3</p>
            <p class="text-sm">Pandu Potensi Untuk Kesejahteraan Karyawan Kino</p>
          </div>
        </div>

        <hr class="my-2 border-black">

        <div class="text-center mb-4">
          <p class="font-bold">KOPERASI SIMPAN PINJAM P2K3</p>
          <p class="font-semibold underline">Serah Terima Pinjaman</p>
        </div>

        <div class="space-y-2">
          <p>Saya Yang Bertanda Tangan Dibawah Ini :</p>
          <p>No. Anggota: <span id="preview-no-anggota" class="font-semibold"></span></p>
          <p>Nama Anggota: <span id="preview-nama-anggota" class="font-semibold"></span></p>
          <p>Dept/Bagian: <span id="preview-dept" class="font-semibold"></span></p>
          <p>Saldo Tabungan: <span id="preview-saldo-tabungan" class="font-semibold"></span></p>
          <p>No. HP: <span id="preview-no-hp" class="font-semibold"></span></p>
        </div>

        <div class="mt-6 space-y-2">
          <p>Telah menerima Pinjaman Sebesar Rp. <span id="preview-jumlah-pinjaman" class="font-semibold"></span></p>
          <p class="ml-4">Diangsur: <span id="preview-jangka-waktu" class="font-semibold"></span> Bulan</p>
          <p class="ml-4">Dengan Angsuran: Rp. <span id="preview-angsuran" class="font-semibold"></span></p>
          <p class="ml-4">Jasa: Rp. <span id="preview-jasa" class="font-semibold"></span></p>
          <p class="ml-4">Jumlah: Rp. <span id="preview-jumlah" class="font-semibold"></span> /bulan</p>
        </div>

        <div class="mt-6">
          <p>Sukabumi, <span id="preview-tanggal" class="font-semibold"></span></p>
        </div>

        <div class="grid grid-cols-2 mt-6 text-center">
          <div>
            <p>Diserahkan Oleh,</p>
            <div class="h-16"></div>
            <p class="mt-2">(Yuliana)</p>
          </div>
          <div>
            <p>Diterima Oleh,</p>
            <div class="h-16"></div>
            <p id="preview-nama-penerima">(Anggota)</p>
          </div>
        </div>

        <div class="mt-6 text-justify text-xs italic">
          Apabila dalam jangka waktu 3 (tiga bulan) berturut-turut tidak membayar angsuran dan jasa maka akan dipotong langsung dari tabungan.
        </div>
      </div>
      
      <div class="mt-6 text-center">
        <button onclick="cetakForm()" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
          Cetak Form
        </button>
      </div>
    </div>
  </div>

</body>
</html>