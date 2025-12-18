<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPenilaian extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi',
        'urutan',
        'aktif',
    ];
    
    protected $casts = [
        'aktif' => 'boolean',
    ];
    
    public function evaluasi()
    {
        return $this->hasMany(EvaluasiSiswa::class);
    }
}
