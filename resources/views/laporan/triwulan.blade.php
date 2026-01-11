@extends('layouts.app')

@section('title', 'Laporan 3 Bulanan')

@section('page-title', 'Laporan 3 Bulanan - Statistik Siswa Per Bulan')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('laporan.triwulan') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tahun</label>
                        <select class="form-select" name="tahun" required>
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Triwulan</label>
                        <select class="form-select" name="triwulan" required>
                            <option value="1" {{ $triwulan == 1 ? 'selected' : '' }}>Triwulan 1 (Jan-Mar)</option>
                            <option value="2" {{ $triwulan == 2 ? 'selected' : '' }}>Triwulan 2 (Apr-Jun)</option>
                            <option value="3" {{ $triwulan == 3 ? 'selected' : '' }}>Triwulan 3 (Jul-Sep)</option>
                            <option value="4" {{ $triwulan == 4 ? 'selected' : '' }}>Triwulan 4 (Okt-Des)</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-search"></i> Tampilkan
                        </button>
                    </div>
                    <div class="col-md-4 text-end">
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
        <h4>Periode: {{ $tanggalAwal->format('F Y') }} - {{ $tanggalAkhir->format('F Y') }}</h4>
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
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Statistik Evaluasi Per Bulan</h5>
            </div>
            <div class="card-body">
                @if($evaluasiPerBulan->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
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
                    <p class="text-center text-muted py-4">Belum ada data evaluasi untuk periode ini</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Top 10 Siswa -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-trophy"></i> Top 10 Siswa</h5>
            </div>
            <div class="card-body" style="max-height: 500px; overflow-y: auto;">
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
                                <strong style="font-size: 14px;">{{ $siswa->nama }}</strong>
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
