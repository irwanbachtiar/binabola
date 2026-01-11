@extends('layouts.app')

@section('title', 'Edit User Orangtua')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <h2 class="mb-4">Edit User Orangtua</h2>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" id="editUserForm">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="unit_kerja" class="form-label">Unit Kerja</label>
                            <input type="text" class="form-control @error('unit_kerja') is-invalid @enderror" 
                                   id="unit_kerja" name="unit_kerja" value="{{ old('unit_kerja', $user->unit_kerja) }}">
                            @error('unit_kerja')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation">
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Assign Siswa</h5>
                        <p class="text-muted">Pilih siswa yang terhubung dengan user ini:</p>

                        <div class="mb-3">
                            @foreach($allSiswas as $siswa)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="siswa_ids[]" value="{{ $siswa->id }}" 
                                           id="siswa_{{ $siswa->id }}"
                                           {{ in_array($siswa->id, $assignedSiswaIds) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="siswa_{{ $siswa->id }}">
                                        {{ $siswa->nama }} 
                                        <small class="text-muted">({{ $siswa->umur_detail }})</small>
                                    </label>
                                </div>
                            @endforeach

                            @if($allSiswas->isEmpty())
                                <div class="alert alert-warning">
                                    Belum ada data siswa. Silakan tambahkan siswa terlebih dahulu.
                                </div>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="bi bi-save"></i> Update
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('editUserForm').addEventListener('submit', function(e) {
    // Show loading on button
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Loading...';
});
</script>
@endpush

@endsection
