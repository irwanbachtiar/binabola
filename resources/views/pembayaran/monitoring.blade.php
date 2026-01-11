@extends('layouts.app')

@section('title', 'Monitoring Iuran Per Siswa')

@section('page-title', 'Monitoring Iuran Per Siswa')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Monitoring Iuran Tahunan</h5>
                    <form method="GET" action="{{ route('pembayaran.monitoring') }}" class="d-flex gap-2">
                        <select name="tahun" class="form-select form-select-sm" style="width: 120px;">
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="bi bi-search"></i> Tampilkan
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th style="min-width: 200px;">Nama Siswa</th>
                                <th class="text-center" style="width: 100px;">Kelompok</th>
                                <th class="text-center" style="width: 70px;">Jan</th>
                                <th class="text-center" style="width: 70px;">Feb</th>
                                <th class="text-center" style="width: 70px;">Mar</th>
                                <th class="text-center" style="width: 70px;">Apr</th>
                                <th class="text-center" style="width: 70px;">Mei</th>
                                <th class="text-center" style="width: 70px;">Jun</th>
                                <th class="text-center" style="width: 70px;">Jul</th>
                                <th class="text-center" style="width: 70px;">Agu</th>
                                <th class="text-center" style="width: 70px;">Sep</th>
                                <th class="text-center" style="width: 70px;">Okt</th>
                                <th class="text-center" style="width: 70px;">Nov</th>
                                <th class="text-center" style="width: 70px;">Des</th>
                                <th class="text-center" style="width: 80px;">Total Lunas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($monitoringData as $index => $data)
                                @php
                                    $totalLunas = collect($data['status_bulan'])->filter(fn($status) => $status === 'Lunas')->count();
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $data['siswa']->nama }}</strong>
                                        @if($data['siswa']->paketIuran)
                                            <br><small class="text-muted">{{ $data['siswa']->paketIuran->nama_paket }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $data['siswa']->kelompok_umur == 'U-7' ? 'info' : 'primary' }}">
                                            {{ $data['siswa']->kelompok_umur }}
                                        </span>
                                    </td>
                                    @for($bulan = 1; $bulan <= 12; $bulan++)
                                        <td class="text-center">
                                            @if($data['status_bulan'][$bulan] === null)
                                                <span class="text-muted" style="opacity: 0.3;">N/A</span>
                                            @elseif($data['status_bulan'][$bulan] === 'Lunas')
                                                <span class="badge bg-success">Lunas</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    @endfor
                                    <td class="text-center">
                                        <strong class="text-{{ $totalLunas >= 12 ? 'success' : ($totalLunas >= 6 ? 'warning' : 'danger') }}">
                                            {{ $totalLunas }}/12
                                        </strong>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="16" class="text-center py-4">
                                        <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                        <p class="text-muted mb-0">Belum ada data siswa aktif</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white">
                <div class="d-flex gap-3 align-items-center justify-content-between">
                    <div>
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Total Siswa Aktif: <strong>{{ count($monitoringData) }}</strong>
                        </small>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-success">Lunas</span>
                        <span class="text-muted">-</span> Belum Bayar
                    </div>
                    <a href="{{ route('pembayaran.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Summary -->
<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-graph-up"></i> Ringkasan Pembayaran {{ $tahun }}</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    @php
                        $totalSiswa = count($monitoringData);
                        $siswaLunasPerBulan = [];
                        for($b = 1; $b <= 12; $b++) {
                            $siswaLunasPerBulan[$b] = collect($monitoringData)->filter(fn($data) => $data['status_bulan'][$b] === 'Lunas')->count();
                        }
                    @endphp
                    @for($bulan = 1; $bulan <= 12; $bulan++)
                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                            <div class="border rounded p-2">
                                <small class="text-muted d-block">{{ DateTime::createFromFormat('!m', $bulan)->format('M') }}</small>
                                <h5 class="mb-0 {{ $siswaLunasPerBulan[$bulan] >= $totalSiswa * 0.8 ? 'text-success' : 'text-warning' }}">
                                    {{ $siswaLunasPerBulan[$bulan] }}/{{ $totalSiswa }}
                                </h5>
                                <small class="text-muted">{{ $totalSiswa > 0 ? round(($siswaLunasPerBulan[$bulan]/$totalSiswa)*100) : 0 }}%</small>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-responsive {
        overflow-x: auto;
    }
    
    .table td, .table th {
        vertical-align: middle;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush
