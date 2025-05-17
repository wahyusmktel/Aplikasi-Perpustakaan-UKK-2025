<?php
include 'koneksi.php';

// Tambah Buku
if (isset($_POST['tambah'])) {
    $judul    = $_POST['judul'];
    $penulis  = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun    = $_POST['tahun_terbit'];
    $cover    = null;

    if (!empty($_FILES['cover']['name'])) {
        $file = $_FILES['cover'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $cover = uniqid('cover_') . '.' . $ext;
        move_uploaded_file($file['tmp_name'], 'uploads/' . $cover);
    }

    $stmt = $conn->prepare("INSERT INTO buku (judul, penulis, penerbit, tahun_terbit, cover) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $judul, $penulis, $penerbit, $tahun, $cover);
    $stmt->execute();
    header("Location: buku.php");
    exit;
}

// Edit Buku
if (isset($_POST['edit'])) {
    $id       = $_POST['id'];
    $judul    = $_POST['judul'];
    $penulis  = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun    = $_POST['tahun_terbit'];

    // Ambil cover lama
    $data     = $conn->query("SELECT cover FROM buku WHERE id = $id")->fetch_assoc();
    $cover    = $data['cover'];

    if (!empty($_FILES['cover']['name'])) {
        // Hapus file lama
        if ($cover && file_exists("uploads/$cover")) {
            unlink("uploads/$cover");
        }

        $file  = $_FILES['cover'];
        $ext   = pathinfo($file['name'], PATHINFO_EXTENSION);
        $cover = uniqid('cover_') . '.' . $ext;
        move_uploaded_file($file['tmp_name'], 'uploads/' . $cover);
    }

    $stmt = $conn->prepare("UPDATE buku SET judul=?, penulis=?, penerbit=?, tahun_terbit=?, cover=? WHERE id=?");
    $stmt->bind_param("sssisi", $judul, $penulis, $penerbit, $tahun, $cover, $id);
    $stmt->execute();
    header("Location: buku.php");
    exit;
}

// Hapus Buku
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $data = $conn->query("SELECT cover FROM buku WHERE id = $id")->fetch_assoc();

    // Hapus cover jika ada
    if ($data && $data['cover'] && file_exists("uploads/{$data['cover']}")) {
        unlink("uploads/{$data['cover']}");
    }

    $conn->query("DELETE FROM buku WHERE id = $id");
    header("Location: buku.php");
    exit;
}

// Ambil semua data buku
$buku = $conn->query("SELECT * FROM buku ORDER BY created_at DESC");

$title = "Data Buku";
ob_start();
?>

<!-- Tombol Tambah -->
<button class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
    <i class="fas fa-plus"></i> Tambah Buku
</button>

<!-- Tabel Data Buku -->
<div class="table-responsive">
    <table class="table table-bordered table-striped table-nowrap">
        <thead class="table-danger">
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            $modals = '';
            while ($row = $buku->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['judul']); ?></td>
                    <td><?= htmlspecialchars($row['penulis']); ?></td>
                    <td><?= htmlspecialchars($row['penerbit']); ?></td>
                    <td><?= htmlspecialchars($row['tahun_terbit']); ?></td>
                    <td>
                        <!-- Tombol Edit -->
                        <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id']; ?>"><i class="fas fa-edit"></i></button>

                        <!-- Tombol Hapus -->
                        <a href="buku.php?hapus=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus buku ini?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>

                <?php
                $modals .= '
                        <div class="modal fade" id="modalEdit' . $row['id'] . '" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" enctype="multipart/form-data" class="modal-content">
                                    <div class="modal-header bg-secondary text-white">
                                        <h5 class="modal-title">Edit Buku</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="' . $row['id'] . '">

                                        <div class="mb-2">
                                            <label>Judul</label>
                                            <input type="text" name="judul" class="form-control" value="' . htmlspecialchars($row['judul']) . '" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Penulis</label>
                                            <input type="text" name="penulis" class="form-control" value="' . htmlspecialchars($row['penulis']) . '">
                                        </div>
                                        <div class="mb-2">
                                            <label>Penerbit</label>
                                            <input type="text" name="penerbit" class="form-control" value="' . htmlspecialchars($row['penerbit']) . '">
                                        </div>
                                        <div class="mb-2">
                                            <label>Tahun Terbit</label>
                                            <input type="number" name="tahun_terbit" class="form-control" value="' . $row['tahun_terbit'] . '">
                                        </div>
                                        <div class="mb-2">
                                            <label>Ganti Cover (jpg/png)</label>
                                            <input type="file" name="cover" class="form-control">
                                            ' . ($row['cover'] ? '<small class="text-muted">Saat ini: ' . $row['cover'] . '</small>' : '<small class="text-muted">Belum ada cover</small>') . '
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="edit" class="btn btn-secondary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    ';
                ?>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?= $modals ?>

<!-- Modal Tambah Buku -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Tambah Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label>Judul</label>
                    <input type="text" name="judul" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Penulis</label>
                    <input type="text" name="penulis" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Penerbit</label>
                    <input type="text" name="penerbit" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Cover Buku (jpg/png)</label>
                    <input type="file" name="cover" class="form-control">
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