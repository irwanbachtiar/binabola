<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Statistik Siswa - {{ $bulan }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #667eea;
        }
        .header-logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: white;
            padding: 5px;
            display: block;
            margin: 0 auto 10px;
            object-fit: contain;
        }
        .header h1 {
            font-size: 18px;
            color: #667eea;
            margin-bottom: 5px;
        }
        .header h2 {
            font-size: 14px;
            color: #2c3e50;
            margin-bottom: 3px;
        }
        .header p {
            font-size: 9px;
            color: #7f8c8d;
        }
        
        .info-periode {
            background: #f8f9fa;
            padding: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
        }
        .info-periode strong {
            color: #667eea;
        }
        
        .summary-box {
            margin-bottom: 20px;
        }
        .summary-item {
            display: inline-block;
            width: 23%;
            text-align: center;
            padding: 10px;
            background: white;
            border: 1px solid #e0e0e0;
            margin-right: 2%;
            border-radius: 4px;
            vertical-align: top;
        }
        .summary-item:last-child {
            margin-right: 0;
        }
        .summary-item h3 {
            font-size: 20px;
            color: #667eea;
            margin-bottom: 3px;
        }
        .summary-item p {
            font-size: 9px;
            color: #7f8c8d;
            text-transform: uppercase;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background: #667eea;
            color: white;
            padding: 8px 5px;
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            border: 1px solid #5568d3;
        }
        td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
        }
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        tr:hover {
            background: #e9ecef;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-success { background: #28a745; color: white; }
        .badge-warning { background: #ffc107; color: #333; }
        .badge-danger { background: #dc3545; color: white; }
        .badge-info { background: #17a2b8; color: white; }
        .badge-secondary { background: #6c757d; color: white; }
        
        .text-left { text-align: left !important; padding-left: 8px !important; }
        
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
            font-size: 8px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <img src="{{ public_path('images/logobinabola.jpeg') }}" class="header-logo">
        <h1>BINABOLA - SEKOLAH SEPAK BOLA</h1>
        <h2>Laporan Statistik Siswa Bulanan</h2>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <!-- Info Periode -->
    <div class="info-periode">
        <strong>Periode:</strong> {{ $bulan }} | 
        <strong>Total Siswa:</strong> {{ $siswas->count() }} siswa
    </div>

    <!-- Summary -->
    <div class="summary-box">
        <div class="summary-item">
            <h3>{{ $totalSiswaAktif }}</h3>
            <p>Siswa Aktif</p>
        </div>
        <div class="summary-item">
            <h3>{{ $totalEvaluasi }}</h3>
            <p>Total Evaluasi</p>
        </div>
        <div class="summary-item">
            <h3>{{ $rataRataKehadiran }}%</h3>
            <p>Rata Kehadiran</p>
        </div>
        <div class="summary-item">
            <h3>{{ $rataRataNilai }}</h3>
            <p>Rata-rata Nilai</p>
        </div>
    </div>

    <!-- Tabel Statistik -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Nama Siswa</th>
                <th style="width: 8%;">Umur</th>
                <th style="width: 10%;">Kelompok</th>
                <th style="width: 8%;">Hadir</th>
                <th style="width: 8%;">Izin</th>
                <th style="width: 8%;">Sakit</th>
                <th style="width: 8%;">Alpa</th>
                <th style="width: 8%;">% Hadir</th>
                <th style="width: 8%;">Evaluasi</th>
                <th style="width: 9%;">Rata Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswas as $index => $siswa)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-left">{{ $siswa->nama }}</td>
                <td>{{ $siswa->umur }} th</td>
                <td>
                    <span class="badge {{ $siswa->kelompok_umur == 'U-12' ? 'badge-success' : 'badge-info' }}">
                        {{ $siswa->kelompok_umur }}
                    </span>
                </td>
                <td><strong style="color: #28a745;">{{ $siswa->kehadiran['hadir'] }}</strong></td>
                <td>{{ $siswa->kehadiran['izin'] }}</td>
                <td>{{ $siswa->kehadiran['sakit'] }}</td>
                <td><strong style="color: #dc3545;">{{ $siswa->kehadiran['alpa'] }}</strong></td>
                <td>
                    <span class="badge 
                        @if($siswa->kehadiran['persentase'] >= 80) badge-success
                        @elseif($siswa->kehadiran['persentase'] >= 60) badge-warning
                        @else badge-danger
                        @endif">
                        {{ $siswa->kehadiran['persentase'] }}%
                    </span>
                </td>
                <td>{{ $siswa->evaluasi['total'] }}</td>
                <td>
                    @if($siswa->evaluasi['rata_rata'] > 0)
                        <span class="badge 
                            @if($siswa->evaluasi['rata_rata'] >= 80) badge-success
                            @elseif($siswa->evaluasi['rata_rata'] >= 60) badge-warning
                            @else badge-danger
                            @endif">
                            {{ $siswa->evaluasi['rata_rata'] }}
                        </span>
                    @else
                        <span class="badge badge-secondary">-</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p><strong>BinaBola - Sekolah Sepak Bola</strong></p>
        <p>Dokumen ini bersifat rahasia dan hanya untuk keperluan internal</p>
    </div>
</body>
</html>
