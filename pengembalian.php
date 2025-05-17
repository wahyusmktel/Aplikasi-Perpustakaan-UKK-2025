<?php
include 'koneksi.php';

// Proses pengembalian
if (isset($_POST['kembalikan'])) {
    $id = $_POST['id'];
    $tgl_real = $_POST['tanggal_kembali_real'];

    // Ambil tanggal jatuh tempo
    $pinjam = $conn->query("SELECT tanggal_kembali FROM peminjaman WHERE id = $id")->fetch_assoc();
    $jatuh_tempo = new DateTime($pinjam['tanggal_kembali']);
    $tgl_kembali = new DateTime($tgl_real);

    $selisih = $jatuh_tempo->diff($tgl_kembali)->days;
    $terlambat = ($tgl_kembali > $jatuh_tempo);
    $denda = $terlambat ? ($selisih * 1000) : 0; // misal: denda 1000/hari

    $stmt = $conn->prepare("UPDATE peminjaman SET tanggal_kembali_real=?, status='Dikembalikan', denda=? WHERE id=?");
    $stmt->bind_param("sii", $tgl_real, $denda, $id);
    $stmt->execute();
    header("Location: pengembalian.php");
    exit;
}

// Ambil data peminjaman yang belum dikembalikan
$data = $conn->query("SELECT p.*, b.judul FROM peminjaman p JOIN buku b ON p.buku_id = b.id WHERE status = 'Dipinjam' ORDER BY p.created_at DESC");

$title = "Pengembalian Buku";
ob_start();

$modals = '';
?>

<table class="table table-bordered table-striped table-nowrap">
    <thead class="table-danger">
        <tr>
            <th>No</th>
            <th>Nama Peminjam</th>
            <th>Buku</th>
            <th>Tgl Pinjam</th>
            <th>Jatuh Tempo</th>
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
                    <!-- Tombol Modal -->
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalKembali<?= $row['id']; ?>">
                        <i class="fas fa-check"></i> Kembalikan
                    </button>
                </td>
            </tr>

            <?php
            $modals .= '
                <div class="modal fade" id="modalKembali' . $row['id'] . '" tabindex="-1" aria-labelledby="modalKembaliLabel' . $row['id'] . '" aria-hidden="true">
                    <div class="modal-dialog">
                    <form method="POST" class="modal-content">
                        <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="modalKembaliLabel' . $row['id'] . '">Proses Pengembalian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <input type="hidden" name="id" value="' . $row['id'] . '">
                        <p>Nama Peminjam: <strong>' . htmlspecialchars($row['nama_peminjam']) . '</strong></p>
                        <p>Buku: <strong>' . htmlspecialchars($row['judul']) . '</strong></p>
                        <p>Jatuh Tempo: <strong>' . $row['tanggal_kembali'] . '</strong></p>
                        <div class="mb-2">
                            <label for="tanggalKembaliReal' . $row['id'] . '">Tanggal Pengembalian Nyata</label>
                            <input type="date" id="tanggalKembaliReal' . $row['id'] . '" name="tanggal_kembali_real" class="form-control" required>
                        </div>
                        </div>
                        <div class="modal-footer">
                        <button type="submit" name="kembalikan" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                    </div>
                </div>
                ';
            ?>

        <?php endwhile; ?>
    </tbody>
</table>

<?= $modals ?>

<?php
$content = ob_get_clean();
include 'template.php';
?>