<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAKAD - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">SIAKAD Universitas</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">Halo, <strong><?= htmlspecialchars($_SESSION['username']); ?></strong></span>
                <a href="logout.php" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin keluar?')">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <ul class="nav nav-pills mb-4 shadow-sm p-2 bg-white rounded" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-mahasiswa" data-bs-toggle="pill" data-bs-target="#konten-mahasiswa" type="button" role="tab" onclick="loadMahasiswa()">Data Mahasiswa</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-dosen" data-bs-toggle="pill" data-bs-target="#konten-dosen" type="button" role="tab" onclick="loadDosen()">Data Dosen</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-matkul" data-bs-toggle="pill" data-bs-target="#konten-matkul" type="button" role="tab" onclick="loadMatkul()">Data Mata Kuliah</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-jadwal" data-bs-toggle="pill" data-bs-target="#konten-jadwal" type="button" role="tab" onclick="loadJadwal()">Jadwal Kuliah</button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="konten-mahasiswa" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>Daftar Mahasiswa</h3>
                    <button class="btn btn-primary" onclick="siapkanTambahMhs()">Tambah Mahasiswa</button>
                </div>
                <div class="card shadow-sm border-0"><div class="card-body p-0"><div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark"><tr><th>No</th><th>NIM</th><th>Nama</th><th>Jurusan</th><th>Email</th><th class="text-center">Aksi</th></tr></thead>
                        <tbody id="tempat-mahasiswa"></tbody>
                    </table>
                </div></div></div>
            </div>

            <div class="tab-pane fade" id="konten-dosen" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>Daftar Dosen</h3>
                    <button class="btn btn-primary" onclick="siapkanTambahDosen()">Tambah Dosen</button>
                </div>
                <div class="card shadow-sm border-0"><div class="card-body p-0"><div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark"><tr><th>No</th><th>Nama Dosen</th><th>Alamat</th><th class="text-center">Aksi</th></tr></thead>
                        <tbody id="tempat-dosen"></tbody>
                    </table>
                </div></div></div>
            </div>

            <div class="tab-pane fade" id="konten-matkul" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>Daftar Mata Kuliah</h3>
                    <button class="btn btn-primary" onclick="siapkanTambahMatkul()">Tambah Matkul</button>
                </div>
                <div class="card shadow-sm border-0"><div class="card-body p-0"><div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark"><tr><th>No</th><th>Mata Kuliah</th><th>SKS</th><th class="text-center">Aksi</th></tr></thead>
                        <tbody id="tempat-matkul"></tbody>
                    </table>
                </div></div></div>
            </div>

            <div class="tab-pane fade" id="konten-jadwal" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>Jadwal Perkuliahan</h3>
                    <button class="btn btn-primary" onclick="siapkanTambahJadwal()">Tambah Jadwal</button>
                </div>
                <div class="card shadow-sm border-0"><div class="card-body p-0"><div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark"><tr><th>No</th><th>Dosen Pengampu</th><th>Mata Kuliah</th><th>Waktu</th><th>Ruangan</th><th class="text-center">Aksi</th></tr></thead>
                        <tbody id="tempat-jadwal"></tbody>
                    </table>
                </div></div></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalMhs" tabindex="-1"><div class="modal-dialog"><form id="formMhs" onsubmit="simpanMhs(event)" class="modal-content">
        <div class="modal-header"><h5 class="modal-title" id="titleMhs">Form Mahasiswa</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <input type="hidden" name="id" id="mhs_id">
            <div class="mb-3"><label class="form-label">NIM</label><input type="text" class="form-control" name="nim" id="mhs_nim" required></div>
            <div class="mb-3"><label class="form-label">Nama Lengkap</label><input type="text" class="form-control" name="nama" id="mhs_nama" required></div>
            <div class="mb-3"><label class="form-label">Jurusan</label><input type="text" class="form-control" name="jurusan" id="mhs_jurusan" required></div>
            <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email" id="mhs_email" required></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form></div></div>

    <div class="modal fade" id="modalDosen" tabindex="-1"><div class="modal-dialog"><form id="formDosen" onsubmit="simpanDosen(event)" class="modal-content">
        <div class="modal-header"><h5 class="modal-title" id="titleDosen">Form Dosen</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <input type="hidden" name="id" id="dosen_id">
            <div class="mb-3"><label class="form-label">Nama Dosen</label><input type="text" class="form-control" name="nama" id="dosen_nama" required></div>
            <div class="mb-3"><label class="form-label">Alamat</label><textarea class="form-control" name="alamat" id="dosen_alamat" required></textarea></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form></div></div>

    <div class="modal fade" id="modalMatkul" tabindex="-1"><div class="modal-dialog"><form id="formMatkul" onsubmit="simpanMatkul(event)" class="modal-content">
        <div class="modal-header"><h5 class="modal-title" id="titleMatkul">Form Mata Kuliah</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <input type="hidden" name="id" id="matkul_id">
            <div class="mb-3"><label class="form-label">Nama Mata Kuliah</label><input type="text" class="form-control" name="matkul" id="matkul_nama" required></div>
            <div class="mb-3"><label class="form-label">Jumlah SKS</label><input type="number" class="form-control" name="sks" id="matkul_sks" required min="1" max="6"></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form></div></div>

    <div class="modal fade" id="modalJadwal" tabindex="-1"><div class="modal-dialog"><form id="formJadwal" onsubmit="simpanJadwal(event)" class="modal-content">
        <div class="modal-header"><h5 class="modal-title" id="titleJadwal">Form Jadwal</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <input type="hidden" name="id" id="jadwal_id">
            <div class="mb-3">
                <label class="form-label">Dosen Pengampu</label>
                <select class="form-select" name="id_dosen" id="jadwal_dosen" required></select>
            </div>
            <div class="mb-3">
                <label class="form-label">Mata Kuliah</label>
                <select class="form-select" name="id_matkul" id="jadwal_matkul" required></select>
            </div>
            <div class="mb-3"><label class="form-label">Waktu (Hari, Jam)</label><input type="text" class="form-control" name="waktu" id="jadwal_waktu" placeholder="Contoh: Senin, 08:00 - 10:00" required></div>
            <div class="mb-3"><label class="form-label">Ruang Kelas</label><input type="text" class="form-control" name="ruang" id="jadwal_ruang" placeholder="Contoh: Lab ICT 3 / Ruang 402" required></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form></div></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>