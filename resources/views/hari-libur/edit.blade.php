@extends('layouts.app')

@section('title', 'Edit Hari Libur')

@section('page-title', 'Edit Hari Libur')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Form Edit Hari Libur</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('hari-libur.update', $hariLibur->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" 
                               class="form-control @error('tanggal') is-invalid @enderror" 
                               id="tanggal" 
                               name="tanggal" 
                               value="{{ old('tanggal', $hariLibur->tanggal->format('Y-m-d')) }}" 
                               required>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('keterangan') is-invalid @enderror" 
                               id="keterangan" 
                               name="keterangan" 
                               value="{{ old('keterangan', $hariLibur->keterangan) }}" 
                               placeholder="Contoh: Hari Raya Idul Fitri" 
                               required>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jenis" class="form-label">Jenis <span class="text-danger">*</span></label>
                        <select class="form-select @error('jenis') is-invalid @enderror" 
                                id="jenis" 
                                name="jenis" 
                                required>
                            <option value="">Pilih Jenis</option>
                            <option value="Nasional" {{ old('jenis', $hariLibur->jenis) === 'Nasional' ? 'selected' : '' }}>Nasional</option>
                            <option value="Keagamaan" {{ old('jenis', $hariLibur->jenis) === 'Keagamaan' ? 'selected' : '' }}>Keagamaan</option>
                            <option value="Sekolah" {{ old('jenis', $hariLibur->jenis) === 'Sekolah' ? 'selected' : '' }}>Sekolah</option>
                            <option value="Lainnya" {{ old('jenis', $hariLibur->jenis) === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('jenis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="aktif" 
                                   name="aktif" 
                                   value="1" 
                                   {{ old('aktif', $hariLibur->aktif) ? 'checked' : '' }}>
                            <label class="form-check-label" for="aktif">
                                Aktif
                            </label>
                        </div>
                        <small class="text-muted">Centang jika hari libur ini aktif/berlaku</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('hari-libur.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
