<?php
include "koneksi.php";
$tanggal = $_GET['tanggal'] ?? '';
$cari = $_GET['cari'] ?? '';

$where = [];
if($tanggal) $where[] = "t.tanggal = '$tanggal'";
if($cari){
  $safe = mysqli_real_escape_string($koneksi, $cari);
  $where[] = "p.nama_pembeli LIKE '%$safe%'";
}
$filter = $where ? 'WHERE '.implode(' AND ', $where) : '';

$sql = "SELECT t.*, p.nama_pembeli, b.nama_barang 
        FROM transaksi t
        JOIN pembeli p ON p.id_pembeli=t.id_pembeli
        JOIN barang b ON b.id_barang=t.id_barang
        $filter ORDER BY t.id_transaksi DESC";
$result = mysqli_query($koneksi, $sql);

// Ambil agregat
$totalTransaksi = mysqli_num_rows($result);
$sum = mysqli_query($koneksi, "SELECT SUM(total_harga) AS total FROM transaksi ".($where ? implode(' AND ',$where):''));
$pendapatan = mysqli_fetch_assoc($sum)['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan Transaksi | Toko Dian</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
  body {
    font-family: 'Poppins', sans-serif;
    background: #fff;
    color: #333;
    padding: 40px;
  }
  h2 {
    text-align: center;
    font-weight: 700;
    margin-bottom: 10px;
  }
  h5 { text-align:center; margin-bottom:30px; color:#555; }
  table {
    border-collapse: collapse;
    width: 100%;
    margin-top: 20px;
  }
  th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
  }
  th {
    background: linear-gradient(90deg, #4e9fff, #5dd9ff);
    color: white;
  }
  tr:nth-child(even){ background-color: #f9f9f9; }
  .summary {
    margin-top: 25px;
    font-weight: 600;
  }
  @media print {
    button { display:none; }
  }
</style>
</head>
<body onload="window.print()">

<h2>🧾 Laporan Transaksi</h2>
<h5>Toko Dian — <?= date('d F Y') ?></h5>

<table>
  <thead>
    <tr>
      <th>ID Transaksi</th>
      <th>Pembeli</th>
      <th>Barang</th>
      <th>Jumlah</th>
      <th>Total Harga</th>
      <th>Tanggal</th>
    </tr>
  </thead>
  <tbody>
    <?php if(mysqli_num_rows($result)==0): ?>
      <tr><td colspan="6">Tidak ada data transaksi</td></tr>
    <?php else: 
      mysqli_data_seek($result, 0); // reset pointer
      while($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td><?= $row['id_transaksi'] ?></td>
        <td><?= $row['nama_pembeli'] ?></td>
        <td><?= $row['nama_barang'] ?></td>
        <td><?= $row['jumlah'] ?></td>
        <td>Rp <?= number_format($row['total_harga'],0,',','.') ?></td>
        <td><?= $row['tanggal'] ?></td>
      </tr>
    <?php endwhile; endif; ?>
  </tbody>
</table>

<div class="summary">
  <p>Total Transaksi: <?= $totalTransaksi ?> transaksi</p>
  <p>Total Pendapatan: <b>Rp <?= number_format($pendapatan,0,',','.') ?></b></p>
</div>

<div class="text-center mt-4">
  <button onclick="window.print()" class="btn btn-primary px-4 py-2 rounded-pill">🖨 Cetak</button>
  <a href="transaksi.php" class="btn btn-secondary px-4 py-2 rounded-pill">⬅ Kembali</a>
</div>

</body>
</html>
