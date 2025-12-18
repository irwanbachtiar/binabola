<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluasiSiswa extends Model
{
    protected $fillable = [
        'siswa_id',
        'kategori_penilaian_id',
        'minggu',
        'tahun',
        'nilai',
        'catatan',
        'dinilai_oleh',
    ];
    
    protected $casts = [
        'nilai' => 'decimal:2',
    ];
    
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
    
    public function kategori()
    {
        return $this->belongsTo(KategoriPenilaian::class, 'kategori_penilaian_id');
    }
}
