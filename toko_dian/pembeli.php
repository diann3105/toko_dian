<?php include "koneksi.php"; ?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Pembeli | Toko Dian</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
  :root{
    --brand-1:#2563eb;
    --brand-2:#60a5fa;
    --ink:#0f172a;
    --muted:#64748b;
    --bg:#f8fafc;
    --card:#ffffff;
    --radius:14px;
    --radius-sm:10px;
  }
  *{box-sizing:border-box}
  body{
    background:var(--bg);
    color:var(--ink);
    font-family:'Poppins',sans-serif;
  }
  /* Navbar */
  .app-navbar{
    backdrop-filter:saturate(180%) blur(8px);
    background:rgba(255,255,255,.85);
    border-bottom:1px solid rgba(15,23,42,.06);
  }
  .nav-link{font-weight:600;}
  .nav-link.active{color:var(--brand-1)!important;}
  /* Container */
  .container-box{
    background:var(--card);
    border:1px solid rgba(15,23,42,.06);
    border-radius:var(--radius);
    box-shadow:0 8px 24px rgba(2,6,23,.06);
    padding:28px;
  }
  h2{font-weight:700;letter-spacing:.2px;}
  /* Buttons */
  .btn-modern{
    border:none;
    border-radius:var(--radius-sm);
    font-weight:600;
  }
  .btn-add{background:linear-gradient(90deg,var(--brand-1),var(--brand-2));color:#fff;}
  .btn-back{background:linear-gradient(90deg,#ef4444,#f59e0b);color:#fff;}
  .btn-modern:hover{opacity:.95;transform:translateY(-1px);}
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
    font-weight:600;
    border-color:transparent!important;
  }
  .table tbody tr:hover{background:#f1f5ff}
  /* Search */
  .search-wrap {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}

/* samakan tinggi semua elemen */
.search-wrap .form-control,
.search-wrap .btn {
  height: 46px;
  border-radius: 10px;
  font-weight: 600;
}

/* input pencarian */
.search-wrap .form-control {
  border: 1px solid rgba(100,116,139,.4);
  padding: 10px 14px;
  flex: 1;
}

/* tombol cari */
.search-wrap .btn-outline-primary,
.search-wrap .btn-outline-secondary {
  width: 46px;
  display: flex;
  align-items: center;
  justify-content: center;
}

  .badge-soft{
    background:#eef2ff;color:#3730a3;font-weight:600;border:1px solid #e0e7ff;
  }
  .page-hint{color:var(--muted);font-size:.9rem}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg sticky-top app-navbar">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-bag-check-fill me-1"></i>Zapstore</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#appNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="appNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="barang.php"><i class="bi bi-box-seam me-1"></i>Barang</a></li>
        <li class="nav-item"><a class="nav-link active" href="pembeli.php"><i class="bi bi-people me-1"></i>Pembeli</a></li>
        <li class="nav-item"><a class="nav-link" href="transaksi.php"><i class="bi bi-receipt me-1"></i>Transaksi</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- CONTENT -->
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="m-0">👤 Data Pembeli <span class="badge badge-soft ms-2"></span></h2>
  </div>

  <div class="container-box mb-3">
    <!-- SEARCH -->
    <form class="search-wrap mb-3" method="GET">
      <input type="text" name="cari" class="form-control flex-grow-1" placeholder="🔍 Cari Nama Pembeli..."
             value="<?= $_GET['cari'] ?? ''; ?>" autofocus>
      <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
      <a href="pembeli.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-repeat"></i></a>
    </form>

    <div class="d-flex flex-wrap gap-2 mb-3">
      <button class="btn-modern btn-add px-3 py-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-circle me-1"></i>Tambah Pembeli
      </button>
    </div>

    <!-- TABLE -->
    <div class="table-responsive table-card">
      <table class="table table-bordered align-middle text-center m-0">
        <thead>
          <tr>
            <th style="width:140px">ID Pembeli</th>
            <th class="text-start">Nama Pembeli</th>
            <th class="text-start">Alamat</th>
            <th style="width:150px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $filter = "";
            if (isset($_GET['cari']) && $_GET['cari'] != '') {
              $cari = mysqli_real_escape_string($koneksi, $_GET['cari']);
              $filter = "WHERE nama_pembeli LIKE '%$cari%'";
            }

            $result = mysqli_query($koneksi, "SELECT * FROM pembeli $filter ORDER BY id_pembeli ASC");
            if (mysqli_num_rows($result) == 0) {
              echo "<tr><td colspan='4' class='text-muted py-5'>Tidak ada data ditemukan.</td></tr>";
            } else {
              while($row = mysqli_fetch_assoc($result)):
          ?>
          <tr>
            <td class="fw-semibold"><?= $row['id_pembeli']; ?></td>
            <td class="text-start"><?= $row['nama_pembeli']; ?></td>
            <td class="text-start"><?= $row['alamat']; ?></td>
            <td>
              <button class="btn btn-sm btn-warning editBtn me-1"
                data-id="<?= $row['id_pembeli']; ?>"
                data-nama="<?= $row['nama_pembeli']; ?>"
                data-alamat="<?= $row['alamat']; ?>"
                title="Edit">
                <i class="bi bi-pencil-square"></i>
              </button>
              <a href="?hapus=<?= $row['id_pembeli']; ?>"
                 onclick="return confirm('Yakin ingin menghapus pembeli ini?')"
                 class="btn btn-sm btn-danger" title="Hapus">
                 <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>
          <?php endwhile; } ?>
        </tbody>
      </table>
    </div>
    <div class="mt-2 page-hint"></div>
  </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4" style="border-radius:var(--radius)">
      <h5 class="fw-bold text-center mb-3">➕ Tambah Pembeli</h5>
      <?php
        $q = mysqli_query($koneksi, "SELECT MAX(id_pembeli) AS kode FROM pembeli");
        $d = mysqli_fetch_assoc($q);
        $urut = (int) substr($d['kode'], 2, 3);
        $urut++;
        $kodeBaru = "PB" . sprintf("%03s", $urut);
      ?>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">ID Pembeli</label>
          <input type="text" name="id_pembeli" class="form-control" value="<?= $kodeBaru; ?>" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Nama Pembeli</label>
          <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Alamat</label>
          <textarea name="alamat" class="form-control" required></textarea>
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
      <h5 class="fw-bold text-center mb-3">✏️ Edit Pembeli</h5>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">ID Pembeli</label>
          <input type="text" name="id" id="edit_id" class="form-control" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Nama Pembeli</label>
          <input type="text" name="nama" id="edit_nama" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Alamat</label>
          <textarea name="alamat" id="edit_alamat" class="form-control" required></textarea>
        </div>
        <div class="text-center">
          <button type="submit" name="update" class="btn btn-success px-4" style="border-radius:var(--radius-sm)">Update</button>
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius:var(--radius-sm)">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- PROSES CRUD -->
<?php
// Tambah
if(isset($_POST['simpan'])){
  $id = $_POST['id_pembeli'];
  $nama = ucwords(strtolower(trim($_POST['nama'])));
  $alamat = trim($_POST['alamat']);

  if(empty($nama) || empty($alamat)){
    echo "<script>alert('❌ Semua kolom harus diisi!');</script>";
  } else {
    $query = "INSERT INTO pembeli (id_pembeli, nama_pembeli, alamat) VALUES ('$id', '$nama', '$alamat')";
    if(mysqli_query($koneksi, $query)){
      echo "<script>alert('✅ Pembeli berhasil ditambahkan!');window.location='pembeli.php';</script>";
    } else {
      echo "<script>alert('❌ Gagal menambah pembeli: ".mysqli_error($koneksi)."');</script>";
    }
  }
}
// Update
if(isset($_POST['update'])){
  $id = $_POST['id'];
  $nama = ucwords(strtolower(trim($_POST['nama'])));
  $alamat = trim($_POST['alamat']);

  if(empty($nama) || empty($alamat)){
    echo "<script>alert('❌ Semua kolom harus diisi!');</script>";
  } else {
    $query = "UPDATE pembeli SET nama_pembeli='$nama', alamat='$alamat' WHERE id_pembeli='$id'";
    if(mysqli_query($koneksi, $query)){
      echo "<script>alert('✅ Data berhasil diperbarui!');window.location='pembeli.php';</script>";
    } else {
      echo "<script>alert('❌ Gagal memperbarui: ".mysqli_error($koneksi)."');</script>";
    }
  }
}
// Hapus
if(isset($_GET['hapus'])){
  $id = $_GET['hapus'];
  mysqli_query($koneksi, "DELETE FROM pembeli WHERE id_pembeli='$id'");
  echo "<script>alert('🗑️ Pembeli berhasil dihapus!');window.location='pembeli.php';</script>";
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Shortcut fokus pencarian
document.addEventListener('keydown', (e)=>{
  if(e.key === '/'){
    const el = document.querySelector('input[name="cari"]');
    if(el){ e.preventDefault(); el.focus(); el.select(); }
  }
});
// Isi otomatis data edit
document.querySelectorAll('.editBtn').forEach(button=>{
  button.addEventListener('click',function(){
    document.getElementById('edit_id').value=this.dataset.id;
    document.getElementById('edit_nama').value=this.dataset.nama;
    document.getElementById('edit_alamat').value=this.dataset.alamat;
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
  });
});
</script>

</body>
</html>
