@extends('layouts.app')

@section('title', 'Edit Siswa - Sekolah Sepak Bola')

@section('page-title', 'Edit Data Siswa')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Form Edit Siswa</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('siswa.update', $siswa->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Nama -->
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $siswa->nama) }}" required>
                                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Tanggal Lahir -->
                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir->format('Y-m-d')) }}" required>
                                @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Telepon -->
                            <div class="mb-3">
                                <label for="telepon" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control @error('telepon') is-invalid @enderror" id="telepon" name="telepon" value="{{ old('telepon', $siswa->telepon) }}">
                                @error('telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $siswa->email) }}">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Foto -->
                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto Siswa</label>
                                @if($siswa->foto)
                                    <div class="mb-2">
                                        <img src="{{ asset($siswa->foto) }}" alt="Foto" class="img-thumbnail" style="max-width: 150px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/*">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah</small>
                                @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Tinggi & Berat -->
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label for="tinggi_badan" class="form-label">Tinggi (cm)</label>
                                    <input type="number" class="form-control @error('tinggi_badan') is-invalid @enderror" id="tinggi_badan" name="tinggi_badan" value="{{ old('tinggi_badan', $siswa->tinggi_badan) }}">
                                    @error('tinggi_badan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="berat_badan" class="form-label">Berat (kg)</label>
                                    <input type="number" class="form-control @error('berat_badan') is-invalid @enderror" id="berat_badan" name="berat_badan" value="{{ old('berat_badan', $siswa->berat_badan) }}">
                                    @error('berat_badan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="Aktif" {{ old('status', $siswa->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Non-Aktif" {{ old('status', $siswa->status) == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="2">{{ old('alamat', $siswa->alamat) }}</textarea>
                        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Minat Posisi -->
                    <div class="mb-3">
                        <label class="form-label">Minat Posisi <span class="text-danger">*</span></label>
                        <small class="text-muted d-block mb-2">Pilih satu atau lebih posisi</small>
                        @php
                            $posisiList = ['Kiper', 'Bek Kiri', 'Bek Tengah', 'Bek Kanan', 'Gelandang Bertahan', 'Gelandang Tengah', 'Gelandang Serang', 'Sayap Kiri', 'Sayap Kanan', 'Striker'];
                            $selectedPosisi = old('minat_posisi', $siswa->minat_posisi ?? []);
                        @endphp
                        <div class="border rounded p-3 @error('minat_posisi') border-danger @enderror">
                            <div class="row">
                                @foreach($posisiList as $posisi)
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="minat_posisi[]" value="{{ $posisi }}" id="posisi_{{ $loop->index }}" {{ in_array($posisi, $selectedPosisi) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="posisi_{{ $loop->index }}">{{ $posisi }}</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @error('minat_posisi')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('siswa.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
