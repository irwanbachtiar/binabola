@extends('layouts.app')

@section('title', 'Bina Bola - Sekolah Sepak Bola')
@section('page-title', '')

@section('content')
<div class="container py-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="display-4 fw-bold">Bina Bola</h1>
            <p class="lead">Sekolah sepak bola untuk anak-anak — latihan terstruktur, pelatih bersertifikat, dan pengembangan karakter.</p>
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-2">Daftar Sekarang</a>
            <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary btn-lg">Pelajari Lebih Lanjut</a>
            <div class="mt-4">
                <small class="text-muted"><i class="bi bi-geo-alt"></i> Lokasi: Jakarta • Jadwal latihan: Sabtu & Minggu</small>
            </div>
        </div>
        <div class="col-md-6 text-center">
            <img src="{{ asset('images/landing-soccer.png') }}" alt="Bina Bola" class="img-fluid" style="max-height:320px;">
        </div>
    </div>

    <hr class="my-5">

    <div class="row text-center">
        <div class="col-md-4">
            <i class="bi bi-people-fill fs-1 text-primary"></i>
            <h5 class="mt-2">Pelatih Bersertifikat</h5>
            <p class="text-muted small">Pelatih berpengalaman untuk pengembangan skill dan karakter.</p>
        </div>
        <div class="col-md-4">
            <i class="bi bi-trophy-fill fs-1 text-success"></i>
            <h5 class="mt-2">Program Kompetitif</h5>
            <p class="text-muted small">Program berjenjang dan kompetisi internal untuk motivasi.</p>
        </div>
        <div class="col-md-4">
            <i class="bi bi-shield-lock-fill fs-1 text-warning"></i>
            <h5 class="mt-2">Keamanan & Kesehatan</h5>
            <p class="text-muted small">Protokol kesehatan dan asuransi untuk tiap peserta.</p>
        </div>
    </div>

    <div class="row my-5">
        <div class="col-md-8">
            <h3>Kenapa memilih Bina Bola?</h3>
            <p>Pelatihan terstruktur, program pengembangan keterampilan teknis, serta fokus kepada disiplin dan kerjasama tim. Cocok untuk anak usia dini hingga remaja.</p>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Kontak & Pendaftaran</h5>
                    <p class="small mb-1"><strong>Telp:</strong> 0812-XXXX-XXXX</p>
                    <p class="small mb-1"><strong>Email:</strong> info@binabola.id</p>
                    <a href="{{ route('register') }}" class="btn btn-primary w-100">Daftar Sekarang</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
