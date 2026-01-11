<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TagihanSiswa extends Model
{
    protected $fillable = [
        'siswa_id',
        'paket_iuran_id',
        'periode_bulan',
        'periode_tahun',
        'nominal',
        'jatuh_tempo',
        'status',
        'catatan',
    ];

    protected $casts = [
        'jatuh_tempo' => 'date',
        'nominal' => 'decimal:2',
    ];

    // Relationship: Tagihan milik siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relationship: Tagihan dari paket
    public function paket()
    {
        return $this->belongsTo(PaketIuran::class, 'paket_iuran_id');
    }

    // Helper: Format nominal ke Rupiah
    public function getNominalFormatAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }

    // Helper: Nama periode
    public function getPeriodeAttribute()
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $bulan[$this->periode_bulan] . ' ' . $this->periode_tahun;
    }

    // Helper: Cek apakah terlambat
    public function getTerlambatAttribute()
    {
        return $this->status === 'belum_bayar' && Carbon::now()->isAfter($this->jatuh_tempo);
    }

    // Scope: Filter by status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope: Tagihan belum bayar
    public function scopeBelumBayar($query)
    {
        return $query->where('status', 'belum_bayar');
    }

    // Scope: Tagihan terlambat
    public function scopeTerlambat($query)
    {
        return $query->where('status', 'belum_bayar')
            ->where('jatuh_tempo', '<', Carbon::now());
    }
}
