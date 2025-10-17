<?php include "koneksi.php"; ?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Transaksi | Toko Dian</title>

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
h2{font-weight:700;}
.btn-modern{
  border:none;border-radius:var(--radius-sm);font-weight:600;
}
.btn-add{background:linear-gradient(90deg,var(--brand-1),var(--brand-2));color:#fff;}
.btn-modern:hover{opacity:.95;transform:translateY(-1px);}
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
}
.table tbody tr:hover{background:#f1f5ff;}
.filter-bar .input-group{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
.filter-bar input[type="date"],
.filter-bar input[type="text"],.filter-bar .btn{
  height:46px;border-radius:10px;font-weight:600;
}
.filter-bar input[type="date"],.filter-bar input[type="text"]{
  border:1px solid rgba(100,116,139,.4);padding:10px 14px;
}
.filter-bar input[type="date"]{width:180px;}
.filter-bar input[type="text"]{flex:1;}
.filter-bar .btn-outline-secondary,.filter-bar .btn-outline-dark{
  width:46px;display:flex;align-items:center;justify-content:center;
}
.badge-soft{background:#eef2ff;color:#3730a3;font-weight:600;border:1px solid #e0e7ff;}
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
        <li class="nav-item"><a class="nav-link" href="pembeli.php"><i class="bi bi-people me-1"></i>Pembeli</a></li>
        <li class="nav-item"><a class="nav-link active" href="transaksi.php"><i class="bi bi-receipt me-1"></i>Transaksi</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- CONTENT -->
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="m-0">💳 Data Transaksi</h2>
  </div>

  <div class="container-box mb-3">
    <form class="filter-bar mb-3" method="GET">
      <div class="input-group w-100">
        <input type="date" name="tanggal" class="form-control" value="<?= $_GET['tanggal'] ?? '' ?>">
        <input type="text" name="cari" class="form-control" placeholder="🔍 Cari nama pembeli..." value="<?= $_GET['cari'] ?? ''; ?>">
        <button class="btn btn-primary px-4" type="submit"><i class="bi bi-search me-1"></i>Cari</button>
        <a href="transaksi.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-repeat"></i></a>
        <a href="transaksi_print.php?tanggal=<?= $_GET['tanggal'] ?? '' ?>&cari=<?= $_GET['cari'] ?? '' ?>" target="_blank" class="btn btn-outline-dark"><i class="bi bi-printer"></i></a>
      </div>
    </form>

    <div class="d-flex flex-wrap gap-2 mb-3 justify-content-between">
      <button class="btn-modern btn-add px-3 py-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-circle me-1"></i>Tambah Transaksi
      </button>
    </div>

    <div class="table-responsive table-card">
      <table class="table table-bordered align-middle text-center m-0">
        <thead>
          <tr>
            <th>ID Transaksi</th><th>Pembeli</th><th>Barang</th><th>Jumlah</th><th>Total Harga</th><th>Tanggal</th><th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
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
                    $filter ORDER BY t.id_transaksi ASC";
            $result = mysqli_query($koneksi, $sql);

            if(mysqli_num_rows($result) == 0){
              echo "<tr><td colspan='7' class='text-muted py-5'>Tidak ada data ditemukan.</td></tr>";
            } else {
              while($row = mysqli_fetch_assoc($result)):
          ?>
          <tr>
            <td class="fw-semibold"><?= $row['id_transaksi'] ?></td>
            <td class="text-start"><?= $row['nama_pembeli'] ?></td>
            <td class="text-start"><?= $row['nama_barang'] ?></td>
            <td><?= $row['jumlah'] ?></td>
            <td class="fw-semibold">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
            <td><?= $row['tanggal'] ?></td>
            <td>
              <button class="btn btn-sm btn-warning editBtn me-1"
                data-id="<?= $row['id_transaksi']; ?>"
                data-pembeli="<?= $row['id_pembeli']; ?>"
                data-barang="<?= $row['id_barang']; ?>"
                data-jumlah="<?= $row['jumlah']; ?>"
                data-tanggal="<?= $row['tanggal']; ?>">
                <i class="bi bi-pencil-square"></i>
              </button>
              <a href="?hapus=<?= $row['id_transaksi'] ?>" onclick="return confirm('Hapus transaksi ini? Stok akan dikembalikan.')" class="btn btn-sm btn-danger">
                <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>
          <?php endwhile; } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4" style="border-radius:var(--radius)">
      <h5 class="fw-bold text-center mb-3">➕ Tambah Transaksi</h5>
      <form method="POST">
        <?php
          $q = mysqli_query($koneksi, "SELECT MAX(id_transaksi) AS kode FROM transaksi");
          $d = mysqli_fetch_assoc($q);
          $urut = (int) substr($d['kode'], 2, 3);
          $urut++;
          $kodeBaru = "TR" . sprintf("%03s", $urut);
        ?>
        <div class="mb-3">
          <label class="form-label">ID Transaksi</label>
          <input type="text" name="id_transaksi" class="form-control" value="<?= $kodeBaru; ?>" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Pilih Pembeli</label>
          <select name="id_pembeli" class="form-select" required>
            <option value="">-- Pilih Pembeli --</option>
            <?php
              $pembeli = mysqli_query($koneksi, "SELECT * FROM pembeli ORDER BY nama_pembeli");
              while($p = mysqli_fetch_assoc($pembeli)){
                echo "<option value='{$p['id_pembeli']}'>{$p['nama_pembeli']}</option>";
              }
            ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Pilih Barang</label>
          <select name="id_barang" class="form-select" required>
            <option value="">-- Pilih Barang --</option>
            <?php
              $barang = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY nama_barang");
              while($b = mysqli_fetch_assoc($barang)){
                echo "<option value='{$b['id_barang']}'>{$b['nama_barang']} (Stok: {$b['stok']})</option>";
              }
            ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Jumlah Beli</label>
          <input type="number" name="jumlah" class="form-control" min="1" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Tanggal Transaksi</label>
          <!-- diisi otomatis via JavaScript saat modal dibuka -->
          <input type="date" name="tanggal" id="tanggalInput" class="form-control" required>
        </div>
        <div class="text-center">
          <button type="submit" name="simpan" class="btn btn-primary px-4" style="border-radius:var(--radius-sm)">Simpan</button>
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius:var(--radius-sm)">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// === isi tanggal otomatis setiap kali modal tambah dibuka ===
document.addEventListener('DOMContentLoaded', function(){
  const modalTambah = document.getElementById('modalTambah');
  modalTambah.addEventListener('show.bs.modal', function () {
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    document.getElementById('tanggalInput').value = `${yyyy}-${mm}-${dd}`;
  });
});
</script>

<!-- MODAL EDIT -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4" style="border-radius:var(--radius)">
      <h5 class="fw-bold text-center mb-3">✏️ Edit Transaksi</h5>
      <form method="POST">
        <input type="hidden" name="id" id="edit_id">
        <div class="mb-3">
          <label class="form-label">Pembeli</label>
          <select name="id_pembeli" id="edit_pembeli" class="form-select" required>
            <option value="">-- Pilih Pembeli --</option>
            <?php
              $pembeli2 = mysqli_query($koneksi, "SELECT * FROM pembeli ORDER BY nama_pembeli");
              while($p = mysqli_fetch_assoc($pembeli2)){
                echo "<option value='{$p['id_pembeli']}'>{$p['nama_pembeli']}</option>";
              }
            ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Barang</label>
          <select name="id_barang" id="edit_barang" class="form-select" required>
            <option value="">-- Pilih Barang --</option>
            <?php
              $barang2 = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY nama_barang");
              while($b = mysqli_fetch_assoc($barang2)){
                echo "<option value='{$b['id_barang']}'>{$b['nama_barang']} (Stok: {$b['stok']})</option>";
              }
            ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Jumlah</label>
          <input type="number" name="jumlah" id="edit_jumlah" class="form-control" min="1" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Tanggal Transaksi</label>
          <input type="date" name="tanggal" id="edit_tanggal" class="form-control" required>
        </div>
        <div class="text-center">
          <button type="submit" name="update" class="btn btn-success px-4">Update</button>
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
// TAMBAH TRANSAKSI
if(isset($_POST['simpan'])){
  $id = $_POST['id_transaksi'];
  $id_pembeli = $_POST['id_pembeli'];
  $id_barang = $_POST['id_barang'];
  $jumlah = (int)$_POST['jumlah'];
  $tanggal = $_POST['tanggal'];

  $qbarang = mysqli_query($koneksi, "SELECT harga, stok FROM barang WHERE id_barang='$id_barang'");
  $barang = mysqli_fetch_assoc($qbarang);
  $harga = $barang['harga']; $stok = $barang['stok'];

  if($jumlah > $stok){
    echo "<script>alert('❌ Jumlah beli melebihi stok!');</script>";
  } else {
    $total = $jumlah * $harga;
    mysqli_query($koneksi, "INSERT INTO transaksi VALUES('$id','$id_pembeli','$id_barang','$jumlah','$total','$tanggal')");
    mysqli_query($koneksi, "UPDATE barang SET stok=stok-$jumlah WHERE id_barang='$id_barang'");
    echo "<script>alert('✅ Transaksi berhasil disimpan!');window.location='transaksi.php';</script>";
  }
}

// UPDATE TRANSAKSI
if(isset($_POST['update'])){
  $id=$_POST['id'];
  $id_pembeli=$_POST['id_pembeli'];
  $id_barang_baru=$_POST['id_barang'];
  $jumlahBaru=(int)$_POST['jumlah'];
  $tanggal=$_POST['tanggal'];

  $lama=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * FROM transaksi WHERE id_transaksi='$id'"));
  $id_barang_lama=$lama['id_barang'];
  $jumlahLama=$lama['jumlah'];
  mysqli_query($koneksi,"UPDATE barang SET stok=stok+$jumlahLama WHERE id_barang='$id_barang_lama'");

  $brg=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT harga,stok FROM barang WHERE id_barang='$id_barang_baru'"));
  if($jumlahBaru>$brg['stok']){
    echo "<script>alert('❌ Jumlah melebihi stok!');</script>";
  } else {
    $totalBaru=$jumlahBaru*$brg['harga'];
    mysqli_query($koneksi,"UPDATE transaksi SET id_pembeli='$id_pembeli', id_barang='$id_barang_baru', jumlah='$jumlahBaru', total_harga='$totalBaru', tanggal='$tanggal' WHERE id_transaksi='$id'");
    mysqli_query($koneksi,"UPDATE barang SET stok=stok-$jumlahBaru WHERE id_barang='$id_barang_baru'");
    echo "<script>alert('✅ Transaksi berhasil diperbarui!');window.location='transaksi.php';</script>";
  }
}

// HAPUS
if(isset($_GET['hapus'])){
  $id=$_GET['hapus'];
  $t=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * FROM transaksi WHERE id_transaksi='$id'"));
  mysqli_query($koneksi,"UPDATE barang SET stok=stok+{$t['jumlah']} WHERE id_barang='{$t['id_barang']}'");
  mysqli_query($koneksi,"DELETE FROM transaksi WHERE id_transaksi='$id'");
  echo "<script>alert('🗑️ Transaksi dihapus & stok dikembalikan!');window.location='transaksi.php';</script>";
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.editBtn').forEach(btn=>{
  btn.addEventListener('click',function(){
    document.getElementById('edit_id').value=this.dataset.id;
    document.getElementById('edit_pembeli').value=this.dataset.pembeli;
    document.getElementById('edit_barang').value=this.dataset.barang;
    document.getElementById('edit_jumlah').value=this.dataset.jumlah;
    document.getElementById('edit_tanggal').value=this.dataset.tanggal;
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
  });
});
</script>

</body>
</html>
