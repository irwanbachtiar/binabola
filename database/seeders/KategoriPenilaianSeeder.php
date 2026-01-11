<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KategoriPenilaian;

class KategoriPenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'nama' => 'Fisik',
                'bobot' => 30,
                'urutan' => 1,
                'aktif' => true,
            ],
            [
                'nama' => 'Teknik',
                'bobot' => 40,
                'urutan' => 2,
                'aktif' => true,
            ],
            [
                'nama' => 'Disiplin',
                'bobot' => 30,
                'urutan' => 3,
                'aktif' => true,
            ],
        ];

        foreach ($kategoris as $kategori) {
            KategoriPenilaian::updateOrCreate(
                ['nama' => $kategori['nama']],
                $kategori
            );
        }
    }
}
