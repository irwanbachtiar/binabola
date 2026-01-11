@extends('layouts.app')

@section('title', 'Laporan Tahunan')

@section('page-title', 'Laporan Tahunan - Statistik Siswa Per Bulan')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('laporan.tahunan') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Pilih Tahun</label>
                        <select class="form-select" name="tahun" required>
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-info w-100">
                            <i class="bi bi-search"></i> Tampilkan
                        </button>
                    </div>
                    <div class="col-md-7 text-end">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <h4>Tahun: {{ $tahun }}</h4>
    </div>
</div>

<!-- Ringkasan -->
<div class="row mb-3">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h6>Total Siswa Aktif</h6>
                <h2>{{ $totalSiswa }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h6>Total Hadir</h6>
                <h2>{{ $absensi['Hadir'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h6>Total Izin/Sakit</h6>
                <h2>{{ ($absensi['Izin'] ?? 0) + ($absensi['Sakit'] ?? 0) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h6>Total Alpa</h6>
                <h2>{{ $absensi['Alpa'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Per Bulan -->
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Statistik Evaluasi Per Bulan</h5>
            </div>
            <div class="card-body">
                @if($evaluasiPerBulan->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Bulan</th>
                                    <th class="text-center">Jumlah Siswa</th>
                                    <th class="text-center">Rata-rata Nilai</th>
                                    <th class="text-center">Nilai Tertinggi</th>
                                    <th class="text-center">Nilai Terendah</th>
                                    <th class="text-center">Total Evaluasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($evaluasiPerBulan as $bulan => $stat)
                                <tr>
                                    <td><strong>{{ \Carbon\Carbon::parse($bulan)->format('F Y') }}</strong></td>
                                    <td class="text-center">{{ $stat['jumlah_siswa'] }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $stat['rata_rata_nilai'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $stat['nilai_tertinggi'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $stat['nilai_terendah'] }}</span>
                                    </td>
                                    <td class="text-center">{{ $stat['total_evaluasi'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-muted py-4">Belum ada data evaluasi untuk tahun ini</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Top 10 Siswa dan Statistik Kategori -->
<div class="row mb-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-trophy"></i> Top 10 Siswa Terbaik</h5>
            </div>
            <div class="card-body">
                @if($topSiswa->count() > 0)
                    @foreach($topSiswa as $index => $siswa)
                        <div class="d-flex align-items-center mb-2 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="me-2" style="width: 30px;">
                                @if($index < 3)
                                    <i class="bi bi-trophy-fill {{ $index == 0 ? 'text-warning' : ($index == 1 ? 'text-secondary' : '') }}" style="{{ $index == 2 ? 'color: #cd7f32;' : '' }}"></i>
                                @else
                                    <span class="text-muted">{{ $index + 1 }}</span>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <strong>{{ $siswa->nama }}</strong>
                            </div>
                            <span class="badge bg-success">{{ $siswa->avg_nilai ? number_format($siswa->avg_nilai, 1) : '-' }}</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-center text-muted">Belum ada data</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Statistik Per Kategori Penilaian</h5>
            </div>
            <div class="card-body">
                @if($statistikKategori->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Kategori</th>
                                    <th class="text-center">Rata-rata</th>
                                    <th class="text-center">Tertinggi</th>
                                    <th class="text-center">Terendah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($statistikKategori as $stat)
                                <tr>
                                    <td>{{ $stat['nama'] }}</td>
                                    <td class="text-center"><span class="badge bg-info">{{ $stat['rata_rata'] }}</span></td>
                                    <td class="text-center"><span class="badge bg-success">{{ $stat['tertinggi'] }}</span></td>
                                    <td class="text-center"><span class="badge bg-danger">{{ $stat['terendah'] }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-muted">Belum ada data</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
