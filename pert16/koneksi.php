<?php
$host = "localhost";
$user = "root";     // Default Laragon
$pass = "";         // Default Laragon (kosong)
$db   = "db_mahasiswa";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>
