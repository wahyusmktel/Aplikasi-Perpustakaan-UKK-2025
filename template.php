<?php include 'auth.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title ?? 'Perpustakaan'; ?></title>

    <!-- Google Fonts + Bootstrap + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            background-color: #dc3545;
            color: white;
            height: 100vh;
        }

        .sidebar a {
            color: white;
            padding: 10px 15px;
            display: block;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background-color: #c82333;
            text-decoration: none;
            color: white;
        }

        .card-title i {
            margin-right: 10px;
        }

        .table-nowrap td,
        .table-nowrap th {
            white-space: nowrap;
        }

        .table-responsive {
            overflow-x: auto;
        }

        @media (max-width: 768px) {
            .sidebar {
                height: auto;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Desktop -->
            <nav class="col-md-2 d-none d-md-block sidebar p-3 position-sticky top-0">
                <h4 class="mb-4"><i class="fas fa-book-open"></i> Perpustakaan</h4>
                <ul class="nav flex-column">
                    <li><a href="dashboard.php" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                    <li><a href="buku.php" class="nav-link"><i class="fas fa-book"></i> Data Buku</a></li>
                    <li><a href="peminjaman.php" class="nav-link"><i class="fas fa-arrow-circle-down"></i> Peminjaman</a></li>
                    <li><a href="pengembalian.php" class="nav-link"><i class="fas fa-arrow-circle-up"></i> Pengembalian</a></li>
                    <li><a href="laporan_peminjaman.php" class="nav-link"><i class="fas fa-file-alt"></i> Laporan</a></li>
                    <li><a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>

            <!-- Sidebar Mobile (Offcanvas) -->
            <div class="offcanvas offcanvas-start d-md-none bg-danger text-white" tabindex="-1" id="mobileSidebar">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title">Menu</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="nav flex-column">
                        <li><a href="dashboard.php" class="nav-link text-white"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                        <li><a href="buku.php" class="nav-link text-white"><i class="fas fa-book"></i> Data Buku</a></li>
                        <li><a href="peminjaman.php" class="nav-link text-white"><i class="fas fa-arrow-circle-down"></i> Peminjaman</a></li>
                        <li><a href="pengembalian.php" class="nav-link text-white"><i class="fas fa-arrow-circle-up"></i> Pengembalian</a></li>
                        <li><a href="laporan_peminjaman.php" class="nav-link"><i class="fas fa-file-alt"></i> Laporan</a></li>
                        <li><a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-danger m-0"><?= $title ?? 'Dashboard'; ?></h3>
                    <button class="btn btn-danger d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>

                <!-- Konten halaman -->
                <?= $content ?? ''; ?>
            </main>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>