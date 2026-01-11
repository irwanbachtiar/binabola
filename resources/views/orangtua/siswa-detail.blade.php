@extends('layouts.app')

@section('title', 'Detail Siswa - ' . $siswa->nama)

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <a href="{{ route('orangtua.dashboard') }}" class="btn btn-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            @if($siswa->foto)
                                <img src="{{ asset('uploads/siswa/' . $siswa->foto) }}" 
                                     alt="{{ $siswa->nama }}" 
                                     class="img-fluid rounded-circle mb-3" 
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 150px; height: 150px; font-size: 60px;">
                                    {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <div class="col-md-9">
                            <h3>{{ $siswa->nama }}</h3>
                            <span class="badge bg-{{ $siswa->status == 'aktif' ? 'success' : 'secondary' }} mb-3">
                                {{ ucfirst($siswa->status) }}
                            </span>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Tanggal Lahir:</strong><br>
                                    {{ $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d F Y') : '-' }}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Umur:</strong><br>
                                    {{ $siswa->umur_detail }}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Email:</strong><br>
                                    {{ $siswa->email ?? '-' }}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Telepon:</strong><br>
                                    {{ $siswa->telepon ?? '-' }}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Tinggi Badan:</strong><br>
                                    {{ $siswa->tinggi_badan ? $siswa->tinggi_badan . ' cm' : '-' }}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Berat Badan:</strong><br>
                                    {{ $siswa->berat_badan ? $siswa->berat_badan . ' kg' : '-' }}
                                </div>
                                <div class="col-12 mb-3">
                                    <strong>Minat Posisi:</strong><br>
                                    {{ $siswa->minat_posisi_string }}
                                </div>
                                <div class="col-12 mb-3">
                                    <strong>Alamat:</strong><br>
                                    {{ $siswa->alamat ?? '-' }}
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-3">
                                <a href="{{ route('orangtua.siswa.absensi', $siswa->id) }}" class="btn btn-primary">
                                    <i class="bi bi-calendar-check"></i> Lihat Absensi
                                </a>
                                <a href="{{ route('orangtua.siswa.evaluasi', $siswa->id) }}" class="btn btn-primary">
                                    <i class="bi bi-clipboard-data"></i> Lihat Evaluasi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
