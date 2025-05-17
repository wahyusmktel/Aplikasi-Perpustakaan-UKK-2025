<?php
include 'koneksi.php';

$title = "Dashboard";

// Total Buku
$total_buku = $conn->query("SELECT COUNT(*) AS total FROM buku")->fetch_assoc()['total'];

// Peminjaman Aktif
$peminjaman_aktif = $conn->query("SELECT COUNT(*) AS total FROM peminjaman WHERE status = 'Dipinjam'")->fetch_assoc()['total'];

// Terlambat
$today = date('Y-m-d');
$terlambat = $conn->query("SELECT COUNT(*) AS total FROM peminjaman WHERE status = 'Dipinjam' AND tanggal_kembali < '$today'")->fetch_assoc()['total'];

ob_start();
?>

<div class="row g-3">
    <p class="text-secondary">Selamat datang di sistem pengelolaan buku perpustakaan.</p>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <!-- Icon dalam kotak merah -->
                    <div class="bg-danger rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-book text-white fa-lg"></i>
                    </div>

                    <!-- Teks dan angka -->
                    <div class="text-start flex-grow-1 ms-3">
                        <h6 class="text-secondary mb-1">Total Buku</h6>
                        <p class="fs-3 fw-semibold text-dark mb-0"><?= $total_buku ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Widget: Peminjaman Aktif -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <!-- Icon Box -->
                    <div class="bg-danger rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-hand-holding text-white fa-lg"></i>
                    </div>

                    <!-- Text + Number -->
                    <div class="text-start flex-grow-1 ms-3">
                        <h6 class="text-secondary mb-1">Peminjaman Aktif</h6>
                        <p class="fs-3 fw-semibold text-dark mb-0"><?= $peminjaman_aktif ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Widget: Terlambat -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <!-- Icon Box -->
                    <div class="bg-danger rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-clock text-white fa-lg"></i>
                    </div>

                    <!-- Text + Number -->
                    <div class="text-start flex-grow-1 ms-3">
                        <h6 class="text-secondary mb-1">Terlambat</h6>
                        <p class="fs-3 fw-semibold text-dark mb-0"><?= $terlambat ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
$content = ob_get_clean();
include 'template.php';
