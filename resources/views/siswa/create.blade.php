@extends('layouts.app')

@section('title', 'Tambah Siswa - Sekolah Sepak Bola')

@section('page-title', 'Tambah Siswa Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-person-plus"></i> Form Tambah Siswa</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('siswa.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Nama -->
                    <div class="mb-3">
                        <label for="nama" class="form-label">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" 
                               name="nama" 
                               value="{{ old('nama') }}" 
                               placeholder="Masukkan nama lengkap siswa"
                               required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Foto -->
                    <div class="mb-3">
                        <label for="foto" class="form-label">
                            Foto Siswa
                        </label>
                        <input type="file" 
                               class="form-control @error('foto') is-invalid @enderror" 
                               id="foto" 
                               name="foto" 
                               accept="image/*">
                        <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB</small>
                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_lahir" class="form-label">
                            Tanggal Lahir <span class="text-danger">*</span>
                        </label>
                        <input type="date" 
                               class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                               id="tanggal_lahir" 
                               name="tanggal_lahir" 
                               value="{{ old('tanggal_lahir') }}" 
                               required>
                        @error('tanggal_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Minat Posisi <span class="text-danger">*</span>
                        </label>
                        <small class="text-muted d-block mb-2">Pilih satu atau lebih posisi yang diminati</small>
                        
                        @php
                            $posisiList = [
                                'Kiper',
                                'Bek Kiri',
                                'Bek Tengah',
                                'Bek Kanan',
                                'Gelandang Bertahan',
                                'Gelandang Tengah',
                                'Gelandang Serang',
                                'Sayap Kiri',
                                'Sayap Kanan',
                                'Striker'
                            ];
                        @endphp
                        
                        <div class="border rounded p-3 @error('minat_posisi') border-danger @enderror">
                            @foreach($posisiList as $posisi)
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="minat_posisi[]" 
                                       value="{{ $posisi }}" 
                                       id="posisi_{{ $loop->index }}"
                                       {{ in_array($posisi, old('minat_posisi', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="posisi_{{ $loop->index }}">
                                    {{ $posisi }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        
                        @error('minat_posisi')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Telepon -->
                    <div class="mb-3">
                        <label for="telepon" class="form-label">
                            Nomor Telepon/WhatsApp
                        </label>
                        <input type="text" 
                               class="form-control @error('telepon') is-invalid @enderror" 
                               id="telepon" 
                               name="telepon" 
                               value="{{ old('telepon') }}" 
                               placeholder="Contoh: 081234567890">
                        @error('telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Email
                        </label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="Contoh: siswa@email.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="mb-3">
                        <label for="alamat" class="form-label">
                            Alamat Lengkap
                        </label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                  id="alamat" 
                                  name="alamat" 
                                  rows="3" 
                                  placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tinggi & Berat Badan -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tinggi_badan" class="form-label">
                                Tinggi Badan (cm)
                            </label>
                            <input type="number" 
                                   class="form-control @error('tinggi_badan') is-invalid @enderror" 
                                   id="tinggi_badan" 
                                   name="tinggi_badan" 
                                   value="{{ old('tinggi_badan') }}" 
                                   placeholder="Contoh: 170"
                                   min="50"
                                   max="250">
                            @error('tinggi_badan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="berat_badan" class="form-label">
                                Berat Badan (kg)
                            </label>
                            <input type="number" 
                                   class="form-control @error('berat_badan') is-invalid @enderror" 
                                   id="berat_badan" 
                                   name="berat_badan" 
                                   value="{{ old('berat_badan') }}" 
                                   placeholder="Contoh: 65"
                                   min="10"
                                   max="200">
                            @error('berat_badan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status" 
                                required>
                            <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Non-Aktif" {{ old('status') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('siswa.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-label {
        font-weight: 500;
    }
    .card {
        margin-top: 20px;
    }
</style>
@endpush
