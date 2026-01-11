@extends('layouts.app')

@section('title', 'Tambah Kategori Penilaian')

@section('page-title', 'Tambah Kategori Penilaian')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Form Tambah Kategori</h5>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('kategori-penilaian.store') }}" method="POST" id="formKategori">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">
                            Tipe Kategori <span class="text-danger">*</span>
                        </label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipe" id="tipeParent" value="parent" 
                                       {{ old('tipe', 'parent') == 'parent' ? 'checked' : '' }}>
                                <label class="form-check-label" for="tipeParent">
                                    <i class="bi bi-folder"></i> Parent (Kategori Utama)
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipe" id="tipeSub" value="sub"
                                       {{ old('tipe') == 'sub' ? 'checked' : '' }}>
                                <label class="form-check-label" for="tipeSub">
                                    <i class="bi bi-arrow-return-right"></i> Sub (Kategori Anak)
                                </label>
                            </div>
                        </div>
                        <small class="text-muted">Parent = kategori utama (tidak dinilai), Sub = kategori yang dinilai</small>
                    </div>

                    <div class="mb-3" id="parentSelectWrapper" style="display: none;">
                        <label for="parent_id" class="form-label">
                            Pilih Parent <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('parent_id') is-invalid @enderror" 
                                id="parent_id" 
                                name="parent_id">
                            <option value="">-- Pilih Parent --</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->nama }} ({{ $parent->kelompok_umur }})
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kategori sub harus memiliki parent</small>
                    </div>

                    <div class="mb-3">
                        <label for="kelompok_umur" class="form-label">
                            Kelompok Umur <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('kelompok_umur') is-invalid @enderror" 
                                id="kelompok_umur" 
                                name="kelompok_umur"
                                required>
                            <option value="">-- Pilih Kelompok Umur --</option>
                            <option value="U-7" {{ old('kelompok_umur') == 'U-7' ? 'selected' : '' }}>U-7</option>
                            <option value="U-12" {{ old('kelompok_umur') == 'U-12' ? 'selected' : '' }}>U-12</option>
                        </select>
                        @error('kelompok_umur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nama" class="form-label">
                            Nama Kategori <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" 
                               name="nama" 
                               value="{{ old('nama') }}" 
                               placeholder="Contoh: Teknik, Passing, Dribbling"
                               required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" 
                                  name="deskripsi" 
                                  rows="3" 
                                  placeholder="Deskripsi singkat tentang kategori ini">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="urutan" class="form-label">
                            Urutan Tampil <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               class="form-control @error('urutan') is-invalid @enderror" 
                               id="urutan" 
                               name="urutan" 
                               value="{{ old('urutan', 1) }}" 
                               min="1"
                               required>
                        <small class="text-muted">Urutan kemunculan di form & laporan</small>
                        @error('urutan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="aktif" 
                                   name="aktif" 
                                   {{ old('aktif', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="aktif">
                                Status Aktif
                            </label>
                        </div>
                        <small class="text-muted">Hanya kategori aktif yang muncul di form evaluasi</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="minggu_terakhir" 
                                   name="minggu_terakhir" 
                                   {{ old('minggu_terakhir') ? 'checked' : '' }}>
                            <label class="form-check-label" for="minggu_terakhir">
                                <i class="bi bi-calendar-check"></i> Kategori Minggu Terakhir
                            </label>
                        </div>
                        <small class="text-muted">Jika dicentang, kategori ini hanya muncul pada minggu terakhir bulan (hari Minggu terakhir)</small>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        <strong>Catatan:</strong> Kategori Parent berfungsi sebagai pengelompokan, sedangkan kategori Sub yang akan dinilai.
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('kategori-penilaian.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipeParent = document.getElementById('tipeParent');
    const tipeSub = document.getElementById('tipeSub');
    const parentSelectWrapper = document.getElementById('parentSelectWrapper');
    const parentSelect = document.getElementById('parent_id');
    
    function toggleParentSelect() {
        if (tipeSub.checked) {
            parentSelectWrapper.style.display = 'block';
            parentSelect.required = true;
        } else {
            parentSelectWrapper.style.display = 'none';
            parentSelect.required = false;
            parentSelect.value = '';
        }
    }
    
    tipeParent.addEventListener('change', toggleParentSelect);
    tipeSub.addEventListener('change', toggleParentSelect);
    
    // Initialize on page load
    toggleParentSelect();
    
    const form = document.getElementById('formKategori');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menyimpan...';
    });
    
    window.addEventListener('pageshow', function() {
        if (submitBtn.disabled) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-save"></i> Simpan Kategori';
        }
    });
});
</script>
@endpush
