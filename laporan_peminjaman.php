<?php
include 'koneksi.php';

$title = "Laporan Peminjaman";
ob_start();

// Ambil filter dari form
$tgl_awal = $_GET['tgl_awal'] ?? '';
$tgl_akhir = $_GET['tgl_akhir'] ?? '';
$nama = $_GET['nama'] ?? '';
$status = $_GET['status'] ?? '';

// Query dasar
$sql = "SELECT p.*, b.judul FROM peminjaman p JOIN buku b ON p.buku_id = b.id WHERE 1=1";

// Tambahkan filter jika ada
if ($tgl_awal && $tgl_akhir) {
    $sql .= " AND tanggal_pinjam BETWEEN '$tgl_awal' AND '$tgl_akhir'";
}

if ($nama) {
    $sql .= " AND nama_peminjam LIKE '%$nama%'";
}

if ($status && in_array($status, ['Dipinjam', 'Dikembalikan'])) {
    $sql .= " AND status = '$status'";
}

$sql .= " ORDER BY p.created_at DESC";
$data = $conn->query($sql);
?>

<h5 class="mb-3">Laporan Peminjaman Buku</h5>

<!-- Form Filter -->
<form method="GET" class="row g-3 mb-4">
    <div class="col-md-3">
        <label>Tanggal Awal</label>
        <input type="date" name="tgl_awal" value="<?= $tgl_awal ?>" class="form-control">
    </div>
    <div class="col-md-3">
        <label>Tanggal Akhir</label>
        <input type="date" name="tgl_akhir" value="<?= $tgl_akhir ?>" class="form-control">
    </div>
    <div class="col-md-3">
        <label>Nama Peminjam</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>" class="form-control" placeholder="Cari nama...">
    </div>
    <div class="col-md-2">
        <label>Status</label>
        <select name="status" class="form-select">
            <option value="">Semua</option>
            <option value="Dipinjam" <?= $status == 'Dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
            <option value="Dikembalikan" <?= $status == 'Dikembalikan' ? 'selected' : '' ?>>Dikembalikan</option>
        </select>
    </div>
    <div class="col-md-1 d-flex align-items-end">
        <div class="btn-group w-50">
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-search"></i>
            </button>
            <a href="laporan_peminjaman.php" class="btn btn-secondary">
                <i class="fas fa-sync-alt"></i>
            </a>
        </div>
    </div>
</form>

<!-- Tabel Data -->
<div class="table-responsive">
    <table class="table table-bordered table-striped table-nowrap">
        <thead class="table-danger">
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Pengembalian Nyata</th>
                <th>Status</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            while ($row = $data->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama_peminjam']) ?></td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= $row['tanggal_pinjam'] ?></td>
                    <td><?= $row['tanggal_kembali'] ?></td>
                    <td><?= $row['tanggal_kembali_real'] ?? '<span class="text-muted">-</span>' ?></td>
                    <td>
                        <?php if ($row['status'] == 'Dikembalikan'): ?>
                            <span class="badge bg-success">Dikembalikan</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Dipinjam</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $row['denda'] > 0 ? 'Rp ' . number_format($row['denda']) : '-' ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
include 'template.php';
?>