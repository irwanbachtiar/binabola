@extends('layouts.app')

@section('title', 'Laporan Summary Bulanan')

@section('page-title')
<span style="font-size: calc(1em - 2px);">Laporan Summary Bulanan</span>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('laporan.bulanan') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Pilih Bulan</label>
                        <input type="month" class="form-control" name="bulan" value="{{ $bulan }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Tampilkan
                        </button>
                    </div>
                    <div class="col-md-6 text-end">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Laporan U-7 -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-trophy"></i> Kelompok U-7 (Usia 3-7 Tahun)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center align-middle" rowspan="2" style="width: 50px;">Peringkat</th>
                                <th class="align-middle" rowspan="2">Nama Siswa</th>
                                
                                @php
                                    $regularParentsU7 = $parentKategorisU7->where('minggu_terakhir', 0);
                                    $miniGameParentsU7 = $parentKategorisU7->where('minggu_terakhir', 1);
                                @endphp
                                
                                @foreach($regularParentsU7 as $parent)
                                    @php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    @endphp
                                    <th colspan="{{ $children->count() }}" class="text-center 
                                        @if($parent->nama === 'Teknik') bg-primary
                                        @elseif($parent->nama === 'Etika') bg-success
                                        @else bg-info
                                        @endif text-white">
                                        {{ $parent->nama }}
                                    </th>
                                @endforeach
                                
                                @foreach($miniGameParentsU7 as $parent)
                                    @php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    @endphp
                                    <th colspan="{{ $children->count() }}" class="text-center bg-warning text-white">
                                        {{ $parent->nama }}
                                    </th>
                                @endforeach
                                
                                <th class="text-center align-middle" rowspan="2" style="width: 100px;">Rata-rata Total</th>
                            </tr>
                            <tr>
                                @foreach($regularParentsU7 as $parent)
                                    @php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    @endphp
                                    @foreach($children as $child)
                                        <th class="text-center" style="min-width: 70px; font-size: 11px;">{{ $child->nama }}</th>
                                    @endforeach
                                @endforeach
                                
                                @foreach($miniGameParentsU7 as $parent)
                                    @php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    @endphp
                                    @foreach($children as $child)
                                        <th class="text-center" style="min-width: 70px; font-size: 11px;">{{ $child->nama }}</th>
                                    @endforeach
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporanDataU7 as $index => $data)
                            <tr>
                                <td class="text-center">
                                    @if($index == 0)
                                        <span class="badge bg-warning text-dark fs-6">🥇 {{ $index + 1 }}</span>
                                    @elseif($index == 1)
                                        <span class="badge bg-secondary fs-6">🥈 {{ $index + 1 }}</span>
                                    @elseif($index == 2)
                                        <span class="badge bg-danger fs-6">🥉 {{ $index + 1 }}</span>
                                    @else
                                        <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $data['siswa']->nama }}</strong>
                                </td>
                                
                                @foreach($regularParentsU7 as $parent)
                                    @php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    @endphp
                                    @foreach($children as $child)
                                        <td class="text-center">
                                            @php
                                                $nilai = $data['nilai_kategori'][$child->id] ?? '-';
                                            @endphp
                                            @if($nilai !== '-')
                                                <span class="badge 
                                                    @if($nilai >= 80) bg-success
                                                    @elseif($nilai >= 60) bg-warning text-dark
                                                    @else bg-danger
                                                    @endif
                                                " style="font-size: 11px; min-width: 35px;">
                                                    {{ $nilai }}
                                                </span>
                                            @else
                                                <span class="text-muted" style="font-size: 11px;">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                @endforeach
                                
                                @foreach($miniGameParentsU7 as $parent)
                                    @php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    @endphp
                                    @foreach($children as $child)
                                        <td class="text-center">
                                            @php
                                                $nilai = $data['nilai_kategori'][$child->id] ?? '-';
                                            @endphp
                                            @if($nilai !== '-')
                                                <span class="badge 
                                                    @if($nilai >= 80) bg-success
                                                    @elseif($nilai >= 60) bg-warning text-dark
                                                    @else bg-danger
                                                    @endif
                                                " style="font-size: 11px; min-width: 35px;">
                                                    {{ $nilai }}
                                                </span>
                                            @else
                                                <span class="text-muted" style="font-size: 11px;">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                @endforeach
                                
                                <td class="text-center bg-light">
                                    @if($data['total_rata'] > 0)
                                        <strong class="text-dark" style="font-size: 12px;">{{ $data['total_rata'] }}</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                @php
                                    $totalCols = 2;
                                    $regularParentsU7 = $parentKategorisU7->where('minggu_terakhir', 0);
                                    $miniGameParentsU7 = $parentKategorisU7->where('minggu_terakhir', 1);
                                    foreach($regularParentsU7 as $p) {
                                        $totalCols += $allKategoris->where('parent_id', $p->id)->count();
                                    }
                                    foreach($miniGameParentsU7 as $p) {
                                        $totalCols += $allKategoris->where('parent_id', $p->id)->count();
                                    }
                                    $totalCols += 1; // rata-rata column
                                @endphp
                                <td colspan="{{ $totalCols }}" class="text-center text-muted">
                                    Tidak ada siswa U-7 dengan data evaluasi untuk bulan ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Laporan U-12 -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-trophy"></i> Kelompok U-12 (Usia 8-12 Tahun)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="table-success">
                            <tr>
                                <th class="text-center align-middle" rowspan="2" style="width: 50px;">Peringkat</th>
                                <th class="align-middle" rowspan="2">Nama Siswa</th>
                                
                                @php
                                    $regularParentsU12 = $parentKategorisU12->where('minggu_terakhir', 0);
                                    $miniGameParentsU12 = $parentKategorisU12->where('minggu_terakhir', 1);
                                @endphp
                                
                                @foreach($regularParentsU12 as $parent)
                                    @php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    @endphp
                                    <th colspan="{{ $children->count() }}" class="text-center 
                                        @if($parent->nama === 'Teknik') bg-primary
                                        @elseif($parent->nama === 'Etika') bg-success
                                        @else bg-info
                                        @endif text-white">
                                        {{ $parent->nama }}
                                    </th>
                                @endforeach
                                
                                @foreach($miniGameParentsU12 as $parent)
                                    @php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    @endphp
                                    <th colspan="{{ $children->count() }}" class="text-center bg-warning text-white">
                                        {{ $parent->nama }}
                                    </th>
                                @endforeach
                                
                                <th class="text-center align-middle" rowspan="2" style="width: 100px;">Rata-rata Total</th>
                            </tr>
                            <tr>
                                @foreach($regularParentsU12 as $parent)
                                    @php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    @endphp
                                    @foreach($children as $child)
                                        <th class="text-center" style="min-width: 70px; font-size: 11px;">{{ $child->nama }}</th>
                                    @endforeach
                                @endforeach
                                
                                @foreach($miniGameParentsU12 as $parent)
                                    @php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    @endphp
                                    @foreach($children as $child)
                                        <th class="text-center" style="min-width: 70px; font-size: 11px;">{{ $child->nama }}</th>
                                    @endforeach
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporanDataU12 as $index => $data)
                            <tr>
                                <td class="text-center">
                                    @if($index == 0)
                                        <span class="badge bg-warning text-dark fs-6">🥇 {{ $index + 1 }}</span>
                                    @elseif($index == 1)
                                        <span class="badge bg-secondary fs-6">🥈 {{ $index + 1 }}</span>
                                    @elseif($index == 2)
                                        <span class="badge bg-danger fs-6">🥉 {{ $index + 1 }}</span>
                                    @else
                                        <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $data['siswa']->nama }}</strong>
                                </td>
                                
                                @foreach($regularParentsU12 as $parent)
                                    @php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    @endphp
                                    @foreach($children as $child)
                                        <td class="text-center">
                                            @php
                                                $nilai = $data['nilai_kategori'][$child->id] ?? '-';
                                            @endphp
                                            @if($nilai !== '-')
                                                <span class="badge 
                                                    @if($nilai >= 80) bg-success
                                                    @elseif($nilai >= 60) bg-warning text-dark
                                                    @else bg-danger
                                                    @endif
                                                " style="font-size: 11px; min-width: 35px;">
                                                    {{ $nilai }}
                                                </span>
                                            @else
                                                <span class="text-muted" style="font-size: 11px;">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                @endforeach
                                
                                @foreach($miniGameParentsU12 as $parent)
                                    @php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    @endphp
                                    @foreach($children as $child)
                                        <td class="text-center">
                                            @php
                                                $nilai = $data['nilai_kategori'][$child->id] ?? '-';
                                            @endphp
                                            @if($nilai !== '-')
                                                <span class="badge 
                                                    @if($nilai >= 80) bg-success
                                                    @elseif($nilai >= 60) bg-warning text-dark
                                                    @else bg-danger
                                                    @endif
                                                " style="font-size: 11px; min-width: 35px;">
                                                    {{ $nilai }}
                                                </span>
                                            @else
                                                <span class="text-muted" style="font-size: 11px;">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                @endforeach
                                
                                <td class="text-center bg-light">
                                    @if($data['total_rata'] > 0)
                                        <strong class="text-dark" style="font-size: 12px;">{{ $data['total_rata'] }}</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                @php
                                    $totalCols = 2;
                                    $regularParentsU12 = $parentKategorisU12->where('minggu_terakhir', 0);
                                    $miniGameParentsU12 = $parentKategorisU12->where('minggu_terakhir', 1);
                                    foreach($regularParentsU12 as $p) {
                                        $totalCols += $allKategoris->where('parent_id', $p->id)->count();
                                    }
                                    foreach($miniGameParentsU12 as $p) {
                                        $totalCols += $allKategoris->where('parent_id', $p->id)->count();
                                    }
                                    $totalCols += 1; // rata-rata column
                                @endphp
                                    $totalCols += 1; // rata-rata column
                                @endphp
                                <td colspan="{{ $totalCols }}" class="text-center text-muted">
                                    Tidak ada siswa U-12 dengan data evaluasi untuk bulan ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Keterangan -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h6>Keterangan:</h6>
                <ul class="small text-muted">
                    <li>Nilai rata-rata dihitung dari semua evaluasi dalam bulan yang dipilih</li>
                    <li>Kategori dengan minggu terakhir (Mini Game) tidak dihitung dalam rata-rata</li>
                    <li>Siswa dikelompokkan berdasarkan kelompok umur dan diurutkan berdasarkan rata-rata total tertinggi</li>
                    <li>Warna Badge:
                        <span class="badge bg-success">≥ 80</span>
                        <span class="badge bg-warning text-dark">60-79</span>
                        <span class="badge bg-danger">< 60</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
