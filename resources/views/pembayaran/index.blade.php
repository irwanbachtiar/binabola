@extends('layouts.app')

@section('title', 'Dashboard Pembayaran')

@section('page-title', 'Pembayaran Iuran')

@section('content')
<div class="row mb-4">
    <!-- Statistik Cards -->
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-cash-coin fs-1 text-success"></i>
                    </div>
                    <div class="ms-3">
                        <p class="mb-0 text-muted small">Pemasukan Bulan Ini</p>
                        <h4 class="mb-0 text-success">{{ number_format($totalPembayaranBulanIni, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-check-circle fs-1 text-primary"></i>
                    </div>
                    <div class="ms-3">
                        <p class="mb-0 text-muted small">Sudah Bayar</p>
                        <h4 class="mb-0">{{ $jumlahSiswaBayar }}/{{ $totalSiswaAktif }}</h4>
                        <small class="text-muted">Siswa</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
                    </div>
                    <div class="ms-3">
                        <p class="mb-0 text-muted small">Belum Bayar</p>
                        <h4 class="mb-0 text-warning">{{ $siswaBelumBayar }}</h4>
                        <small class="text-muted">Siswa</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-center h-100">
                    <a href="{{ route('pembayaran.create') }}" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-plus-circle"></i> Input Pembayaran
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Tagihan Belum Bayar -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-clock-history text-warning"></i> Tagihan Belum Bayar</h5>
            </div>
            <div class="card-body">
                @if($tagihanBelumBayar->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Siswa</th>
                                    <th>Periode</th>
                                    <th>Nominal</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tagihanBelumBayar as $tagihan)
                                <tr class="{{ $tagihan->terlambat ? 'table-danger' : '' }}">
                                    <td>
                                        <strong>{{ $tagihan->siswa->nama }}</strong>
                                        @if($tagihan->terlambat)
                                            <br><span class="badge bg-danger"><i class="bi bi-exclamation-triangle"></i> Terlambat</span>
                                        @endif
                                    </td>
                                    <td>{{ $tagihan->periode }}</td>
                                    <td>{{ $tagihan->nominal_format }}</td>
                                    <td>{{ $tagihan->jatuh_tempo->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('pembayaran.create', ['siswa_id' => $tagihan->siswa_id]) }}" class="btn btn-sm btn-primary">
                                            Bayar
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2">
                        {{ $tagihanBelumBayar->links() }}
                    </div>
                @else
                    <p class="text-center text-muted py-4">
                        <i class="bi bi-check-circle fs-1 d-block mb-2 text-success"></i>
                        Semua tagihan sudah dibayar!
                    </p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Pembayaran Terakhir -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-receipt text-success"></i> Pembayaran Terakhir</h5>
            </div>
            <div class="card-body">
                @if($pembayaranTerakhir->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($pembayaranTerakhir as $bayar)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $bayar->siswa->nama }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $bayar->periode }} • {{ $bayar->tanggal_bayar->format('d/m/Y') }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <strong class="text-success">{{ $bayar->nominal_format }}</strong>
                                    <br>
                                    <span class="badge bg-secondary">{{ ucfirst($bayar->metode_pembayaran) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-muted py-4">Belum ada pembayaran</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="bi bi-link-45deg"></i> Menu Pembayaran</h5>
                <div class="row g-2">
                    <div class="col-md-3">
                        <a href="{{ route('pembayaran.create') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-plus-circle"></i> Input Pembayaran
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('pembayaran.laporan') }}" class="btn btn-outline-info w-100">
                            <i class="bi bi-file-earmark-bar-graph"></i> Laporan Pembayaran
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-people"></i> Lihat Semua Siswa
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-dark w-100">
                            <i class="bi bi-house"></i> Dashboard Utama
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
