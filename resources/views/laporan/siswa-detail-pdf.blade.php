<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Siswa - {{ $siswa->nama }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #2c3e50;
        }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 25px 20px;
            margin-bottom: 20px;
            position: relative;
        }
        .header-logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 10px;
            border-radius: 10px;
            background: white;
            padding: 5px;
        }
        .header h1 {
            color: white;
            margin-bottom: 8px;
            font-size: 22px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .header h2 {
            color: white;
            font-size: 16px;
            font-weight: normal;
            opacity: 0.95;
        }
        .header .date {
            margin-top: 10px;
            font-size: 10px;
            opacity: 0.9;
        }
        
        /* Info Box */
        .info-box {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border: 2px solid #667eea;
            border-radius: 8px;
        }
        .info-box h3 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 14px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 8px;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
            padding: 5px 0;
        }
        .info-label {
            display: table-cell;
            width: 150px;
            font-weight: bold;
            color: #667eea;
            font-size: 11px;
        }
        .info-value {
            display: table-cell;
            color: #2c3e50;
            font-weight: 600;
        }
        
        /* Highlight Box */
        .highlight-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            text-align: center;
            margin-bottom: 25px;
            border-radius: 10px;
            border: 3px solid white;
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.4);
        }
        .highlight-box p {
            font-size: 12px;
            opacity: 0.95;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        .highlight-box h2 {
            font-size: 42px;
            margin: 15px 0;
            font-weight: bold;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        /* Stats Container */
        .stats-container {
            margin-bottom: 20px;
        }
        .stat-box {
            width: 23%;
            display: inline-block;
            text-align: center;
            padding: 15px 10px;
            margin-right: 2%;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            vertical-align: top;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .stat-box:last-child {
            margin-right: 0;
        }
        .stat-box h3 {
            font-size: 26px;
            color: #667eea;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .stat-box p {
            color: #7f8c8d;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        /* Section Title */
        .section-title {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 15px;
            margin: 25px 0 15px 0;
            font-size: 13px;
            font-weight: bold;
            border-radius: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
        }
        
        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }
        th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 10px 8px;
            text-align: center;
            font-weight: bold;
            border-bottom: 2px solid #667eea;
            color: #667eea;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td {
            padding: 8px 6px;
            border-bottom: 1px solid #e9ecef;
            text-align: center;
            font-size: 10px;
        }
        tr:last-child td {
            border-bottom: none;
        }
        
        /* Badge */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 0.3px;
        }
        .badge-success { 
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        .badge-warning { 
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white;
        }
        .badge-danger { 
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }
        .badge-primary { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            padding: 20px;
            border-top: 3px solid #667eea;
            text-align: center;
            font-size: 9px;
            color: #7f8c8d;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .footer strong {
            color: #667eea;
            font-size: 11px;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <img src="{{ public_path('images/logobinabola.jpeg') }}" class="header-logo">
        <h1>⚽ BINABOLA - SEKOLAH SEPAK BOLA ⚽</h1>
        <h2>Laporan Detail Siswa</h2>
        <p class="date">Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
    </div>

    <!-- Info Siswa -->
    <div class="info-box">
        <h3>📋 INFORMASI SISWA</h3>
        <div class="info-row">
            <div class="info-label">Nama Lengkap</div>
            <div class="info-value">: {{ $siswa->nama }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Umur</div>
            <div class="info-value">: {{ $umur }} Tahun</div>
        </div>
        <div class="info-row">
            <div class="info-label">Tanggal Lahir</div>
            <div class="info-value">: {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d F Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Posisi / Minat</div>
            <div class="info-value">: {{ $siswa->minat_posisi_string }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Status</div>
            <div class="info-value">: {{ $siswa->status }}</div>
        </div>
        @if($siswa->alamat)
        <div class="info-row">
            <div class="info-label">Alamat</div>
            <div class="info-value">: {{ $siswa->alamat }}</div>
        </div>
        @endif
    </div>

    <!-- Tingkat Kehadiran -->
    <div class="highlight-box">
        <p>Tingkat Kehadiran (30 Hari Terakhir)</p>
        <h2>{{ $statistikKehadiran['persentase_hadir'] }}%</h2>
        <p>dari {{ $statistikKehadiran['total'] }} hari</p>
    </div>

    <!-- Statistik Kehadiran -->
    <div class="section-title">📊 STATISTIK KEHADIRAN</div>
    <div class="stats-container">
        <div class="stat-box">
            <h3 style="color: #28a745;">{{ $statistikKehadiran['hadir'] }}</h3>
            <p>Hadir</p>
        </div>
        <div class="stat-box">
            <h3 style="color: #17a2b8;">{{ $statistikKehadiran['izin'] }}</h3>
            <p>Izin</p>
        </div>
        <div class="stat-box">
            <h3 style="color: #ffc107;">{{ $statistikKehadiran['sakit'] }}</h3>
            <p>Sakit</p>
        </div>
        <div class="stat-box">
            <h3 style="color: #dc3545;">{{ $statistikKehadiran['alpa'] }}</h3>
            <p>Alpa</p>
        </div>
    </div>

    <!-- Statistik Evaluasi -->
    <div class="section-title">🎯 STATISTIK EVALUASI</div>
    <div class="stats-container">
        <div class="stat-box">
            <h3>{{ $statistikEvaluasi['total_evaluasi'] }}</h3>
            <p>Total Evaluasi</p>
        </div>
        <div class="stat-box">
            <h3>{{ $statistikEvaluasi['rata_rata_keseluruhan'] }}</h3>
            <p>Rata-rata Nilai</p>
        </div>
        <div class="stat-box">
            <h3 style="color: #28a745;">{{ $statistikEvaluasi['nilai_tertinggi'] }}</h3>
            <p>Nilai Tertinggi</p>
        </div>
        <div class="stat-box">
            <h3 style="color: #dc3545;">{{ $statistikEvaluasi['nilai_terendah'] }}</h3>
            <p>Nilai Terendah</p>
        </div>
    </div>

    <!-- Kategori Terbaik & Terlemah -->
    @if(isset($statistikEvaluasi['kategori_terbaik']))
    <div style="margin-bottom: 25px;">
        <div style="width: 48%; display: inline-block; padding: 18px; background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border: 2px solid #28a745; border-radius: 8px; margin-right: 4%; vertical-align: top;">
            <h4 style="color: #155724; margin-bottom: 12px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">✓ Kategori Terbaik</h4>
            <h3 style="color: #155724; font-size: 16px; margin-bottom: 8px;">{{ $statistikEvaluasi['kategori_terbaik']['nama'] }}</h3>
            <p style="color: #155724; margin-top: 5px; font-size: 11px;"><strong>Rata-rata: {{ $statistikEvaluasi['kategori_terbaik']['rata_rata'] }}</strong></p>
        </div>
        <div style="width: 48%; display: inline-block; padding: 18px; background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); border: 2px solid #dc3545; border-radius: 8px; vertical-align: top;">
            <h4 style="color: #721c24; margin-bottom: 12px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">⚠ Perlu Ditingkatkan</h4>
            <h3 style="color: #721c24; font-size: 16px; margin-bottom: 8px;">{{ $statistikEvaluasi['kategori_terlemah']['nama'] }}</h3>
            <p style="color: #721c24; margin-top: 5px; font-size: 11px;"><strong>Rata-rata: {{ $statistikEvaluasi['kategori_terlemah']['rata_rata'] }}</strong></p>
        </div>
    </div>
    @endif

    <!-- Page Break -->
    <div class="page-break"></div>

    <!-- Nilai per Kategori -->
    <div class="section-title">📈 NILAI PER KATEGORI</div>
    <table>
        <thead>
            <tr>
                <th style="width: 50%;">Kategori</th>
                <th style="width: 25%;">Jumlah Evaluasi</th>
                <th style="width: 25%;">Rata-rata Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($perKategori as $kategori)
            <tr>
                <td style="text-align: left; padding-left: 15px;">{{ $kategori['nama'] }}</td>
                <td>{{ $kategori['total'] }}</td>
                <td>
                    <span class="badge {{ $kategori['rata_rata'] >= 70 ? 'badge-success' : ($kategori['rata_rata'] >= 50 ? 'badge-warning' : 'badge-danger') }}">
                        {{ $kategori['rata_rata'] }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Riwayat Evaluasi -->
    <div class="section-title">📊 RIWAYAT EVALUASI PER MINGGU</div>
    @if($evaluasiData->count() > 0)
        @php
            $evaluasiPerMinggu = $evaluasiData->groupBy('minggu')->sortByDesc(function($item, $key) {
                return $key;
            })->take(10);
            $kategoris = \App\Models\KategoriPenilaian::where('aktif', true)->orderBy('urutan')->get();
        @endphp
        
        @foreach($evaluasiPerMinggu as $minggu => $evaluasiMinggu)
        <div style="background: white; border: 2px solid #e0e0e0; border-radius: 8px; padding: 15px; margin-bottom: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <div style="display: table; width: 100%; margin-bottom: 12px;">
                <div style="display: table-cell; width: 50%;">
                    <span class="badge badge-primary" style="font-size: 11px; padding: 6px 12px;">MINGGU {{ $minggu }}</span>
                </div>
                <div style="display: table-cell; width: 50%; text-align: right;">
                    <span style="color: #7f8c8d; font-size: 10px;">
                        📅 {{ $evaluasiMinggu->first()->tanggal_evaluasi ? \Carbon\Carbon::parse($evaluasiMinggu->first()->tanggal_evaluasi)->format('d F Y') : '-' }}
                    </span>
                </div>
            </div>
            
            <table style="border: none; margin-bottom: 12px;">
                <thead>
                    <tr>
                        <th style="width: 60%; text-align: left; padding-left: 12px; background: #f8f9fa; border: 1px solid #e0e0e0;">Kategori Penilaian</th>
                        <th style="width: 20%; background: #f8f9fa; border: 1px solid #e0e0e0;">Nilai</th>
                        <th style="width: 20%; background: #f8f9fa; border: 1px solid #e0e0e0;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kategoris as $kategori)
                        @php
                            $evalKategori = $evaluasiMinggu->firstWhere('kategori_penilaian_id', $kategori->id);
                            $nilai = $evalKategori ? $evalKategori->nilai : null;
                        @endphp
                        <tr>
                            <td style="text-align: left; padding-left: 12px; border: 1px solid #e9ecef;">
                                {{ $kategori->nama }}
                            </td>
                            <td style="border: 1px solid #e9ecef;">
                                @if($nilai !== null)
                                    <strong style="font-size: 12px; color: #2c3e50;">{{ $nilai }}</strong>
                                @else
                                    <span style="color: #bbb;">-</span>
                                @endif
                            </td>
                            <td style="border: 1px solid #e9ecef;">
                                @if($nilai !== null)
                                    <span class="badge 
                                        @if($nilai >= 80) badge-success
                                        @elseif($nilai >= 60) badge-warning
                                        @else badge-danger
                                        @endif">
                                        @if($nilai >= 80) Baik
                                        @elseif($nilai >= 60) Cukup
                                        @else Perlu Latihan
                                        @endif
                                    </span>
                                @else
                                    <span style="color: #bbb;">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 10px 15px; border-radius: 5px; text-align: right;">
                @php
                    // Filter nilai yang tidak null (nilai 0 dari ketidakhadiran tetap dihitung)
                    $nilaiValid = $evaluasiMinggu->filter(function($eval) {
                        return $eval->nilai !== null;
                    });
                    $rataRata = $nilaiValid->isNotEmpty() ? $nilaiValid->avg('nilai') : 0;
                @endphp
                <span style="color: #667eea; font-weight: bold; font-size: 11px;">
                    RATA-RATA MINGGU INI: 
                    <span style="font-size: 14px;">{{ number_format($rataRata, 1) }}</span>
                </span>
            </div>
        </div>
        @endforeach
        
        @if($evaluasiData->groupBy('minggu')->count() > 10)
        <p style="text-align: center; color: #7f8c8d; font-style: italic; margin-top: 15px; font-size: 10px;">
            Menampilkan 10 minggu terakhir dari total {{ $evaluasiData->groupBy('minggu')->count() }} minggu evaluasi
        </p>
        @endif
    @else
        <p style="text-align: center; color: #999; padding: 30px; background: white; border-radius: 8px;">
            Belum ada data evaluasi
        </p>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>⚽ BinaBola - Sekolah Sepak Bola ⚽</strong></p>
        <p style="margin: 8px 0;">Laporan digenerate pada {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</p>
        <p style="margin-top: 10px; font-style: italic;">
            Dokumen ini bersifat rahasia dan hanya untuk keperluan internal
        </p>
    </div>
</body>
</html>
