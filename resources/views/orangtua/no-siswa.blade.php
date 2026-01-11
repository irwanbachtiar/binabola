@extends('layouts.app')

@section('title', 'Dashboard Orangtua')

@section('page-title', 'Dashboard Orangtua')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-person-x" style="font-size: 72px; color: #ccc;"></i>
                    <h3 class="mt-4 mb-3">Belum Ada Siswa Terhubung</h3>
                    <p class="text-muted">
                        Akun Anda belum terhubung dengan data siswa. 
                        Silakan hubungi admin untuk menghubungkan akun Anda dengan data siswa.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                           class="btn btn-outline-secondary">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
