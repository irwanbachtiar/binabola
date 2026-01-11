@extends('layouts.mobile')

@section('title', 'Pilih Siswa')
@section('page-title', 'Pilih Siswa untuk Evaluasi')

@section('content')
<!-- Search Box -->
<div class="card mb-3">
    <div class="card-body">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" id="searchInput" class="form-control" placeholder="Cari nama siswa...">
        </div>
    </div>
</div>

<!-- Siswa List -->
<div id="siswaList">
    @forelse($siswas as $siswa)
    <a href="{{ route('evaluasi.create', $siswa->id) }}" class="siswa-card">
        @if($siswa->foto)
            <img src="{{ asset($siswa->foto) }}" alt="{{ $siswa->nama }}">
        @else
            <div class="placeholder-img">
                <i class="bi bi-person fs-3 text-white"></i>
            </div>
        @endif
        <div class="siswa-info flex-grow-1">
            <h6>{{ $siswa->nama }}</h6>
            <small><i class="bi bi-calendar"></i> {{ $siswa->umur }} tahun</small><br>
            <small><i class="bi bi-trophy"></i> {{ $siswa->minat_posisi_string }}</small>
        </div>
        <i class="bi bi-chevron-right text-muted"></i>
    </a>
    @empty
    <div class="text-center py-5">
        <i class="bi bi-people fs-1 text-muted"></i>
        <p class="text-muted mt-3">Belum ada siswa aktif</p>
    </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const siswaCards = document.querySelectorAll('.siswa-card');
        
        siswaCards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(searchTerm) ? 'flex' : 'none';
        });
    });
</script>
@endpush
