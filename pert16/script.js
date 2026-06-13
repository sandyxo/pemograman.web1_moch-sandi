document.addEventListener('DOMContentLoaded', () => {
    loadMahasiswa(); // Load default tab pertama kali
});

// Inisialisasi Seluruh Objek Modal Bootstrap
const mhsModal = new bootstrap.Modal(document.getElementById('modalMhs'));
const dosenModal = new bootstrap.Modal(document.getElementById('modalDosen'));
const matkulModal = new bootstrap.Modal(document.getElementById('modalMatkul'));
const jadwalModal = new bootstrap.Modal(document.getElementById('modalJadwal'));

// =========================================================================
// ENGINE MODULE: MAHASISWA
// =========================================================================
function loadMahasiswa() {
    fetch('api.php?action=list')
        .then(res => res.json())
        .then(data => {
            let html = data.length === 0 ? '<tr><td colspan="6" class="text-center p-3 text-muted">Tidak ada data.</td></tr>' : '';
            data.forEach((mhs, idx) => {
                html += `<tr>
                    <td>${idx + 1}</td><td>${mhs.nim}</td><td>${mhs.nama}</td><td>${mhs.jurusan}</td><td>${mhs.email}</td>
                    <td class="text-center">
                        <button class="btn btn-warning btn-sm" onclick="siapkanEditMhs(${mhs.id})">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="hapusMhs(${mhs.id})">Hapus</button>
                    </td>
                </tr>`;
            });
            document.getElementById('tempat-mahasiswa').innerHTML = html;
        });
}

function siapkanTambahMhs() {
    document.getElementById('titleMhs').innerText = 'Tambah Mahasiswa';
    document.getElementById('formMhs').reset();
    document.getElementById('mhs_id').value = '';
    mhsModal.show();
}

function siapkanEditMhs(id) {
    document.getElementById('titleMhs').innerText = 'Ubah Mahasiswa';
    fetch(`api.php?action=get_single&id=${id}`).then(res => res.json()).then(data => {
        document.getElementById('mhs_id').value = data.id;
        document.getElementById('mhs_nim').value = data.nim;
        document.getElementById('mhs_nama').value = data.nama;
        document.getElementById('mhs_jurusan').value = data.jurusan;
        document.getElementById('mhs_email').value = data.email;
        mhsModal.show();
    });
}

function simpanMhs(e) {
    e.preventDefault();
    fetch('api.php?action=save', { method: 'POST', body: new FormData(e.target) }).then(res => res.json()).then(res => {
        if(res.status === 'success') { mhsModal.hide(); loadMahasiswa(); } else { alert(res.message); }
    });
}

function hapusMhs(id) {
    if(confirm('Hapus permanen mahasiswa ini?')) {
        let fd = new FormData(); fd.append('id', id);
        fetch('api.php?action=delete', { method: 'POST', body: fd }).then(res => res.json()).then(() => loadMahasiswa());
    }
}

// =========================================================================
// ENGINE MODULE: DOSEN
// =========================================================================
function loadDosen() {
    fetch('api.php?action=list_dosen').then(res => res.json()).then(data => {
        let html = data.length === 0 ? '<tr><td colspan="4" class="text-center p-3 text-muted">Tidak ada data.</td></tr>' : '';
        data.forEach((dsn, idx) => {
            html += `<tr>
                <td>${idx + 1}</td><td>${dsn.nama}</td><td>${dsn.alamat}</td>
                <td class="text-center">
                    <button class="btn btn-warning btn-sm" onclick="siapkanEditDosen(${dsn.id})">Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="hapusDosen(${dsn.id})">Hapus</button>
                </td>
            </tr>`;
        });
        document.getElementById('tempat-dosen').innerHTML = html;
    });
}

function siapkanTambahDosen() {
    document.getElementById('titleDosen').innerText = 'Tambah Dosen';
    document.getElementById('formDosen').reset();
    document.getElementById('dosen_id').value = '';
    dosenModal.show();
}

function siapkanEditDosen(id) {
    document.getElementById('titleDosen').innerText = 'Ubah Dosen';
    fetch(`api.php?action=get_dosen&id=${id}`).then(res => res.json()).then(data => {
        document.getElementById('dosen_id').value = data.id;
        document.getElementById('dosen_nama').value = data.nama;
        document.getElementById('dosen_alamat').value = data.alamat;
        dosenModal.show();
    });
}

function simpanDosen(e) {
    e.preventDefault();
    fetch('api.php?action=save_dosen', { method: 'POST', body: new FormData(e.target) }).then(res => res.json()).then(res => {
        if(res.status === 'success') { dosenModal.hide(); loadDosen(); } else { alert(res.message); }
    });
}

function hapusDosen(id) {
    if(confirm('Hapus dosen? Menghapus dosen akan menghapus jadwal terkait.')) {
        let fd = new FormData(); fd.append('id', id);
        fetch('api.php?action=delete_dosen', { method: 'POST', body: fd }).then(res => res.json()).then(() => loadDosen());
    }
}

// =========================================================================
// ENGINE MODULE: MATA KULIAH (MATKUL)
// =========================================================================
function loadMatkul() {
    fetch('api.php?action=list_matkul').then(res => res.json()).then(data => {
        let html = data.length === 0 ? '<tr><td colspan="4" class="text-center p-3 text-muted">Tidak ada data.</td></tr>' : '';
        data.forEach((mk, idx) => {
            html += `<tr>
                <td>${idx + 1}</td><td>${mk.matkul}</td><td>${mk.sks} SKS</td>
                <td class="text-center">
                    <button class="btn btn-warning btn-sm" onclick="siapkanEditMatkul(${mk.id})">Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="hapusMatkul(${mk.id})">Hapus</button>
                </td>
            </tr>`;
        });
        document.getElementById('tempat-matkul').innerHTML = html;
    });
}

function siapkanTambahMatkul() {
    document.getElementById('titleMatkul').innerText = 'Tambah Mata Kuliah';
    document.getElementById('formMatkul').reset();
    document.getElementById('matkul_id').value = '';
    matkulModal.show();
}

function siapkanEditMatkul(id) {
    document.getElementById('titleMatkul').innerText = 'Ubah Mata Kuliah';
    fetch(`api.php?action=get_matkul&id=${id}`).then(res => res.json()).then(data => {
        document.getElementById('matkul_id').value = data.id;
        document.getElementById('matkul_nama').value = data.matkul;
        document.getElementById('matkul_sks').value = data.sks;
        matkulModal.show();
    });
}

function simpanMatkul(e) {
    e.preventDefault();
    fetch('api.php?action=save_matkul', { method: 'POST', body: new FormData(e.target) }).then(res => res.json()).then(res => {
        if(res.status === 'success') { matkulModal.hide(); loadMatkul(); } else { alert(res.message); }
    });
}

function hapusMatkul(id) {
    if(confirm('Hapus matkul? Menghapus matkul akan menghapus jadwal terkait.')) {
        let fd = new FormData(); fd.append('id', id);
        fetch('api.php?action=delete_matkul', { method: 'POST', body: fd }).then(res => res.json()).then(() => loadMatkul());
    }
}

// =========================================================================
// ENGINE MODULE: JADWAL (Mendukung Dropdown Pilihan Dosen & Matkul Dinamis)
// =========================================================================
function loadJadwal() {
    fetch('api.php?action=list_jadwal').then(res => res.json()).then(data => {
        let html = data.length === 0 ? '<tr><td colspan="6" class="text-center p-3 text-muted">Tidak ada jadwal perkuliahan.</td></tr>' : '';
        data.forEach((jdw, idx) => {
            html += `<tr>
                <td>${idx + 1}</td><td><strong>${jdw.nama_dosen}</strong></td><td>${jdw.nama_kuliah || jdw.nama_matkul}</td><td>${jdw.waktu}</td><td><span class="badge bg-secondary">${jdw.ruang}</span></td>
                <td class="text-center">
                    <button class="btn btn-warning btn-sm" onclick="siapkanEditJadwal(${jdw.id})">Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="hapusJadwal(${jdw.id})">Hapus</button>
                </td>
            </tr>`;
        });
        document.getElementById('tempat-jadwal').innerHTML = html;
    });
}

// Fungsi pembantu mengisi relasi option-select di form modal jadwal
function pasokDropdownJadwal(callbackSelect) {
    Promise.all([
        fetch('api.php?action=list_dosen').then(res => res.json()),
        fetch('api.php?action=list_matkul').then(res => res.json())
    ]).then(([dosen, matkul]) => {
        let dOpt = '<option value="">-- Pilih Dosen --</option>';
        dosen.forEach(d => dOpt += `<option value="${d.id}">${d.nama}</option>`);
        document.getElementById('jadwal_dosen').innerHTML = dOpt;

        let mOpt = '<option value="">-- Pilih Matkul --</option>';
        matkul.forEach(m => mOpt += `<option value="${m.id}">${m.matkul} (${m.sks} SKS)</option>`);
        document.getElementById('jadwal_matkul').innerHTML = mOpt;

        if(callbackSelect) callbackSelect();
    });
}

function siapkanTambahJadwal() {
    document.getElementById('titleJadwal').innerText = 'Tambah Jadwal Kuliah';
    document.getElementById('formJadwal').reset();
    document.getElementById('jadwal_id').value = '';
    pasokDropdownJadwal(() => { jadwalModal.show(); });
}

function siapkanEditJadwal(id) {
    document.getElementById('titleJadwal').innerText = 'Ubah Jadwal Kuliah';
    pasokDropdownJadwal(() => {
        fetch(`api.php?action=get_jadwal&id=${id}`).then(res => res.json()).then(data => {
            document.getElementById('jadwal_id').value = data.id;
            document.getElementById('jadwal_dosen').value = data.id_dosen;
            document.getElementById('jadwal_matkul').value = data.id_matkul;
            document.getElementById('jadwal_waktu').value = data.waktu;
            document.getElementById('jadwal_ruang').value = data.ruang;
            jadwalModal.show();
        });
    });
}

function simpanJadwal(e) {
    e.preventDefault();
    fetch('api.php?action=save_jadwal', { method: 'POST', body: new FormData(e.target) }).then(res => res.json()).then(res => {
        if(res.status === 'success') { jadwalModal.hide(); loadJadwal(); } else { alert(res.message); }
    });
}

function hapusJadwal(id) {
    if(confirm('Hapus jadwal kuliah ini?')) {
        let fd = new FormData(); fd.append('id', id);
        fetch('api.php?action=delete_jadwal', { method: 'POST', body: fd }).then(res => res.json()).then(() => loadJadwal());
    }
}