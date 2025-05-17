<?php
session_start();
include 'koneksi.php'; // buat file ini untuk koneksi database

$username = $_POST['username'];
$password = $_POST['password'];

// Gunakan query dengan bind parameter jika menggunakan PDO
$query = "SELECT * FROM user WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Jika password belum di-hash, pakai MD5
    if (md5($password) === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        header("Location: dashboard.php");
        exit;
    } else {
        header("Location: login.php?error=password");
        exit;
    }
} else {
    header("Location: login.php?error=usernotfound");
    exit;
}
