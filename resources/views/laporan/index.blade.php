@extends('layouts.app')

@section('title', 'Laporan - Sekolah Sepak Bola')

@section('page-title', 'Laporan')

@section('content')
<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-calendar-month text-primary" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-3">Laporan Bulanan</h5>
                <p class="card-text text-muted small">Statistik siswa per minggu dalam 1 bulan</p>
                <a href="{{ route('laporan.mingguan') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-file-earmark-text"></i> Lihat Laporan
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-bar-chart-fill text-danger" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-3">Summary Bulanan</h5>
                <p class="card-text text-muted small">Daftar siswa dengan rata-rata per kategori</p>
                <a href="{{ route('laporan.bulanan') }}" class="btn btn-danger btn-sm">
                    <i class="bi bi-file-earmark-bar-graph"></i> Lihat Laporan
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-calendar2-range text-success" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-3">Laporan 3 Bulanan</h5>
                <p class="card-text text-muted small">Statistik siswa per bulan dalam 1 triwulan</p>
                <a href="{{ route('laporan.triwulan') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-file-earmark-text"></i> Lihat Laporan
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-calendar3 text-info" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-3">Laporan Tahunan</h5>
                <p class="card-text text-muted small">Statistik siswa per bulan dalam 1 tahun</p>
                <a href="{{ route('laporan.tahunan') }}" class="btn btn-info btn-sm">
                    <i class="bi bi-file-earmark-text"></i> Lihat Laporan
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-person-badge text-warning" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-3">Laporan Per Siswa</h5>
                <p class="card-text text-muted small">Laporan detail kehadiran dan evaluasi per siswa</p>
                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalPilihSiswa">
                    <i class="bi bi-file-earmark-person"></i> Pilih Siswa
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informasi</h5>
    </div>
    <div class="card-body">
        <p class="mb-2"><strong>Laporan Bulanan:</strong> Menampilkan statistik evaluasi dan absensi siswa per minggu dalam 1 bulan yang dipilih.</p>
        <p class="mb-2"><strong>Summary Bulanan:</strong> Menampilkan daftar semua siswa dengan nilai rata-rata per kategori dalam 1 bulan, diurutkan dari nilai tertinggi.</p>
        <p class="mb-2"><strong>Laporan 3 Bulanan:</strong> Menampilkan statistik evaluasi dan absensi siswa per bulan dalam 1 triwulan (3 bulan).</p>
        <p class="mb-2"><strong>Laporan Tahunan:</strong> Menampilkan statistik evaluasi dan absensi siswa per bulan dalam 1 tahun, termasuk ranking siswa terbaik.</p>
        <p class="mb-0"><strong>Laporan Per Siswa:</strong> Menampilkan detail lengkap kehadiran dan evaluasi siswa beserta grafik perkembangan. Laporan dapat di-export ke PDF.</p>
    </div>
</div>

<!-- Modal Pilih Siswa -->
<div class="modal fade" id="modalPilihSiswa" tabindex="-1" aria-labelledby="modalPilihSiswaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPilihSiswaLabel">
                    <i class="bi bi-person-badge"></i> Pilih Siswa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="selectSiswa" class="form-label">Pilih siswa untuk melihat laporan detail:</label>
                    <select class="form-select" id="selectSiswa">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach(\App\Models\Siswa::orderBy('nama')->get() as $siswa)
                        <option value="{{ $siswa->id }}">{{ $siswa->nama }} - {{ $siswa->minat_posisi_string }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle"></i> Laporan akan menampilkan statistik kehadiran, grafik perkembangan nilai, dan dapat di-export ke PDF.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-warning" onclick="lihatLaporanSiswa()">
                    <i class="bi bi-file-earmark-text"></i> Lihat Laporan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function lihatLaporanSiswa() {
    const siswaId = document.getElementById('selectSiswa').value;
    
    if (!siswaId) {
        alert('Silakan pilih siswa terlebih dahulu');
        return;
    }
    
    window.location.href = `/laporan/siswa/${siswaId}`;
}
</script>
@endsection
