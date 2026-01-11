@extends('layouts.app')

@section('title', 'Absensi Siswa')

@section('page-title', 'Absensi Siswa')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Input Absensi Harian</h5>
                <div>
                    <a href="{{ route('absensi.history') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-clock-history"></i> Riwayat
                    </a>
                    <a href="{{ route('absensi.statistik') }}" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-bar-chart"></i> Statistik
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div id="successAlert" class="alert alert-success d-none"></div>
                <div id="holidayWarning" class="alert alert-warning d-none">
                    <i class="bi bi-exclamation-triangle"></i> <strong>Perhatian!</strong> 
                    <span id="holidayMessage"></span>
                </div>
                
                <form id="absensiForm">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" value="{{ $today }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sesi Latihan</label>
                            <select class="form-select" name="sesi" required>
                                <option value="Pagi">Pagi</option>
                                <option value="Sore">Sore</option>
                                <option value="Full Day">Full Day</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" class="btn btn-sm btn-outline-secondary me-2" onclick="tandaiSemua('Hadir')">
                                <i class="bi bi-check-all"></i> Semua Hadir
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning" onclick="tandaiSemua('Alpa')">
                                <i class="bi bi-x-circle"></i> Semua Alpa
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="30%">Nama Siswa</th>
                                    <th width="15%">Umur</th>
                                    <th width="20%">Posisi</th>
                                    <th width="30%">Status Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswas as $siswa)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($siswa->foto)
                                                <img src="{{ asset($siswa->foto) }}" alt="Foto" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                                    <i class="bi bi-person text-white" style="font-size: 14px;"></i>
                                                </div>
                                            @endif
                                            <strong>{{ $siswa->nama }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $siswa->umur }} tahun</td>
                                    <td><small>{{ $siswa->minat_posisi_string }}</small></td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <input type="radio" class="btn-check" name="absensi[{{ $siswa->id }}]" value="Hadir" id="hadir_{{ $siswa->id }}" {{ (isset($absensiHariIni[$siswa->id]) && $absensiHariIni[$siswa->id] == 'Hadir') || !isset($absensiHariIni[$siswa->id]) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-success" for="hadir_{{ $siswa->id }}">
                                                <i class="bi bi-check-circle"></i> Hadir
                                            </label>

                                            <input type="radio" class="btn-check" name="absensi[{{ $siswa->id }}]" value="Izin" id="izin_{{ $siswa->id }}" {{ ($absensiHariIni[$siswa->id] ?? '') == 'Izin' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary" for="izin_{{ $siswa->id }}">
                                                <i class="bi bi-info-circle"></i> Izin
                                            </label>

                                            <input type="radio" class="btn-check" name="absensi[{{ $siswa->id }}]" value="Sakit" id="sakit_{{ $siswa->id }}" {{ ($absensiHariIni[$siswa->id] ?? '') == 'Sakit' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-warning" for="sakit_{{ $siswa->id }}">
                                                <i class="bi bi-hospital"></i> Sakit
                                            </label>

                                            <input type="radio" class="btn-check" name="absensi[{{ $siswa->id }}]" value="Alpa" id="alpa_{{ $siswa->id }}" {{ ($absensiHariIni[$siswa->id] ?? '') == 'Alpa' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger" for="alpa_{{ $siswa->id }}">
                                                <i class="bi bi-x-circle"></i> Alpa
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <p class="text-muted">Tidak ada siswa aktif</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($siswas->count() > 0)
                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary" id="btnSimpan">
                            <i class="bi bi-save"></i> Simpan Absensi
                        </button>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Data hari libur dari server
const hariLibur = {!! json_encode($hariLibur) !!};

// Check hari libur saat tanggal diubah
document.querySelector('input[name="tanggal"]').addEventListener('change', function(e) {
    const selectedDate = e.target.value;
    const warning = document.getElementById('holidayWarning');
    const message = document.getElementById('holidayMessage');
    const btnSimpan = document.getElementById('btnSimpan');
    
    if (hariLibur[selectedDate]) {
        warning.classList.remove('d-none');
        message.textContent = 'Tanggal yang dipilih adalah hari libur: ' + hariLibur[selectedDate];
        btnSimpan.disabled = true;
        btnSimpan.innerHTML = '<i class="bi bi-lock"></i> Tidak Dapat Input (Hari Libur)';
    } else {
        warning.classList.add('d-none');
        btnSimpan.disabled = false;
        btnSimpan.innerHTML = '<i class="bi bi-save"></i> Simpan Absensi';
    }
});

// Set semua status
function tandaiSemua(status) {
    document.querySelectorAll(`input[type="radio"][value="${status}"]`).forEach(radio => {
        radio.checked = true;
    });
}

// Submit form with AJAX
document.getElementById('absensiForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Check if all students have status
    const totalSiswa = {{ $siswas->count() }};
    const checkedRadios = document.querySelectorAll('input[type="radio"]:checked').length;
    
    if (checkedRadios < totalSiswa) {
        alert('Mohon isi status kehadiran untuk semua siswa!');
        return;
    }
    
    const btnSimpan = document.getElementById('btnSimpan');
    btnSimpan.disabled = true;
    btnSimpan.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
    
    const formData = new FormData(this);
    
    fetch('{{ route("absensi.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            return response.text().then(text => {
                throw new Error('Server mengembalikan HTML, bukan JSON.');
            });
        }
    })
    .then(data => {
        if (data.success) {
            const alert = document.getElementById('successAlert');
            alert.textContent = data.message;
            alert.classList.remove('d-none');
            setTimeout(() => alert.classList.add('d-none'), 3000);
        } else {
            alert('Error: ' + (data.message || 'Gagal menyimpan'));
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan: ' + error.message);
    })
    .finally(() => {
        btnSimpan.disabled = false;
        btnSimpan.innerHTML = '<i class="bi bi-save"></i> Simpan Absensi';
    });
});
</script>
@endpush
