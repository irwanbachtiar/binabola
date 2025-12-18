@extends('layouts.app')

@section('title', 'Statistik Kehadiran')

@section('page-title', 'Statistik Kehadiran')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Statistik Kehadiran Siswa</h5>
                <a href="{{ route('absensi.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <form method="GET" class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Bulan</label>
                        <select class="form-select" name="bulan">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->isoFormat('MMMM') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tahun</label>
                        <select class="form-select" name="tahun">
                            @for($i = date('Y'); $i >= date('Y') - 3; $i--)
                                <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filter
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Hadir</th>
                                <th>Izin</th>
                                <th>Sakit</th>
                                <th>Alpa</th>
                                <th>Total</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswas as $siswa)
                                @php
                                    $total = $siswa->total_hadir + $siswa->total_izin + $siswa->total_sakit + $siswa->total_alpa;
                                    $persentase = $total > 0 ? round(($siswa->total_hadir / $total) * 100, 1) : 0;
                                @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($siswa->foto)
                                            <img src="{{ asset($siswa->foto) }}" alt="Foto" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                                <i class="bi bi-person text-white" style="font-size: 14px;"></i>
                                            </div>
                                        @endif
                                        <strong>{{ $siswa->nama }}</strong>
                                    </div>
                                </td>
                                <td><span class="badge bg-success">{{ $siswa->total_hadir }}</span></td>
                                <td><span class="badge bg-primary">{{ $siswa->total_izin }}</span></td>
                                <td><span class="badge bg-warning">{{ $siswa->total_sakit }}</span></td>
                                <td><span class="badge bg-danger">{{ $siswa->total_alpa }}</span></td>
                                <td><strong>{{ $total }}</strong></td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar 
                                            @if($persentase >= 80) bg-success
                                            @elseif($persentase >= 60) bg-warning
                                            @else bg-danger
                                            @endif" 
                                            style="width: {{ $persentase }}%">
                                            {{ $persentase }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <p class="text-muted">Tidak ada data statistik</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
