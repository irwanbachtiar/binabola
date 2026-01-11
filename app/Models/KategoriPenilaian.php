<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPenilaian extends Model
{
    protected $fillable = [
        'parent_id',
        'kelompok_umur',
        'nama',
        'deskripsi',
        'urutan',
        'aktif',
        'minggu_terakhir',
    ];
    
    protected $casts = [
        'aktif' => 'boolean',
        'minggu_terakhir' => 'boolean',
    ];
    
    // Relationship untuk parent (kategori utama)
    public function parent()
    {
        return $this->belongsTo(KategoriPenilaian::class, 'parent_id');
    }
    
    // Relationship untuk children (sub-kategori)
    public function children()
    {
        return $this->hasMany(KategoriPenilaian::class, 'parent_id')->orderBy('urutan');
    }
    
    // Cek apakah ini kategori utama (parent)
    public function isParent()
    {
        return is_null($this->parent_id);
    }
    
    // Cek apakah ini sub-kategori
    public function isChild()
    {
        return !is_null($this->parent_id);
    }
    
    // Get hanya kategori utama (parent)
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }
    
    // Get hanya sub-kategori (children)
    public function scopeChildren($query)
    {
        return $query->whereNotNull('parent_id');
    }
    
    public function evaluasi()
    {
        return $this->hasMany(EvaluasiSiswa::class);
    }
}
