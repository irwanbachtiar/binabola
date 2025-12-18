@extends('layouts.app')

@section('title', 'Riwayat Absensi')

@section('page-title', 'Riwayat Absensi')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Absensi</h5>
                <a href="{{ route('absensi.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <form method="GET" class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="tanggal_mulai" value="{{ $tanggalMulai }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="tanggal_akhir" value="{{ $tanggalAkhir }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filter
                        </button>
                    </div>
                </form>

                @forelse($absensis as $tanggal => $absensiPerHari)
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong>{{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM YYYY') }}</strong>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Siswa</th>
                                            <th>Sesi</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($absensiPerHari as $absensi)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $absensi->siswa->nama }}</td>
                                            <td><span class="badge bg-info">{{ $absensi->sesi }}</span></td>
                                            <td>
                                                @if($absensi->status == 'Hadir')
                                                    <span class="badge bg-success">✓ Hadir</span>
                                                @elseif($absensi->status == 'Izin')
                                                    <span class="badge bg-primary">ℹ Izin</span>
                                                @elseif($absensi->status == 'Sakit')
                                                    <span class="badge bg-warning">+ Sakit</span>
                                                @else
                                                    <span class="badge bg-danger">✗ Alpa</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <p class="text-muted">Tidak ada data absensi pada periode ini</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
