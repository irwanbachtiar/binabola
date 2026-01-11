<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranIuran extends Model
{
    protected $fillable = [
        'siswa_id',
        'paket_iuran_id',
        'periode_bulan',
        'periode_tahun',
        'tanggal_bayar',
        'nominal',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'nominal' => 'decimal:2',
    ];

    // Relationship: Pembayaran milik siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relationship: Pembayaran menggunakan paket
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

    // Scope: Filter by status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope: Filter by periode
    public function scopePeriode($query, $bulan, $tahun)
    {
        return $query->where('periode_bulan', $bulan)->where('periode_tahun', $tahun);
    }
}
