<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Perpustakaan</title>

    <!-- Google Font: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .card {
            border: none;
            border-radius: 1rem;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            <div class="text-center mb-4">
                <i class="fas fa-book fa-3x text-danger mb-2"></i>
                <h4 class="text-danger">Login Perpustakaan</h4>
            </div>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger text-center">
                    <?php
                    if ($_GET['error'] === 'usernotfound') echo "Username tidak ditemukan!";
                    elseif ($_GET['error'] === 'password') echo "Password salah!";
                    ?>
                </div>
            <?php endif; ?>
            <form action="proses_login.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label text-secondary">Username</label>
                    <input type="text" name="username" id="username" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label text-secondary">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required />
                </div>
                <button type="submit" class="btn btn-danger w-100">Login</button>
            </form>
            <p class="mt-3 text-center text-muted small">© 2025 - Perpustakaan SMK</p>
        </div>
    </div>
</body>

</html>