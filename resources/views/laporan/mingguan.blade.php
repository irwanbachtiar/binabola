@extends('layouts.app')

@section('title', 'Laporan Mingguan')

@section('page-title', 'Laporan Mingguan - Statistik Siswa Per Minggu')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('laporan.mingguan') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Pilih Bulan</label>
                        <input type="month" class="form-control" name="bulan" value="{{ $bulan }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Tampilkan
                        </button>
                    </div>
                    <div class="col-md-6 text-end">
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
        <h4>Periode: {{ $tanggal->format('F Y') }}</h4>
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

<!-- Statistik Per Minggu -->
<div class="row mb-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Statistik Evaluasi Per Minggu</h5>
            </div>
            <div class="card-body">
                @if($evaluasiPerMinggu->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">Minggu</th>
                                    <th class="text-center">Jumlah Siswa</th>
                                    <th class="text-center">Rata-rata Nilai</th>
                                    <th class="text-center">Nilai Tertinggi</th>
                                    <th class="text-center">Nilai Terendah</th>
                                    <th class="text-center">Total Evaluasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($evaluasiPerMinggu as $minggu => $stat)
                                <tr>
                                    <td class="text-center"><strong>Minggu {{ $minggu }}</strong></td>
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
                    <p class="text-center text-muted py-4">Belum ada data evaluasi untuk bulan ini</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Top 5 Siswa -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-trophy"></i> Top 5 Siswa</h5>
            </div>
            <div class="card-body">
                @if($topSiswa->count() > 0)
                    @foreach($topSiswa as $index => $siswa)
                        <div class="d-flex align-items-center mb-2 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="me-2">
                                @if($index == 0)
                                    <i class="bi bi-trophy-fill text-warning fs-4"></i>
                                @elseif($index == 1)
                                    <i class="bi bi-trophy-fill text-secondary fs-5"></i>
                                @elseif($index == 2)
                                    <i class="bi bi-trophy-fill" style="color: #cd7f32;"></i>
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
</div>
@endsection
