<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['login'])) {
    echo json_encode(['status' => 'error', 'message' => 'Akses ilegal terdeteksi. Silakan login.']);
    exit;
}

include 'koneksi.php';
$action = $_GET['action'] ?? '';

// ==========================================
// CONTROLLER: MAHASISWA
// ==========================================
if ($action == 'list') {
    $query = mysqli_query($conn, "SELECT * FROM mahasiswa ORDER BY id DESC");
    echo json_encode(mysqli_fetch_all($query, MYSQLI_ASSOC));
    exit;
}
if ($action == 'get_single') {
    $id = intval($_GET['id']);
    $query = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id = $id");
    echo json_encode(mysqli_fetch_assoc($query));
    exit;
}
if ($action == 'save') {
    $id = $_POST['id'] ?? '';
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = empty($id) 
        ? "INSERT INTO mahasiswa (nim, nama, jurusan, email) VALUES ('$nim', '$nama', '$jurusan', '$email')"
        : "UPDATE mahasiswa SET nim='$nim', nama='$nama', jurusan='$jurusan', email='$email' WHERE id=$id";

    echo mysqli_query($conn, $sql) ? json_encode(['status' => 'success']) : json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    exit;
}
if ($action == 'delete') {
    $id = intval($_POST['id']);
    echo mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = $id") ? json_encode(['status' => 'success']) : json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    exit;
}

// ==========================================
// CONTROLLER: DOSEN
// ==========================================
if ($action == 'list_dosen') {
    $query = mysqli_query($conn, "SELECT * FROM dosen ORDER BY id DESC");
    echo json_encode(mysqli_fetch_all($query, MYSQLI_ASSOC));
    exit;
}
if ($action == 'get_dosen') {
    $id = intval($_GET['id']);
    $query = mysqli_query($conn, "SELECT * FROM dosen WHERE id = $id");
    echo json_encode(mysqli_fetch_assoc($query));
    exit;
}
if ($action == 'save_dosen') {
    $id = $_POST['id'] ?? '';
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    $sql = empty($id) 
        ? "INSERT INTO dosen (nama, alamat) VALUES ('$nama', '$alamat')"
        : "UPDATE dosen SET nama='$nama', alamat='$alamat' WHERE id=$id";

    echo mysqli_query($conn, $sql) ? json_encode(['status' => 'success']) : json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    exit;
}
if ($action == 'delete_dosen') {
    $id = intval($_POST['id']);
    echo mysqli_query($conn, "DELETE FROM dosen WHERE id = $id") ? json_encode(['status' => 'success']) : json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    exit;
}

// ==========================================
// CONTROLLER: MATA KULIAH (MATKUL)
// ==========================================
if ($action == 'list_matkul') {
    $query = mysqli_query($conn, "SELECT * FROM matkul ORDER BY id DESC");
    echo json_encode(mysqli_fetch_all($query, MYSQLI_ASSOC));
    exit;
}
if ($action == 'get_matkul') {
    $id = intval($_GET['id']);
    $query = mysqli_query($conn, "SELECT * FROM matkul WHERE id = $id");
    echo json_encode(mysqli_fetch_assoc($query));
    exit;
}
if ($action == 'save_matkul') {
    $id = $_POST['id'] ?? '';
    $matkul = mysqli_real_escape_string($conn, $_POST['matkul']);
    $sks = intval($_POST['sks']);

    $sql = empty($id) 
        ? "INSERT INTO matkul (matkul, sks) VALUES ('$matkul', $sks)"
        : "UPDATE matkul SET matkul='$matkul', sks=$sks WHERE id=$id";

    echo mysqli_query($conn, $sql) ? json_encode(['status' => 'success']) : json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    exit;
}
if ($action == 'delete_matkul') {
    $id = intval($_POST['id']);
    echo mysqli_query($conn, "DELETE FROM matkul WHERE id = $id") ? json_encode(['status' => 'success']) : json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    exit;
}

// ==========================================
// CONTROLLER: JADWAL (Mendukung JOIN Tabel)
// ==========================================
if ($action == 'list_jadwal') {
    // JOIN digunakan agar kita bisa menampilkan Nama Dosen & Nama Matkul di tabel interface
    $sql = "SELECT jadwal.*, dosen.nama AS nama_dosen, matkul.matkul AS nama_matkul 
            FROM jadwal 
            JOIN dosen ON jadwal.id_dosen = dosen.id 
            JOIN matkul ON jadwal.id_matkul = matkul.id 
            ORDER BY jadwal.id DESC";
    $query = mysqli_query($conn, $sql);
    echo json_encode(mysqli_fetch_all($query, MYSQLI_ASSOC));
    exit;
}
if ($action == 'get_jadwal') {
    $id = intval($_GET['id']);
    $query = mysqli_query($conn, "SELECT * FROM jadwal WHERE id = $id");
    echo json_encode(mysqli_fetch_assoc($query));
    exit;
}
if ($action == 'save_jadwal') {
    $id = $_POST['id'] ?? '';
    $id_dosen = intval($_POST['id_dosen']);
    $id_matkul = intval($_POST['id_matkul']);
    $waktu = mysqli_real_escape_string($conn, $_POST['waktu']);
    $ruang = mysqli_real_escape_string($conn, $_POST['ruang']);

    $sql = empty($id) 
        ? "INSERT INTO jadwal (id_dosen, id_matkul, waktu, ruang) VALUES ($id_dosen, $id_matkul, '$waktu', '$ruang')"
        : "UPDATE jadwal SET id_dosen=$id_dosen, id_matkul=$id_matkul, waktu='$waktu', ruang='$ruang' WHERE id=$id";

    echo mysqli_query($conn, $sql) ? json_encode(['status' => 'success']) : json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    exit;
}
if ($action == 'delete_jadwal') {
    $id = intval($_POST['id']);
    echo mysqli_query($conn, "DELETE FROM jadwal WHERE id = $id") ? json_encode(['status' => 'success']) : json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    exit;
}
?>