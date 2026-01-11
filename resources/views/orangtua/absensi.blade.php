@extends('layouts.app')

@section('title', 'Absensi - ' . $siswa->nama)

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <a href="{{ route('orangtua.dashboard') }}" class="btn btn-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            <div class="d-flex align-items-center mb-4">
                @if($siswa->foto)
                    <img src="{{ asset($siswa->foto) }}" alt="{{ $siswa->nama }}" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                @else
                    <div class="rounded-circle text-white d-inline-flex align-items-center justify-content-center me-3" 
                         style="width: 60px; height: 60px; font-size: 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h3 class="mb-0">Absensi: {{ $siswa->nama }}</h3>
                    <p class="text-muted mb-0">{{ $siswa->umur_detail }} • {{ $siswa->minat_posisi_string }}</p>
                </div>
            </div>

            <!-- Statistik Absensi 30 Hari -->
            <div class="row mb-4">
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body text-center p-3">
                            <h5 class="fw-bold mb-1">{{ $stats['total'] }}</h5>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="card shadow-sm border-success">
                        <div class="card-body text-center p-3">
                            <h5 class="fw-bold text-success mb-1">{{ $stats['hadir'] }}</h5>
                            <small class="text-muted">Hadir</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="card shadow-sm border-warning">
                        <div class="card-body text-center p-3">
                            <h5 class="fw-bold text-warning mb-1">{{ $stats['izin'] }}</h5>
                            <small class="text-muted">Izin</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="card shadow-sm border-info">
                        <div class="card-body text-center p-3">
                            <h5 class="fw-bold text-info mb-1">{{ $stats['sakit'] }}</h5>
                            <small class="text-muted">Sakit</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="card shadow-sm border-danger">
                        <div class="card-body text-center p-3">
                            <h5 class="fw-bold text-danger mb-1">{{ $stats['alpa'] }}</h5>
                            <small class="text-muted">Alpa</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="card shadow-sm bg-primary text-white">
                        <div class="card-body text-center p-3">
                            <h5 class="fw-bold mb-1">{{ $stats['persentase_hadir'] }}%</h5>
                            <small>Kehadiran</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Riwayat Absensi -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Absensi (30 Hari Terakhir)</h5>
                </div>
                <div class="card-body">
                    @if($absensis->isEmpty())
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Belum ada data absensi.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="20%">Tanggal</th>
                                        <th width="15%">Sesi</th>
                                        <th width="15%">Status</th>
                                        <th width="45%">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($absensis as $index => $absensi)
                                        <tr>
                                            <td>{{ $absensis->firstItem() + $index }}</td>
                                            <td>
                                                <strong>{{ $absensi->tanggal->format('d M Y') }}</strong><br>
                                                <small class="text-muted">{{ $absensi->tanggal->isoFormat('dddd') }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $absensi->sesi }}</span>
                                            </td>
                                            <td>
                                                @if($absensi->status == 'Hadir')
                                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Hadir</span>
                                                @elseif($absensi->status == 'Izin')
                                                    <span class="badge bg-warning"><i class="bi bi-exclamation-circle"></i> Izin</span>
                                                @elseif($absensi->status == 'Sakit')
                                                    <span class="badge bg-info"><i class="bi bi-thermometer"></i> Sakit</span>
                                                @else
                                                    <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Alpa</span>
                                                @endif
                                            </td>
                                            <td>{{ $absensi->keterangan ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $absensis->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
