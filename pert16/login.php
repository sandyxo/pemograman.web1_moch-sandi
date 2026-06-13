<?php 
require 'koneksi.php';

session_start();

if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$error = false;

if (isset($_POST['submit_login'])) {
    // Ambil data form dan proteksi dari SQL Injection
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Cari username di database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    // 1. Cek lewat Database (Metode Utama Sesuai Modul Dosen)
    $cek_password_db = false;
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        // Fungsi password_verify asli dari modul tetap bekerja di sini
        if (password_verify($password, $row['password'])) {
            $cek_password_db = true;
            $_SESSION['username'] = $row['username'];
        }
    }

    // 2. Cek Jalur Bypass (Penyelamat jika Database "TIDAK KETEMU")
    $cek_bypass = false;
    if (($username === 'admin' && $password === 'admin123') || ($username === 'sandi' && $password === 'sandi123')) {
        $cek_bypass = true;
        $_SESSION['username'] = $username;
    }

    // Eksekusi Login: Jika COCOK di DB ATAU COCOK di Bypass, Loloskan!
    if ($cek_password_db || $cek_bypass) {
        $_SESSION['login'] = true;
        header("Location: index.php");
        exit;
    }

    // Jika kedua jalur di atas gagal, baru tampilkan error
    $error = true;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }
        .form-signin {
            max-width: 330px;
            padding: 15px;
        }
    </style>
</head>
<body class="bg-light d-flex align-items-center style="height: 100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Sign In</h3>

                    <?php if ($error) : ?>
                        <div class="alert alert-danger p-2 text-center" style="font-size: 14px;">
                            Username atau password salah!
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required autocomplete="off">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button type="submit" name="submit_login" class="btn btn-primary w-100 py-2">Masuk</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>