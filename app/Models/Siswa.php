<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Siswa extends Model
{
    protected $fillable = [
        'nama',
        'foto',
        'tanggal_lahir',
        'minat_posisi',
        'telepon',
        'email',
        'alamat',
        'tinggi_badan',
        'berat_badan',
        'status',
    ];
    
    protected $casts = [
        'tanggal_lahir' => 'date',
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
     * Get minat posisi sebagai string dengan comma
     */
    public function getMinatPosisiStringAttribute()
    {
        if (!$this->minat_posisi || empty($this->minat_posisi)) {
            return '-';
        }
        
        return implode(', ', $this->minat_posisi);
    }
    
    /**
     * Relationship dengan absensi
     */
    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
}
