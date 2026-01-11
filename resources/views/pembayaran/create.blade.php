@extends('layouts.app')

@section('title', 'Input Pembayaran')

@section('page-title', 'Input Pembayaran Iuran')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-cash-coin"></i> Form Pembayaran Iuran</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pembayaran.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="siswa_id" class="form-label">Pilih Siswa <span class="text-danger">*</span></label>
                        <select name="siswa_id" id="siswa_id" class="form-select @error('siswa_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswas as $s)
                                <option value="{{ $s->id }}" {{ old('siswa_id', $siswa?->id) == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama }} - {{ $s->kelompok_umur }}
                                </option>
                            @endforeach
                        </select>
                        @error('siswa_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    @if($siswa && $tagihanBelumBayar && $tagihanBelumBayar->count() > 0)
                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle"></i> Tagihan Belum Dibayar:</h6>
                        <ul class="mb-0">
                            @foreach($tagihanBelumBayar as $tagihan)
                                <li>{{ $tagihan->periode }} - {{ $tagihan->nominal_format }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="paket_iuran_id" class="form-label">Paket Iuran</label>
                        <select name="paket_iuran_id" id="paket_iuran_id" class="form-select @error('paket_iuran_id') is-invalid @enderror">
                            <option value="">-- Tanpa Paket --</option>
                            @foreach($pakets as $paket)
                                <option value="{{ $paket->id }}" 
                                        data-nominal="{{ $paket->nominal }}" 
                                        {{ old('paket_iuran_id', $siswa?->paket_iuran_id) == $paket->id ? 'selected' : '' }}>
                                    {{ $paket->nama_paket }} ({{ $paket->kelompok_umur }}) - {{ $paket->nominal_format }}
                                </option>
                            @endforeach
                        </select>
                        @error('paket_iuran_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="periode_bulan" class="form-label">Bulan <span class="text-danger">*</span></label>
                            <select name="periode_bulan" id="periode_bulan" class="form-select @error('periode_bulan') is-invalid @enderror" required>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('periode_bulan', date('n')) == $i ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                            @error('periode_bulan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="periode_tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                            <select name="periode_tahun" id="periode_tahun" class="form-select @error('periode_tahun') is-invalid @enderror" required>
                                @for($y = date('Y'); $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ old('periode_tahun', date('Y')) == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                            @error('periode_tahun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_bayar" class="form-label">Tanggal Bayar <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="form-control @error('tanggal_bayar') is-invalid @enderror" value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required>
                            @error('tanggal_bayar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nominal" class="form-label">Nominal <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="nominal" id="nominal" class="form-control @error('nominal') is-invalid @enderror" value="{{ old('nominal', $siswa?->paketIuran?->nominal) }}" required min="0" step="1000">
                            </div>
                            @error('nominal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="metode_pembayaran" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                        <select name="metode_pembayaran" id="metode_pembayaran" class="form-select @error('metode_pembayaran') is-invalid @enderror" required>
                            <option value="cash" {{ old('metode_pembayaran') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="qris" {{ old('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="lainnya" {{ old('metode_pembayaran') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('metode_pembayaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran (Upload Foto/Scan)</label>
                        <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control @error('bukti_pembayaran') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                        @error('bukti_pembayaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea name="catatan" id="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Pembayaran
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
    // Auto-fill paket dan nominal ketika siswa dipilih (redirect)
    document.getElementById('siswa_id').addEventListener('change', function() {
        if (this.value) {
            window.location.href = '{{ route("pembayaran.create") }}?siswa_id=' + this.value;
        }
    });
    
    // Auto-fill nominal ketika paket dipilih secara manual
    document.getElementById('paket_iuran_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const nominal = selectedOption.getAttribute('data-nominal');
        if (nominal) {
            document.getElementById('nominal').value = nominal;
        } else {
            document.getElementById('nominal').value = '';
        }
    });
    
    // Auto-fill nominal saat page load jika paket sudah terpilih
    window.addEventListener('DOMContentLoaded', function() {
        const paketSelect = document.getElementById('paket_iuran_id');
        if (paketSelect.value) {
            const selectedOption = paketSelect.options[paketSelect.selectedIndex];
            const nominal = selectedOption.getAttribute('data-nominal');
            if (nominal && !document.getElementById('nominal').value) {
                document.getElementById('nominal').value = nominal;
            }
        }
    });
</script>
@endpush
