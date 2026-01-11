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
                    
                    <!-- Nama Siswa -->
                    <div class="mb-3">
                        <label for="nama" class="form-label">
                            Nama Siswa <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" 
                               name="nama" 
                               value="{{ old('nama') }}" 
                               required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email (hidden) -->
                    <input type="hidden" name="email" value="{{ old('email') }}">

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
                        <label for="tanggal_masuk" class="form-label">
                            Tanggal Masuk / Daftar <span class="text-danger">*</span>
                        </label>
                        <input type="date" 
                               class="form-control @error('tanggal_masuk') is-invalid @enderror" 
                               id="tanggal_masuk" 
                               name="tanggal_masuk" 
                               value="{{ old('tanggal_masuk', date('Y-m-d')) }}" 
                               required>
                        <small class="text-muted">Tanggal siswa mulai bergabung di sekolah sepak bola</small>
                        @error('tanggal_masuk')
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
                                'Belakang',
                                'Tengah',
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
                            Nomor Telepon Orang tua / Wali
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

                    <!-- Paket Iuran -->
                    <div class="mb-3">
                        <label for="paket_iuran_id" class="form-label">
                            <i class="bi bi-cash-coin"></i> Paket Iuran Bulanan
                        </label>
                        <select class="form-select @error('paket_iuran_id') is-invalid @enderror" 
                                id="paket_iuran_id" 
                                name="paket_iuran_id">
                            <option value="">-- Pilih Paket Iuran (Opsional) --</option>
                            @foreach($pakets as $paket)
                                <option value="{{ $paket->id }}" 
                                        data-nominal="{{ $paket->nominal }}"
                                        {{ old('paket_iuran_id') == $paket->id ? 'selected' : '' }}>
                                    {{ $paket->nama_paket }} ({{ $paket->kelompok_umur }}) - {{ $paket->nominal_format }}/{{ $paket->durasi_bulan }} bulan
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Paket iuran bulanan yang akan dibayarkan oleh siswa</small>
                        @error('paket_iuran_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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

                    <!-- Akun Orangtua -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-person-badge"></i> Hubungkan dengan Akun Orangtua
                        </label>
                        <small class="text-muted d-block mb-2">Cari dan pilih akun orangtua yang akan terhubung dengan siswa ini</small>
                        
                        @if($orangtuaUsers->isEmpty())
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i> Belum ada akun orangtua. 
                                <a href="{{ route('admin.users.create') }}" target="_blank">Buat akun orangtua</a> terlebih dahulu.
                            </div>
                        @else
                            <!-- Search Input -->
                            <div class="mb-2">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="searchOrangtua" 
                                           placeholder="Cari nama atau email orangtua...">
                                </div>
                            </div>

                            <!-- Selected Items -->
                            <div id="selectedOrangtua" class="mb-2" style="min-height: 40px;">
                                <!-- Selected items will appear here -->
                            </div>

                            <!-- Dropdown List -->
                            <div class="border rounded" style="max-height: 300px; overflow-y: auto;">
                                <div id="orangtuaList">
                                    @foreach($orangtuaUsers as $orangtua)
                                    <div class="orangtua-item p-2 border-bottom" 
                                         data-id="{{ $orangtua->id }}" 
                                         data-name="{{ $orangtua->name }}" 
                                         data-email="{{ $orangtua->email }}"
                                         style="cursor: pointer;">
                                        <div class="form-check">
                                            <input class="form-check-input orangtua-checkbox" 
                                                   type="checkbox" 
                                                   name="orangtua_ids[]" 
                                                   value="{{ $orangtua->id }}" 
                                                   id="orangtua_{{ $orangtua->id }}"
                                                   {{ in_array($orangtua->id, old('orangtua_ids', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="orangtua_{{ $orangtua->id }}" style="cursor: pointer;">
                                                <strong>{{ $orangtua->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $orangtua->email }}</small>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div id="noResults" class="text-center text-muted p-3" style="display: none;">
                                    <i class="bi bi-search"></i> Tidak ada hasil ditemukan
                                </div>
                            </div>
                        @endif
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
    .orangtua-item:hover {
        background-color: #f8f9fa;
    }
    .orangtua-item.selected {
        background-color: #e7f3ff;
    }
    .selected-badge {
        display: inline-flex;
        align-items: center;
        background-color: #0d6efd;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        margin: 3px;
        font-size: 14px;
    }
    .selected-badge .remove-btn {
        margin-left: 8px;
        cursor: pointer;
        font-weight: bold;
        opacity: 0.8;
    }
    .selected-badge .remove-btn:hover {
        opacity: 1;
    }
    #selectedOrangtua:empty::before {
        content: 'Belum ada orangtua yang dipilih';
        color: #6c757d;
        font-style: italic;
        font-size: 14px;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchOrangtua');
    const orangtuaList = document.getElementById('orangtuaList');
    const selectedContainer = document.getElementById('selectedOrangtua');
    const noResults = document.getElementById('noResults');
    
    if (!searchInput || !orangtuaList) return;

    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const items = orangtuaList.querySelectorAll('.orangtua-item');
        let visibleCount = 0;

        items.forEach(item => {
            const name = item.dataset.name.toLowerCase();
            const email = item.dataset.email.toLowerCase();
            
            if (name.includes(searchTerm) || email.includes(searchTerm)) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        noResults.style.display = visibleCount === 0 ? 'block' : 'none';
    });

    // Handle checkbox changes
    const checkboxes = document.querySelectorAll('.orangtua-checkbox');
    
    checkboxes.forEach(checkbox => {
        // Initialize selected items from old input
        if (checkbox.checked) {
            updateSelectedDisplay();
        }

        checkbox.addEventListener('change', function() {
            const item = this.closest('.orangtua-item');
            
            if (this.checked) {
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
            
            updateSelectedDisplay();
        });
    });

    // Handle item click (toggle checkbox)
    const items = orangtuaList.querySelectorAll('.orangtua-item');
    items.forEach(item => {
        item.addEventListener('click', function(e) {
            // Don't toggle if clicking on checkbox or label directly
            if (e.target.type === 'checkbox' || e.target.tagName === 'LABEL') {
                return;
            }
            
            const checkbox = this.querySelector('.orangtua-checkbox');
            checkbox.checked = !checkbox.checked;
            checkbox.dispatchEvent(new Event('change'));
        });
    });

    function updateSelectedDisplay() {
        selectedContainer.innerHTML = '';
        
        const checkedBoxes = document.querySelectorAll('.orangtua-checkbox:checked');
        
        checkedBoxes.forEach(checkbox => {
            const item = checkbox.closest('.orangtua-item');
            const name = item.dataset.name;
            const email = item.dataset.email;
            
            const badge = document.createElement('span');
            badge.className = 'selected-badge';
            badge.innerHTML = `
                <span>${name}</span>
                <span class="remove-btn" data-id="${checkbox.value}">×</span>
            `;
            
            // Remove button click
            badge.querySelector('.remove-btn').addEventListener('click', function() {
                checkbox.checked = false;
                checkbox.dispatchEvent(new Event('change'));
            });
            
            selectedContainer.appendChild(badge);
        });
    }
});
</script>
@endpush
