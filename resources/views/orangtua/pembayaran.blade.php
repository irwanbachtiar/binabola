@extends('layouts.app')

@section('title', 'Monitoring Pembayaran - ' . $siswa->nama)

@section('page-title')
    <div class="d-flex align-items-center">
        <a href="{{ route('orangtua.dashboard') }}" class="btn btn-outline-secondary btn-sm me-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <span>Monitoring Pembayaran</span>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Student Info -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    @if($siswa->foto && file_exists(public_path($siswa->foto)))
                        <img src="{{ asset($siswa->foto) }}" alt="{{ $siswa->nama }}" 
                             class="rounded-circle" width="80" height="80" 
                             style="object-fit: cover; border: 3px solid #667eea;">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: 3px solid #667eea;">
                            <span class="text-white" style="font-size: 32px; font-weight: bold;">
                                {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                </div>
                <div class="col">
                    <h4 class="mb-1">{{ $siswa->nama }}</h4>
                    <p class="text-muted mb-0">
                        <span class="badge bg-{{ $siswa->kelompok_umur == 'U-7' ? 'info' : 'primary' }} me-2">
                            {{ $siswa->kelompok_umur }}
                        </span>
                        @if($siswa->paketIuran)
                            <span class="badge bg-success">
                                <i class="bi bi-credit-card"></i> {{ $siswa->paketIuran->nama_paket }}
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Pembayaran Bulan Ini -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-calendar-check text-{{ $statusBulanIni ? 'success' : 'warning' }}" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="text-muted mb-1">Status Bulan Ini</h6>
                    <h5 class="mb-0">
                        <span class="badge bg-{{ $statusBulanIni ? 'success' : 'warning' }}">
                            {{ $statusBulanIni ? 'Sudah Bayar' : 'Belum Bayar' }}
                        </span>
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-cash-stack text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="text-muted mb-1">Total Bayar {{ $tahunIni }}</h6>
                    <h5 class="mb-0 text-primary">Rp {{ number_format($totalBayarTahunIni, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-calendar2-check text-success" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="text-muted mb-1">Bulan Terbayar</h6>
                    <h5 class="mb-0 text-success">{{ $jumlahBulanBayar }}/12 Bulan</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-exclamation-triangle text-danger" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="text-muted mb-1">Tunggakan</h6>
                    <h5 class="mb-0 text-danger">{{ $tagihanBelumBayar->count() }} Bulan</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Per Paket Iuran -->
    @if(count($statistikPerPaket) > 0)
    <div class="row mb-4">
        @foreach($statistikPerPaket as $paketId => $stat)
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h6 class="mb-0 text-white">
                        <i class="bi bi-tag"></i> {{ $stat['nama'] }}
                        <span class="badge bg-white text-dark ms-2">{{ $stat['nominal_bulanan'] ? 'Rp ' . number_format($stat['nominal_bulanan'], 0, ',', '.') . '/bulan' : '-' }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <small class="text-muted d-block">Total {{ $tahunIni }}</small>
                            <h6 class="text-primary mb-0">Rp {{ number_format($stat['total_bayar_tahun_ini'], 0, ',', '.') }}</h6>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Terbayar</small>
                            <h6 class="text-success mb-0">{{ $stat['jumlah_bulan_bayar'] }} bulan</h6>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Tunggakan</small>
                            <h6 class="text-danger mb-0">{{ $stat['jumlah_tunggakan'] }} bulan</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Tagihan Belum Bayar - Grouped by Paket -->
    @if($tagihanBelumBayar->count() > 0)
        @foreach($tagihanBelumBayar as $paketId => $tagihans)
            @php
                $paket = $tagihans->first()->paket;
                $totalTagihan = $tagihans->sum('nominal');
            @endphp
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-danger text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-exclamation-circle"></i> Tagihan Belum Bayar - {{ $paket->nama_paket ?? 'Paket Tidak Diketahui' }}
                        </h5>
                        <span class="badge bg-white text-danger">
                            Total: Rp {{ number_format($totalTagihan, 0, ',', '.') }} ({{ $tagihans->count() }} bulan)
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Periode</th>
                                    <th>Nominal</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tagihans as $tagihan)
                                <tr>
                                    <td>
                                        <strong>{{ $tagihan->periode }}</strong>
                                    </td>
                                    <td class="fw-bold text-danger">{{ $tagihan->nominal_format }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($tagihan->jatuh_tempo)->format('d M Y') }}
                                        @if($tagihan->terlambat)
                                            <span class="badge bg-danger ms-1">Terlambat</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ ucfirst(str_replace('_', ' ', $tagihan->status)) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('orangtua.siswa.pelunasan', $siswa->id) }}?tagihan_id={{ $tagihan->id }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-credit-card"></i> Bayar
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    @else
    <div class="alert alert-success border-0 mb-4">
        <i class="bi bi-check-circle"></i> <strong>Tidak ada tunggakan!</strong> Semua tagihan telah dibayarkan.
    </div>
    @endif

    <!-- History Pembayaran - Grouped by Paket -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Pembayaran</h5>
        </div>
        <div class="card-body">
            @if($historyPembayaran->count() > 0)
                @foreach($historyPembayaran as $paketId => $pembayarans)
                    @php
                        $paket = $pembayarans->first()->paket;
                    @endphp
                    <div class="mb-4 {{ !$loop->last ? 'border-bottom pb-4' : '' }}">
                        <h6 class="text-primary mb-3">
                            <i class="bi bi-tag"></i> {{ $paket->nama_paket ?? 'Paket Tidak Diketahui' }}
                            <span class="badge bg-primary ms-2">{{ $pembayarans->count() }} transaksi</span>
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Periode</th>
                                        <th>Tanggal Bayar</th>
                                        <th>Nominal</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                        <th>Bukti</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pembayarans as $pembayaran)
                                    <tr>
                                        <td><strong>{{ $pembayaran->periode }}</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d M Y') }}</td>
                                        <td class="fw-bold text-success">{{ $pembayaran->nominal_format }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ ucfirst($pembayaran->metode_pembayaran) }}</span>
                                        </td>
                                        <td>
                                            @if($pembayaran->status == 'lunas')
                                                <span class="badge bg-success">Lunas</span>
                                            @elseif($pembayaran->status == 'pending')
                                                <span class="badge bg-warning">Menunggu Verifikasi</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($pembayaran->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($pembayaran->bukti_pembayaran)
                                                <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-file-image"></i> Lihat
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @else
            <div class="text-center text-muted py-4">
                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                <p class="mt-2">Belum ada riwayat pembayaran</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
</style>
@endpush
