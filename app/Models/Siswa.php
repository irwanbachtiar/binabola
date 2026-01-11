<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\User;

class Siswa extends Model
{
    protected $fillable = [
        'nama',
        'foto',
        'tanggal_lahir',
        'tanggal_masuk',
        'minat_posisi',
        'telepon',
        'email',
        'alamat',
        'tinggi_badan',
        'berat_badan',
        'status',
        'paket_iuran_id',
    ];
    
    protected $casts = [
        'tanggal_lahir' => 'datetime',
        'tanggal_masuk' => 'datetime',
        'minat_posisi' => 'array',
    ];
    
    /**
     * Get umur siswa dalam tahun
     */
    protected function umur(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tanggal_lahir ? $this->tanggal_lahir->age : null,
        );
    }
    
    /**
     * Get umur detail (tahun dan bulan)
     */
    public function getUmurDetailAttribute()
    {
        if (!$this->tanggal_lahir) {
            return '-';
        }
        
        $years = $this->tanggal_lahir->age;
        $months = $this->tanggal_lahir->diffInMonths(now()) % 12;
        
        if ($years == 0) {
            return $months . ' bulan';
        }
        
        if ($months == 0) {
            return $years . ' tahun';
        }
        
        return $years . ' tahun ' . $months . ' bulan';
    }
    
    /**
     * Get kelompok umur siswa (U-7 atau U-12)
     */
    public function getKelompokUmurAttribute()
    {
        if (!$this->tanggal_lahir) {
            return '-';
        }
        
        $umur = $this->tanggal_lahir->age;
        
        if ($umur >= 3 && $umur <= 7) {
            return 'U-7';
        } elseif ($umur >= 8 && $umur <= 12) {
            return 'U-12';
        } else {
            return '-';
        }
    }
    
    /**
     * Get minat posisi sebagai string dengan comma
     */
    public function getMinatPosisiStringAttribute()
    {
        if (!$this->minat_posisi || empty($this->minat_posisi)) {
            return '-';
        }
        
        // Handle if minat_posisi is already an array
        if (is_array($this->minat_posisi)) {
            return implode(', ', $this->minat_posisi);
        }
        
        // Handle if it's a JSON string
        $decoded = json_decode($this->minat_posisi, true);
        if (is_array($decoded)) {
            return implode(', ', $decoded);
        }
        
        // Fallback: return as is if it's a simple string
        return $this->minat_posisi;
    }
    
    /**
     * Relationship dengan absensi
     */
    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    /**
     * Relationship dengan evaluasi siswa
     */
    public function evaluasiSiswas()
    {
        return $this->hasMany(EvaluasiSiswa::class);
    }

    /**
     * Relationship: siswa connected with parent users (orangtua)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'siswa_user');
    }

    /**
     * Relationship: siswa has one paket iuran
     */
    public function paketIuran()
    {
        return $this->belongsTo(PaketIuran::class, 'paket_iuran_id');
    }

    /**
     * Relationship: siswa has many pembayaran
     */
    public function pembayarans()
    {
        return $this->hasMany(PembayaranIuran::class);
    }

    /**
     * Relationship: siswa has many tagihan
     */
    public function tagihans()
    {
        return $this->hasMany(TagihanSiswa::class);
    }
}
