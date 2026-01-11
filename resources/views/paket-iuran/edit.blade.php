@extends('layouts.app')

@section('title', 'Edit Paket Iuran')

@section('page-title', 'Edit Paket Iuran')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-pencil"></i> Form Edit Paket Iuran</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('paket-iuran.update', $paket->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="nama_paket" class="form-label">Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" name="nama_paket" id="nama_paket" class="form-control @error('nama_paket') is-invalid @enderror" value="{{ old('nama_paket', $paket->nama_paket) }}" required>
                        @error('nama_paket')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="kelompok_umur" class="form-label">Kelompok Umur <span class="text-danger">*</span></label>
                        <select name="kelompok_umur" id="kelompok_umur" class="form-select @error('kelompok_umur') is-invalid @enderror" required>
                            <option value="">-- Pilih Kelompok Umur --</option>
                            <option value="U-7" {{ old('kelompok_umur', $paket->kelompok_umur) === 'U-7' ? 'selected' : '' }}>U-7 (Usia 3-7 Tahun)</option>
                            <option value="U-12" {{ old('kelompok_umur', $paket->kelompok_umur) === 'U-12' ? 'selected' : '' }}>U-12 (Usia 8-12 Tahun)</option>
                        </select>
                        @error('kelompok_umur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="nominal" class="form-label">Nominal <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="nominal" id="nominal" class="form-control @error('nominal') is-invalid @enderror" value="{{ old('nominal', $paket->nominal) }}" required min="0" step="1000">
                            </div>
                            @error('nominal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="durasi_bulan" class="form-label">Durasi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="durasi_bulan" id="durasi_bulan" class="form-control @error('durasi_bulan') is-invalid @enderror" value="{{ old('durasi_bulan', $paket->durasi_bulan) }}" required min="1" max="12">
                                <span class="input-group-text">Bulan</span>
                            </div>
                            @error('durasi_bulan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan', $paket->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="aktif" id="aktif" class="form-check-input" {{ old('aktif', $paket->aktif) ? 'checked' : '' }}>
                            <label class="form-check-label" for="aktif">
                                <strong>Status Aktif</strong>
                                <br><small class="text-muted">Paket aktif akan muncul di form pembayaran</small>
                            </label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('paket-iuran.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Update Paket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
