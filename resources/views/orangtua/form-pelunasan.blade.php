@extends('layouts.app')

@section('title', 'Form Pelunasan - ' . $siswa->nama)

@section('page-title')
    <div class="d-flex align-items-center">
        <a href="{{ route('orangtua.siswa.pembayaran', $siswa->id) }}" class="btn btn-outline-secondary btn-sm me-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <span>Form Pelunasan Iuran</span>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Student Info -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            @if($siswa->foto && file_exists(public_path($siswa->foto)))
                                <img src="{{ asset($siswa->foto) }}" alt="{{ $siswa->nama }}" 
                                     class="rounded-circle" width="60" height="60" 
                                     style="object-fit: cover; border: 3px solid #667eea;">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <span class="text-white" style="font-size: 24px; font-weight: bold;">
                                        {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="col">
                            <h5 class="mb-0">{{ $siswa->nama }}</h5>
                            <p class="text-muted mb-0 small">
                                <span class="badge bg-{{ $siswa->kelompok_umur == 'U-7' ? 'info' : 'primary' }}">
                                    {{ $siswa->kelompok_umur }}
                                </span>
                                @if($siswa->paketIuran)
                                    <span class="badge bg-success ms-1">{{ $siswa->paketIuran->nama_paket }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Pelunasan -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-credit-card"></i> Form Pelunasan Iuran</h5>
                </div>
                <div class="card-body">
                    @if($tagihanBelumBayar->count() > 0)
                    <form action="{{ route('orangtua.siswa.pelunasan.submit', $siswa->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Pilih Tagihan -->
                        <div class="mb-3">
                            <label for="tagihan_id" class="form-label">Pilih Tagihan yang Akan Dibayar <span class="text-danger">*</span></label>
                            <select name="tagihan_id" id="tagihan_id" class="form-select @error('tagihan_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Tagihan --</option>
                                @foreach($tagihanBelumBayar as $tagihan)
                                <option value="{{ $tagihan->id }}" 
                                        data-nominal="{{ $tagihan->nominal }}"
                                        {{ old('tagihan_id') == $tagihan->id || request('tagihan_id') == $tagihan->id ? 'selected' : '' }}>
                                    {{ $tagihan->periode }} - {{ $tagihan->nominal_format }}
                                    @if($tagihan->terlambat) (Terlambat) @endif
                                </option>
                                @endforeach
                            </select>
                            @error('tagihan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Pilih periode tagihan yang ingin Anda bayar</small>
                        </div>

                        <!-- Tanggal Bayar -->
                        <div class="mb-3">
                            <label for="tanggal_bayar" class="form-label">Tanggal Pembayaran <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('tanggal_bayar') is-invalid @enderror" 
                                   id="tanggal_bayar" 
                                   name="tanggal_bayar" 
                                   value="{{ old('tanggal_bayar', date('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}"
                                   required>
                            @error('tanggal_bayar')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nominal -->
                        <div class="mb-3">
                            <label for="nominal" class="form-label">Nominal Pembayaran <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" 
                                       class="form-control @error('nominal') is-invalid @enderror" 
                                       id="nominal" 
                                       name="nominal" 
                                       value="{{ old('nominal') }}"
                                       placeholder="0"
                                       min="0"
                                       step="1000"
                                       required>
                            </div>
                            @error('nominal')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Nominal akan otomatis terisi sesuai tagihan yang dipilih</small>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="mb-3">
                            <label for="metode_pembayaran" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select name="metode_pembayaran" id="metode_pembayaran" class="form-select @error('metode_pembayaran') is-invalid @enderror" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="cash" {{ old('metode_pembayaran') == 'cash' ? 'selected' : '' }}>Tunai</option>
                                <option value="qris" {{ old('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS</option>
                                <option value="lainnya" {{ old('metode_pembayaran') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('metode_pembayaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload Bukti Pembayaran -->
                        <div class="mb-3">
                            <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran (Opsional)</label>
                            <input type="file" 
                                   class="form-control @error('bukti_pembayaran') is-invalid @enderror" 
                                   id="bukti_pembayaran" 
                                   name="bukti_pembayaran"
                                   accept="image/*">
                            @error('bukti_pembayaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Upload foto/screenshot bukti pembayaran (JPG, PNG max 2MB)</small>
                            
                            <!-- Preview -->
                            <div id="preview-container" class="mt-2" style="display: none;">
                                <img id="preview-image" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="mb-4">
                            <label for="catatan" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                      id="catatan" 
                                      name="catatan" 
                                      rows="3" 
                                      placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                            @error('catatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alert Info -->
                        <div class="alert alert-info border-0">
                            <i class="bi bi-info-circle"></i>
                            <strong>Perhatian:</strong> Pembayaran yang Anda ajukan akan menunggu verifikasi dari admin. 
                            Status pembayaran akan diperbarui setelah admin memverifikasi.
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-send"></i> Ajukan Pembayaran
                            </button>
                            <a href="{{ route('orangtua.siswa.pembayaran', $siswa->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        </div>
                    </form>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                        <h5 class="mt-3">Tidak Ada Tagihan</h5>
                        <p class="text-muted">Semua tagihan telah dibayarkan</p>
                        <a href="{{ route('orangtua.siswa.pembayaran', $siswa->id) }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left"></i> Kembali ke Monitoring
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tagihanSelect = document.getElementById('tagihan_id');
    const nominalInput = document.getElementById('nominal');
    const buktiInput = document.getElementById('bukti_pembayaran');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');
    
    // Auto-fill nominal saat tagihan dipilih
    tagihanSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const nominal = selectedOption.getAttribute('data-nominal');
        
        if (nominal) {
            nominalInput.value = nominal;
        } else {
            nominalInput.value = '';
        }
    });
    
    // Trigger change event jika sudah ada nilai default
    if (tagihanSelect.value) {
        tagihanSelect.dispatchEvent(new Event('change'));
    }
    
    // Preview bukti pembayaran
    buktiInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = 'block';
            };
            
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
        }
    });
});
</script>
@endpush
