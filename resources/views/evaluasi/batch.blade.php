@extends('layouts.app')

@section('title', 'Evaluasi Batch - Sekolah Sepak Bola')

@section('page-title', 'Evaluasi Batch Siswa')

@push('styles')
<style>
    /* Sticky table header */
    .table-sticky {
        position: relative;
    }
    
    .table-sticky thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background: #fff;
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
    }
    
    .table-sticky thead tr:first-child th {
        top: 0;
    }
    
    .table-sticky thead tr:nth-child(2) th {
        top: 50px; /* Adjust based on first row height */
    }
    
    /* Compact input untuk nilai */
    .nilai-input {
        width: 60px !important;
        padding: 4px 6px !important;
        font-size: 14px;
        font-weight: 600;
        border: 2px solid #dee2e6;
        transition: all 0.2s;
    }
    
    .nilai-input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }
    
    .nilai-input:disabled {
        background-color: #f8f9fa !important;
        color: #6c757d;
        border-color: #dee2e6;
    }
    
    /* Kolom siswa fixed */
    .table-sticky tbody td:first-child,
    .table-sticky tbody td:nth-child(2),
    .table-sticky thead th:first-child,
    .table-sticky thead th:nth-child(2) {
        position: sticky;
        left: 0;
        background: #fff;
        z-index: 5;
    }
    
    .table-sticky tbody td:nth-child(2),
    .table-sticky thead th:nth-child(2) {
        left: 50px; /* Width of No column */
    }
    
    .table-sticky tbody tr:hover td:first-child,
    .table-sticky tbody tr:hover td:nth-child(2) {
        background: #f8f9fa;
    }
    
    /* Parent kategori header styling */
    .parent-header {
        font-weight: 700;
        font-size: 13px;
        letter-spacing: 0.5px;
        padding: 12px 8px !important;
    }
    
    /* Sub-kategori header */
    .sub-header {
        font-size: 11px;
        font-weight: 600;
        padding: 8px 4px !important;
        white-space: nowrap;
    }
    
    /* Nama siswa styling */
    .siswa-name {
        font-size: 14px;
        min-width: 180px;
    }

    /* Full-screen saving overlay */
    .saving-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2050;
        color: #fff;
    }
    .saving-overlay .card {
        background: transparent;
        border: none;
        box-shadow: none;
        color: #fff;
    }
    .saving-overlay .spinner-border {
        width: 3rem;
        height: 3rem;
    }
</style>
@endpush

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <div class="mt-2">
            <small>
                <i class="bi bi-arrow-right"></i> Data evaluasi sudah tersimpan dan dapat dilihat di 
                <a href="{{ route('evaluasi.index') }}" class="alert-link">halaman Penilaian Regular</a>
            </small>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Pilih Tanggal Latihan</h5>
    </div>
    <div class="card-body">
        <form id="formSelectDate">
            <div class="row">
                <div class="col-md-4">
                    <label for="tanggal" class="form-label">
                        Tanggal Latihan 
                        <i class="bi bi-info-circle text-muted" data-bs-toggle="tooltip" title="Pilih tanggal latihan untuk melihat siswa yang hadir"></i>
                    </label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                    <input type="hidden" id="minggu" name="minggu" value="{{ date('W') }}">
                </div>
                <div class="col-md-4">
                    <label for="kelompok_umur" class="form-label">
                        Kelompok Umur
                        <i class="bi bi-info-circle text-muted" data-bs-toggle="tooltip" title="Filter siswa berdasarkan kelompok umur"></i>
                    </label>
                    <select class="form-select" id="kelompok_umur" name="kelompok_umur" required>
                        <option value="">Pilih Kelompok</option>
                        <option value="U-7">U-7</option>
                        <option value="U-12">U-12</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tampilkan Siswa
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="siswaContainer" style="display: none;">
    <form id="formBatchEvaluasi" method="POST" action="{{ route('evaluasi.batch.store') }}">
        @csrf
        <input type="hidden" name="tanggal_evaluasi" id="hidden_tanggal">
        <input type="hidden" name="minggu" id="hidden_minggu">
        
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clipboard-data"></i> Evaluasi Siswa - <span id="displayTanggal"></span></h5>
                <div>
                    <span class="badge bg-secondary me-2" id="kelompokUmurBadge" style="display: none;"></span>
                    <span class="badge bg-primary me-2" id="totalSiswa">0 Siswa</span>
                    <span class="badge bg-info me-2" id="totalKategori">0 Kategori</span>
                </div>
            </div>
            <div class="card-body">
                <div id="warningEvaluasi" class="alert alert-warning alert-dismissible fade show" style="display: none;">
                    <i class="bi bi-exclamation-triangle-fill"></i> 
                    <strong>Peringatan:</strong> Tanggal <strong id="tanggalEvaluasi"></strong> sudah dilakukan penilaian sebelumnya (<span id="jumlahEvaluasi"></span> data evaluasi). 
                    <hr class="my-2">
                    <small>
                        <i class="bi bi-info-circle"></i> Jika Anda melanjutkan, data evaluasi yang ada akan <strong>di-UPDATE</strong> dengan nilai baru.
                    </small>
                </div>
                <div id="siswaList"></div>
            </div>
            <div class="card-footer bg-white text-end">
                <button type="button" id="saveAllBtn" class="btn btn-success">
                    <i class="bi bi-save"></i> Simpan Semua Evaluasi
                </button>
            </div>
        </div>
    </form>
</div>

<div id="loadingMessage" class="text-center py-5" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <p class="mt-3 text-muted">Memuat data siswa...</p>
</div>

<div id="noDataMessage" class="alert alert-warning" style="display: none;">
    <i class="bi bi-exclamation-triangle"></i> Tidak ada data absensi pada tanggal tersebut. Silakan pilih tanggal lain atau input absensi terlebih dahulu.
</div>

<!-- Full screen saving overlay (hidden by default) -->
<div id="savingOverlay" class="saving-overlay d-none" aria-hidden="true">
    <div class="card text-center">
        <div class="card-body">
            <div class="spinner-border text-light" role="status" aria-hidden="true"></div>
            <div class="mt-3">Menyimpan penilaian...</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-calculate minggu from tanggal
function calculateWeekNumber(dateString) {
    const date = new Date(dateString);
    const startOfYear = new Date(date.getFullYear(), 0, 1);
    const days = Math.floor((date - startOfYear) / (24 * 60 * 60 * 1000));
    return Math.ceil((days + startOfYear.getDay() + 1) / 7);
}

// Update minggu when tanggal changes
document.getElementById('tanggal').addEventListener('change', function() {
    const minggu = calculateWeekNumber(this.value);
    document.getElementById('minggu').value = minggu;
});

document.getElementById('formSelectDate').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const tanggal = document.getElementById('tanggal').value;
    const kelompokUmur = document.getElementById('kelompok_umur').value;
    const minggu = calculateWeekNumber(tanggal);
    document.getElementById('minggu').value = minggu;
    
    if (!tanggal) {
        alert('Silakan pilih tanggal terlebih dahulu');
        return;
    }
    
    loadSiswa(tanggal, minggu, kelompokUmur);
});

function loadSiswa(tanggal, minggu, kelompokUmur = '') {
    // Show loading
    document.getElementById('loadingMessage').style.display = 'block';
    document.getElementById('siswaContainer').style.display = 'none';
    document.getElementById('noDataMessage').style.display = 'none';
    
    // Fetch siswa yang hadir
    const url = kelompokUmur 
        ? `/evaluasi/batch/siswa?tanggal=${tanggal}&kelompok_umur=${kelompokUmur}`
        : `/evaluasi/batch/siswa?tanggal=${tanggal}`;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            document.getElementById('loadingMessage').style.display = 'none';
            
            if (data.siswa.length === 0) {
                document.getElementById('noDataMessage').style.display = 'block';
                return;
            }
            
            // Check if sudah dinilai
            if (data.sudah_dinilai) {
                document.getElementById('warningEvaluasi').style.display = 'block';
                document.getElementById('jumlahEvaluasi').textContent = data.jumlah_evaluasi;
                document.getElementById('tanggalEvaluasi').textContent = formatTanggal(tanggal);
            }
            
            // Set hidden inputs
            document.getElementById('hidden_tanggal').value = tanggal;
            document.getElementById('hidden_minggu').value = minggu;
            
            // Normalize response to avoid undefined
            data.siswa = Array.isArray(data.siswa) ? data.siswa : [];
            data.kategoris = Array.isArray(data.kategoris) ? data.kategoris : [];
            data.kategori_structure = Array.isArray(data.kategori_structure) ? data.kategori_structure : [];

            // Tampilkan info minggu
            const weekLabel = data.is_last_sunday
                ? `(Minggu ke-${data.week_of_month} - <strong class="text-warning"><i class="bi bi-calendar-check"></i> Minggu Terakhir - Termasuk Kategori Khusus</strong>)` 
                : `(Minggu ke-${data.week_of_month} - Kategori Reguler)`;
            
            document.getElementById('displayTanggal').innerHTML = formatTanggal(tanggal) + ' ' + weekLabel;
            document.getElementById('totalSiswa').textContent = (data.siswa.length || 0) + ' Siswa';
            document.getElementById('totalKategori').textContent = (data.kategoris.length || 0) + ' Sub-Kategori';
            
            // Display kelompok umur badge if filter is applied or show majority
            const kelompokUmurBadge = document.getElementById('kelompokUmurBadge');
            if (kelompokUmur) {
                kelompokUmurBadge.textContent = `Kelompok: ${kelompokUmur}`;
                kelompokUmurBadge.style.display = 'inline-block';
            } else if (data.siswa.length > 0) {
                // Show majority kelompok umur
                const kelompokUmurCount = {};
                data.siswa.forEach(s => {
                    if (s && s.kelompok_umur) {
                        kelompokUmurCount[s.kelompok_umur] = (kelompokUmurCount[s.kelompok_umur] || 0) + 1;
                    }
                });
                const majority = Object.keys(kelompokUmurCount).length ? Object.keys(kelompokUmurCount).reduce((a, b) => 
                    kelompokUmurCount[a] > kelompokUmurCount[b] ? a : b
                ) : '';
                if (majority) kelompokUmurBadge.textContent = `Mayoritas: ${majority}`;
                kelompokUmurBadge.style.display = 'inline-block';
            }
            
            // Generate form dengan hierarki
            let html = '<div class="table-responsive" style="max-height: 600px; overflow-y: auto;"><table class="table table-bordered table-hover table-sticky table-sm">';
            html += '<thead class="table-primary">';
            html += '<tr>';
            html += '<th width="50px" class="text-center align-middle parent-header" rowspan="2">No</th>';
            html += '<th width="200px" class="align-middle parent-header" rowspan="2">Nama Siswa</th>';
            
            // Header kategori parent
            data.kategori_structure.forEach(parent => {
                const childrenCount = Array.isArray(parent.children) ? parent.children.length : 0;
                const parentName = parent && parent.nama ? parent.nama : '';
                const bgColor = parentName === 'Teknik' ? 'bg-primary' : 
                               parentName === 'Etika' ? 'bg-success' : 
                               (parent && parent.minggu_terakhir) ? 'bg-warning' : 'bg-info';
                html += `<th colspan="${childrenCount}" class="text-center ${bgColor} text-white parent-header">${parentName}</th>`;
            });
            html += '</tr>';
            
            // Header sub-kategori
            html += '<tr>';
            data.kategori_structure.forEach(parent => {
                if (!Array.isArray(parent.children)) return;
                parent.children.forEach(child => {
                    const childName = (child && child.nama) ? child.nama : '';
                    html += `<th class="text-center sub-header" style="min-width: 70px;">${childName}</th>`;
                });
            });
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';
            
            // Rows siswa
            data.siswa.forEach((siswa, index) => {
                const isHadir = siswa.status_absensi === 'Hadir';
                const rowClass = !isHadir ? 'table-secondary' : '';
                
                html += `<tr class="${rowClass}">`;
                html += `<td class="text-center align-middle fw-bold">${index + 1}<input type="hidden" name="siswa_ids[]" value="${siswa.id}"></td>`;
                html += `<td class="align-middle siswa-name">`;
                html += `<div class="d-flex align-items-center justify-content-between">`;
                html += `<div><strong style="font-size: 13px;">${siswa.nama}</strong></div>`;
                html += `<div>`;
                html += `<span class="badge bg-secondary" style="font-size: 9px;">${siswa.kelompok_umur}</span> `;
                
                // Badge status absensi
                let badgeClass = 'bg-success';
                if (siswa.status_absensi === 'Izin') badgeClass = 'bg-warning text-dark';
                else if (siswa.status_absensi === 'Sakit') badgeClass = 'bg-info';
                else if (siswa.status_absensi === 'Alpa') badgeClass = 'bg-danger';
                
                html += `<span class="badge ${badgeClass}" style="font-size: 9px;">${siswa.status_absensi}</span>`;
                html += `</div></div>`;
                html += `</td>`;
                
                // Input per sub-kategori (loop melalui struktur hierarki)
                data.kategori_structure.forEach(parent => {
                    if (!Array.isArray(parent.children)) return;
                    parent.children.forEach(child => {
                        // If child or its id is missing, render an empty cell to keep columns aligned
                        if (!child || typeof child.id === 'undefined') {
                            html += '<td class="text-center p-1">-</td>';
                            return;
                        }

                        html += '<td class="text-center p-1">';

                        // Tentukan nilai default
                        let defaultValue;
                        if (!isHadir) {
                            // Tidak hadir = 0 (akan diproses otomatis di backend)
                            defaultValue = 0;
                        } else {
                            // Hadir = nilai terakhir atau 80
                            defaultValue = (siswa.last_nilai && typeof siswa.last_nilai[child.id] !== 'undefined')
                                ? siswa.last_nilai[child.id]
                                : 80;
                        }

                        // Gunakan readonly alih-alih disabled agar tetap terkirim ke backend
                        const readonlyAttr = !isHadir ? 'readonly' : '';
                        const bgClass = !isHadir ? 'bg-light' : '';
                        const cursorStyle = !isHadir ? 'cursor: not-allowed;' : '';

                        html += `<input type="number" class="form-control nilai-input ${bgClass}" name="nilai[${siswa.id}][${child.id}]" min="0" max="100" ${readonlyAttr} value="${defaultValue}" placeholder="-" step="1" style="${cursorStyle}">`;
                        html += '</td>';
                    });
                });
                
                html += '</tr>';
            });
            
            html += '</tbody>';
            html += '</table></div>';
            
            document.getElementById('siswaList').innerHTML = html;
            document.getElementById('siswaContainer').style.display = 'block';
        })
        .catch(error => {
            document.getElementById('loadingMessage').style.display = 'none';
            alert('Terjadi kesalahan saat memuat data: ' + error.message);
        });
}

function formatTanggal(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}

// Initialize Bootstrap tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Form submit
document.getElementById('formBatchEvaluasi').addEventListener('submit', function(e) {
    // Validation only (submission done via confirmation modal)
    const inputs = this.querySelectorAll('input[type="number"]:not([readonly])');
    let allFilled = true;

    inputs.forEach(input => {
        if (!input.value || input.value === '') {
            allFilled = false;
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    });

    if (!allFilled) {
        e.preventDefault();
        alert('Mohon isi semua nilai evaluasi untuk siswa yang hadir');
        return false;
    }

    // Allow submission when called programmatically after confirmation
    return true;
});

// Confirmation modal markup (inserted dynamically)
const confirmModalHtml = `
<div class="modal fade" id="confirmSaveModal" tabindex="-1" aria-labelledby="confirmSaveModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmSaveModalLabel">Konfirmasi Simpan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
            <div class="modal-body">
                Simpan penilaian ?
            </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="confirmSaveBtn">OK</button>
      </div>
    </div>
  </div>
</div>`;

document.body.insertAdjacentHTML('beforeend', confirmModalHtml);

const confirmSaveModalEl = document.getElementById('confirmSaveModal');
const bootstrapModal = confirmSaveModalEl ? new bootstrap.Modal(confirmSaveModalEl) : null;

// Save button click: validate, then show modal
document.getElementById('saveAllBtn').addEventListener('click', function(e) {
    const form = document.getElementById('formBatchEvaluasi');
    const inputs = form.querySelectorAll('input[type="number"]:not([readonly])');
    let allFilled = true;
    inputs.forEach(input => {
        if (!input.value || input.value === '') {
            allFilled = false;
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    });

    if (!allFilled) {
        alert('Mohon isi semua nilai evaluasi untuk siswa yang hadir');
        return;
    }

    // Show modal
    if (bootstrapModal) bootstrapModal.show();
});

// When user confirms in modal, disable save button, show spinner, then submit the form
document.body.addEventListener('click', function(e) {
    if (e.target && e.target.id === 'confirmSaveBtn') {
        // hide modal
        if (bootstrapModal) bootstrapModal.hide();

        // prevent double submit by disabling save button
        const saveBtn = document.getElementById('saveAllBtn');
        if (saveBtn) {
            saveBtn.disabled = true;
        }

        // show full-screen saving overlay (centered spinner)
        const overlay = document.getElementById('savingOverlay');
        if (overlay) {
            overlay.classList.remove('d-none');
            overlay.setAttribute('aria-hidden', 'false');
        }

        // submit form after short delay so overlay can render
        setTimeout(function() {
            document.getElementById('formBatchEvaluasi').submit();
        }, 150);
    }
});
</script>
@endpush
