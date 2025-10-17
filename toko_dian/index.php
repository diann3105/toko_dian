<?php include "koneksi.php"; ?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Penjualan | Elektronik Dian</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
  body {
    font-family: 'Poppins', sans-serif;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    color: #333;
    background: linear-gradient(160deg, #e8f1ff 0%, #f9fbff 50%, #ffffff 100%); /* 🌈 Soft clean gradient */
    background-attachment: fixed; /* agar tetap stabil saat scroll */
  }

  /* Navbar */
  .app-navbar {
    backdrop-filter: saturate(180%) blur(8px);
    background: rgba(255,255,255,.85);
    border-bottom: 1px solid rgba(15,23,42,.06);
  }
  .nav-link { font-weight: 600; }
  .nav-link.active { color: #2563eb !important; }

  /* Dashboard Container */
  .dashboard {
    background: rgba(255, 255, 255, 0.85);
    border: 1px solid rgba(230,230,230,0.7);
    backdrop-filter: blur(10px);
    border-radius: 30px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    padding: 50px 60px;
    width: 90%;
    max-width: 1100px;
    text-align: center;
    margin: 60px auto;
  }

  .dashboard h1 {
    font-weight: 700;
    font-size: 2.4rem;
    margin-bottom: 10px;
    color: #1e293b;
  }

  .subtitle {
    font-size: 1.1rem;
    color: #475569;
    margin-bottom: 40px;
  }

  /* Statistik Card */
  .stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
  }

  .card-stat {
    background: rgba(255,255,255,0.9);
    border-radius: 18px;
    padding: 25px;
    transition: all 0.4s ease;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    border: 1px solid rgba(200,200,200,0.2);
  }

  .card-stat:hover {
    transform: translateY(-8px) scale(1.03);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  }

  .card-stat .icon {
    font-size: 3rem;
    margin-bottom: 10px;
  }

  .stat-title {
    font-weight: 600;
    font-size: 1.1rem;
    color: #64748b;
  }

  .stat-value {
    font-weight: 700;
    font-size: 1.7rem;
    color: #111;
  }

  /* Tombol Section */
  .btn-section {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 15px;
  }

  .btn-modern {
    border: none;
    border-radius: 50px;
    padding: 12px 28px;
    font-weight: 600;
    color: white;
    transition: all 0.3s ease;
    letter-spacing: 0.5px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .btn-modern:hover {
    transform: scale(1.08);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    color: white;
  }

  .btn-barang { background: linear-gradient(45deg, #4e9fff, #5dd9ff); color: #fff; }
  .btn-pembeli { background: linear-gradient(45deg, #4ad991, #64e1ab); color: #fff; }
  .btn-transaksi { background: linear-gradient(45deg, #ff7d7d, #ffa55b); color: #fff; }

  footer {
    margin-top: 50px;
    color: #888;
    font-size: 0.9rem;
  }
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg sticky-top app-navbar">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">
      <i class="bi bi-bag-check-fill me-1"></i>Zapstore
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#appNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="appNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="index.php"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="barang.php"><i class="bi bi-box-seam me-1"></i>Barang</a></li>
        <li class="nav-item"><a class="nav-link" href="pembeli.php"><i class="bi bi-people me-1"></i>Pembeli</a></li>
        <li class="nav-item"><a class="nav-link" href="transaksi.php"><i class="bi bi-receipt me-1"></i>Transaksi</a></li>
      </ul>
    </div>
  </div>
</nav>

<?php
  $transaksi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM transaksi"));
  $pendapatan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(total_harga) AS total FROM transaksi"));
  $terlaris = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT b.nama_barang, COUNT(t.id_barang) AS jumlah
    FROM transaksi t JOIN barang b ON t.id_barang=b.id_barang
    GROUP BY b.id_barang ORDER BY jumlah DESC LIMIT 1
  "));
?>

<div class="dashboard">
  <h1>📊 Penjualan Elektronik Zapstore 📊</h1>
  <p class="subtitle">Dashboard Penjualan <strong>Zapstore</strong> — pantau data penjualan dengan profesional.</p>

  <div class="stats">
    <div class="card-stat">
      <div class="icon text-primary"><i class="bi bi-receipt"></i></div>
      <div class="stat-title">Total Transaksi</div>
      <div class="stat-value"><?= $transaksi['total'] ?? 0; ?></div>
    </div>

    <div class="card-stat">
      <div class="icon text-success"><i class="bi bi-cash-stack"></i></div>
      <div class="stat-title">Total Pendapatan</div>
      <div class="stat-value">Rp <?= number_format($pendapatan['total'] ?? 0, 0, ',', '.'); ?></div>
    </div>

    <div class="card-stat">
      <div class="icon text-warning"><i class="bi bi-award"></i></div>
      <div class="stat-title">Barang Terlaris</div>
      <div class="stat-value"><?= $terlaris['nama_barang'] ?? '-'; ?></div>
    </div>
  </div>

  <div class="btn-section">
    <a href="barang.php" class="btn-modern btn-barang"><i class="bi bi-box"></i> Kelola Barang</a>
    <a href="pembeli.php" class="btn-modern btn-pembeli"><i class="bi bi-person-circle"></i> Kelola Pembeli</a>
    <a href="transaksi.php" class="btn-modern btn-transaksi"><i class="bi bi-cart-check"></i> Kelola Transaksi</a>
  </div>

  <footer>
    © <?= date("Y"); ?> <strong>Tuti Dian Kusumawati</strong> | Pemrograman Basis Data
  </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
