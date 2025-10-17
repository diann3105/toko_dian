<?php include "koneksi.php"; ?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Barang | Toko Dian</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
  :root{
    --brand-1:#2563eb; /* Indigo-600 */
    --brand-2:#60a5fa; /* Blue-400 */
    --ink:#0f172a;     /* Slate-900 */
    --muted:#64748b;   /* Slate-500 */
    --bg:#f8fafc;      /* Slate-50 */
    --card:#ffffff;
    --radius:14px;     /* less rounded */
    --radius-sm:10px;
  }
  *{box-sizing:border-box}
  body{
    background:var(--bg);
    color:var(--ink);
    font-family:'Poppins',system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,'Helvetica Neue',Arial,sans-serif;
  }
  /* Navbar */
  .app-navbar{
    backdrop-filter:saturate(180%) blur(8px);
    background:rgba(255,255,255,.85);
    border-bottom:1px solid rgba(15,23,42,.06);
  }
  .nav-link{
    font-weight:600;
  }
  .nav-link.active{
    color:var(--brand-1)!important;
  }
  /* Container card */
  .container-box{
    background:var(--card);
    border:1px solid rgba(15,23,42,.06);
    border-radius:var(--radius);
    box-shadow:0 8px 24px rgba(2,6,23,.06);
    padding:28px;
  }
  h2{
    font-weight:700;
    letter-spacing:.2px;
  }
  /* Buttons */
  .btn-modern{
    border:none;
    border-radius:var(--radius-sm);
    font-weight:600;
  }
  .btn-add{
    background:linear-gradient(90deg,var(--brand-1),var(--brand-2));
    color:#fff;
  }
  .btn-back{
    background:linear-gradient(90deg,#ef4444,#f59e0b);
    color:#fff;
  }
  .btn-modern:hover{opacity:.95;transform:translateY(-1px)}
  /* Table */
  .table-card{
    border-radius:var(--radius);
    overflow:hidden;
    border:1px solid rgba(15,23,42,.06);
    background:#fff;
  }
  .table thead th{
    background:linear-gradient(90deg,var(--brand-1),var(--brand-2));
    color:#fff;
    border-color:transparent!important;
    font-weight:600;
  }
  .table tbody tr:hover{background:#f1f5ff}
  /* Search full width one-line */
  .search-wrap {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}

/* Samakan tinggi semua elemen */
.search-wrap .form-control,
.search-wrap .btn {
  height: 46px;             /* tinggi seragam */
  border-radius: 10px;
  font-weight: 600;
}

/* Input pencarian */
.search-wrap .form-control {
  border: 1px solid rgba(100,116,139,.4);
  padding: 10px 14px;
  flex: 1; /* agar melebar otomatis */
}

/* Tombol Cari */
.search-wrap .btn-outline-primary {
  width: 46px;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Tombol Refresh */
.search-wrap .btn-outline-secondary {
  width: 46px;
  display: flex;
  align-items: center;
  justify-content: center;
}
  /* Badges */
  .badge-soft{
    background:#eef2ff;color:#3730a3;font-weight:600;border:1px solid #e0e7ff;
  }
  /* Footer hint */
  .page-hint{color:var(--muted);font-size:.9rem}
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
        <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="barang.php"><i class="bi bi-box-seam me-1"></i>Barang</a></li>
        <li class="nav-item"><a class="nav-link" href="pembeli.php"><i class="bi bi-people me-1"></i>Pembeli</a></li>
        <li class="nav-item"><a class="nav-link" href="transaksi.php"><i class="bi bi-receipt me-1"></i>Transaksi</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- CONTENT -->
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="m-0">📦 Data Barang <span class="badge badge-soft ms-2"></span></h2>
    <div class="d-none d-md-flex align-items-center page-hint">
    </div>
  </div>

  <div class="container-box mb-3">
    <!-- SEARCH: full width single row -->
    <form class="search-wrap mb-3" method="GET">
      <input type="text" name="cari" class="form-control flex-grow-1" placeholder="🔍 Cari ID atau Nama Barang..."
             value="<?= $_GET['cari'] ?? ''; ?>" autofocus>
      <button class="btn btn-outline-primary" type="submit" title="Cari"><i class="bi bi-search"></i></button>
      <a href="barang.php" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-repeat"></i></a>
    </form>

    <div class="d-flex flex-wrap gap-2 mb-3">
      <button class="btn-modern btn-add px-3 py-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-circle me-1"></i>Tambah Barang
      </button>
    </div>

    <!-- TABLE -->
    <div class="table-responsive table-card">
      <table class="table table-bordered align-middle text-center m-0">
        <thead>
          <tr>
            <th style="width:140px">ID Barang</th>
            <th class="text-start">Nama Barang</th>
            <th style="width:160px">Harga</th>
            <th style="width:120px">Stok</th>
            <th style="width:150px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $filter = "";
            if (isset($_GET['cari']) && $_GET['cari'] != '') {
              $cari = mysqli_real_escape_string($koneksi, $_GET['cari']);
              $filter = "WHERE id_barang LIKE '%$cari%' OR nama_barang LIKE '%$cari%'";
            }
            $result = mysqli_query($koneksi, "SELECT * FROM barang $filter ORDER BY id_barang ASC");
            if (mysqli_num_rows($result) == 0) {
              echo "<tr><td colspan='5' class='text-muted py-5'>Tidak ada data ditemukan.</td></tr>";
            } else {
              while($row = mysqli_fetch_assoc($result)):
          ?>
          <tr>
            <td class="fw-semibold"><?= $row['id_barang']; ?></td>
            <td class="text-start"><?= $row['nama_barang']; ?></td>
            <td class="fw-semibold">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
            <td>
              <?php
                $stok = (int)$row['stok'];
                $badge = $stok == 0 ? 'bg-danger' : ($stok <= 5 ? 'bg-warning text-dark' : 'bg-success');
                $label = $stok == 0 ? 'Habis' : $stok;
              ?>
              <span class="badge <?= $badge ?> rounded-pill px-3"><?= $label ?></span>
            </td>
            <td>
              <button class="btn btn-sm btn-warning editBtn me-1"
                data-id="<?= $row['id_barang']; ?>"
                data-nama="<?= $row['nama_barang']; ?>"
                data-harga="<?= $row['harga']; ?>"
                data-stok="<?= $row['stok']; ?>"
                title="Edit">
                <i class="bi bi-pencil-square"></i>
              </button>
              <a href="?hapus=<?= $row['id_barang']; ?>"
                 onclick="return confirm('Yakin ingin menghapus barang ini?')"
                 class="btn btn-sm btn-danger" title="Hapus">
                 <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>
          <?php endwhile; } ?>
        </tbody>
      </table>
    </div>
    <div class="mt-2 page-hint">
    </div>
  </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4" style="border-radius:var(--radius)">
      <h5 class="fw-bold text-center mb-3">➕ Tambah Barang</h5>
      <?php
        $q = mysqli_query($koneksi, "SELECT MAX(id_barang) AS kode FROM barang");
        $d = mysqli_fetch_assoc($q);
        $urut = (int) substr($d['kode'], 2, 3);
        $urut++;
        $kodeBaru = "BR" . sprintf("%03s", $urut);
      ?>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">ID Barang</label>
          <input type="text" name="id_barang" class="form-control" value="<?= $kodeBaru; ?>" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Nama Barang</label>
          <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Harga</label>
          <input type="number" name="harga" class="form-control" required min="0">
        </div>
        <div class="mb-3">
          <label class="form-label">Stok</label>
          <input type="number" name="stok" class="form-control" required min="0">
        </div>
        <div class="text-center">
          <button type="submit" name="simpan" class="btn btn-primary px-4" style="border-radius:var(--radius-sm)">Simpan</button>
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius:var(--radius-sm)">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4" style="border-radius:var(--radius)">
      <h5 class="fw-bold text-center mb-3">✏️ Edit Barang</h5>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">ID Barang</label>
          <input type="text" name="id" id="edit_id" class="form-control" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Nama Barang</label>
          <input type="text" name="nama" id="edit_nama" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Harga</label>
          <input type="number" name="harga" id="edit_harga" class="form-control" required min="0">
        </div>
        <div class="mb-3">
          <label class="form-label">Stok</label>
          <input type="number" name="stok" id="edit_stok" class="form-control" required min="0">
        </div>
        <div class="text-center">
          <button type="submit" name="update" class="btn btn-success px-4" style="border-radius:var(--radius-sm)">Update</button>
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius:var(--radius-sm)">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- PROSES CRUD PHP -->
<?php
// ===== TAMBAH DATA =====
if (isset($_POST['simpan'])) {
  $id     = $_POST['id_barang'];
  $nama   = strtoupper(trim($_POST['nama']));
  $harga  = (int) $_POST['harga'];
  $stok   = (int) $_POST['stok'];

  if (empty($nama) || $harga < 0 || $stok < 0) {
    echo "<script>alert('❌ Semua kolom harus diisi dengan benar!');</script>";
  } else {
    $cek = mysqli_query($koneksi, "SELECT id_barang FROM barang WHERE id_barang='$id'");
    if (mysqli_num_rows($cek) > 0) {
      echo "<script>alert('⚠️ ID Barang sudah ada, silakan refresh halaman untuk dapat ID baru.');</script>";
    } else {
      $query = "INSERT INTO barang (id_barang, nama_barang, harga, stok)
                VALUES ('$id', '$nama', '$harga', '$stok')";
      if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('✅ Barang berhasil ditambahkan!');window.location='barang.php';</script>";
      } else {
        echo "<script>alert('❌ Gagal menambah barang: " . mysqli_error($koneksi) . "');</script>";
      }
    }
  }
}

// ===== UPDATE DATA =====
if (isset($_POST['update'])) {
  $id     = $_POST['id'];
  $nama   = strtoupper(trim($_POST['nama']));
  $harga  = (int) $_POST['harga'];
  $stok   = (int) $_POST['stok'];

  if (empty($nama) || $harga < 0 || $stok < 0) {
    echo "<script>alert('❌ Semua kolom harus diisi dengan benar!');</script>";
  } else {
    $query = "UPDATE barang SET nama_barang='$nama', harga='$harga', stok='$stok' WHERE id_barang='$id'";
    if (mysqli_query($koneksi, $query)) {
      echo "<script>alert('✅ Data berhasil diperbarui!');window.location='barang.php';</script>";
    } else {
      echo "<script>alert('❌ Gagal memperbarui: " . mysqli_error($koneksi) . "');</script>";
    }
  }
}

// ===== HAPUS DATA =====
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  $hapus = mysqli_query($koneksi, "DELETE FROM barang WHERE id_barang='$id'");
  if ($hapus) {
    echo "<script>alert('🗑️ Barang berhasil dihapus!');window.location='barang.php';</script>";
  } else {
    echo "<script>alert('❌ Gagal menghapus data: " . mysqli_error($koneksi) . "');</script>";
  }
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Fokus search dengan tombol '/'
document.addEventListener('keydown', (e)=>{
  if(e.key === '/'){
    const el = document.querySelector('input[name="cari"]');
    if(el){ e.preventDefault(); el.focus(); el.select(); }
  }
});
// Isi otomatis data edit ke modal
document.querySelectorAll('.editBtn').forEach(button => {
  button.addEventListener('click', function() {
    document.getElementById('edit_id').value = this.dataset.id;
    document.getElementById('edit_nama').value = this.dataset.nama;
    document.getElementById('edit_harga').value = this.dataset.harga;
    document.getElementById('edit_stok').value = this.dataset.stok;
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
  });
});
</script>

</body>
</html>
