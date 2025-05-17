<?php
include 'koneksi.php';

// Tambah peminjaman
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama_peminjam'];
    $buku_id = $_POST['buku_id'];
    $tgl_pinjam = $_POST['tanggal_pinjam'];
    $tgl_kembali = $_POST['tanggal_kembali'];

    $stmt = $conn->prepare("INSERT INTO peminjaman (nama_peminjam, buku_id, tanggal_pinjam, tanggal_kembali) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $nama, $buku_id, $tgl_pinjam, $tgl_kembali);
    $stmt->execute();
    header("Location: peminjaman.php");
    exit;
}

// Hapus peminjaman
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM peminjaman WHERE id = $id");
    header("Location: peminjaman.php");
    exit;
}

// Ambil semua data peminjaman + buku
$data = $conn->query("SELECT p.*, b.judul FROM peminjaman p JOIN buku b ON p.buku_id = b.id ORDER BY p.created_at DESC");

// Ambil daftar buku untuk pilihan
$buku = $conn->query("SELECT id, judul FROM buku");

$title = "Data Peminjaman";
ob_start();
?>

<!-- Tombol Tambah -->
<button class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
    <i class="fas fa-plus"></i> Tambah Peminjaman
</button>

<!-- Tabel Peminjaman -->
<div class="table-responsive">
    <table class="table table-bordered table-striped table-nowrap">
        <thead class="table-danger">
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            while ($row = $data->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nama_peminjam']); ?></td>
                    <td><?= htmlspecialchars($row['judul']); ?></td>
                    <td><?= $row['tanggal_pinjam']; ?></td>
                    <td><?= $row['tanggal_kembali']; ?></td>
                    <td>
                        <a href="peminjaman.php?hapus=<?= $row['id']; ?>" onclick="return confirm('Hapus data ini?')" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Tambah Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label>Nama Peminjam</label>
                    <input type="text" name="nama_peminjam" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Buku</label>
                    <select name="buku_id" class="form-select" required>
                        <option value="">-- Pilih Buku --</option>
                        <?php while ($b = $buku->fetch_assoc()): ?>
                            <option value="<?= $b['id']; ?>"><?= htmlspecialchars($b['judul']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="tambah" class="btn btn-danger">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'template.php';
?>