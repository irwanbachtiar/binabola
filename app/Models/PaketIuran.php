<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketIuran extends Model
{
    protected $fillable = [
        'nama_paket',
        'kelompok_umur',
        'nominal',
        'durasi_bulan',
        'keterangan',
        'aktif',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'aktif' => 'boolean',
    ];

    // Relationship: Paket memiliki banyak pembayaran
    public function pembayarans()
    {
        return $this->hasMany(PembayaranIuran::class);
    }

    // Relationship: Paket memiliki banyak tagihan
    public function tagihans()
    {
        return $this->hasMany(TagihanSiswa::class);
    }

    // Helper: Format nominal ke Rupiah
    public function getNominalFormatAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }

    // Scope: Hanya paket aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    // Scope: Filter by kelompok umur
    public function scopeKelompokUmur($query, $kelompok)
    {
        return $query->where('kelompok_umur', $kelompok);
    }
}
