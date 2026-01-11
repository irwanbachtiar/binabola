@extends('layouts.mobile')

@section('title', 'Input Evaluasi')
@section('page-title', 'Input Evaluasi Siswa')

@section('content')
<!-- Siswa Info Card -->
<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            @if($siswa->foto)
                <img src="{{ asset($siswa->foto) }}" alt="{{ $siswa->nama }}">
            @else
                <div class="placeholder-img">
                    <i class="bi bi-person fs-3 text-white"></i>
                </div>
            @endif
            <div class="siswa-info">
                <h6>{{ $siswa->nama }}</h6>
                <small><i class="bi bi-calendar"></i> {{ $siswa->umur }} tahun</small><br>
                <small><i class="bi bi-trophy"></i> {{ $siswa->minat_posisi_string }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Minggu Info -->
<div class="alert alert-info">
    <i class="bi bi-calendar3"></i> <strong>Minggu ke-{{ $currentWeek }}</strong>, Tahun {{ $currentYear }}
</div>

<!-- Form Evaluasi -->
<form action="{{ route('evaluasi.store') }}" method="POST" id="evaluasiForm">
    @csrf
    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
    <input type="hidden" name="minggu" value="{{ $currentWeek }}">
    <input type="hidden" name="tahun" value="{{ $currentYear }}">

    @foreach($kategoris as $kategori)
    <div class="card">
        <div class="card-body">
            <label class="form-label">
                <strong>{{ $kategori->nama }}</strong>
                @if($kategori->deskripsi)
                    <br><small class="text-muted">{{ $kategori->deskripsi }}</small>
                @endif
            </label>
            
            <input 
                type="range" 
                class="form-range range-slider" 
                name="nilai[{{ $kategori->id }}]" 
                id="range{{ $kategori->id }}"
                min="0" 
                max="100" 
                value="{{ $existingEvaluasi[$kategori->id] ?? 50 }}"
                step="5"
                oninput="updateValue({{ $kategori->id }})"
            >
            
            <div class="text-center">
                <span class="range-value" id="value{{ $kategori->id }}">
                    {{ $existingEvaluasi[$kategori->id] ?? 50 }}
                </span>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Catatan -->
    <div class="card">
        <div class="card-body">
            <label class="form-label"><strong>Catatan (Opsional)</strong></label>
            <textarea 
                name="catatan" 
                class="form-control" 
                rows="4" 
                placeholder="Tulis catatan perkembangan siswa..."
            ></textarea>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="d-grid gap-2 mb-3">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-check-circle"></i> Simpan Evaluasi
        </button>
        <a href="{{ route('evaluasi.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</form>
@endsection

@push('scripts')
<script>
    function updateValue(id) {
        const range = document.getElementById('range' + id);
        const value = document.getElementById('value' + id);
        value.textContent = range.value;
        
        // Update color based on value
        if (range.value < 40) {
            value.style.background = '#dc3545'; // Red
        } else if (range.value < 70) {
            value.style.background = '#ffc107'; // Yellow
        } else {
            value.style.background = '#28a745'; // Green
        }
    }
    
    // Initialize all values
    @foreach($kategoris as $kategori)
        updateValue({{ $kategori->id }});
    @endforeach
    
    // Form submission with loading
    document.getElementById('evaluasiForm').addEventListener('submit', function() {
        document.querySelector('.loading').style.display = 'block';
    });
</script>
@endpush
