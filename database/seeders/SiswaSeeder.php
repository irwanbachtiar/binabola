<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Siswa;
use Carbon\Carbon;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswa = [
            [
                'nama' => 'Ahmad Ridwan',
                'foto' => 'https://ui-avatars.com/api/?name=Ahmad+Ridwan&background=667eea&color=fff&size=200',
                'tanggal_lahir' => '2010-05-15',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                'telepon' => '081234567890',
                'email' => 'ahmad.ridwan@student.com',
                'minat_posisi' => json_encode(['Striker', 'Winger']),
                'tinggi_badan' => 165,
                'berat_badan' => 55,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Bima Sakti',
                'foto' => 'https://ui-avatars.com/api/?name=Bima+Sakti&background=764ba2&color=fff&size=200',
                'tanggal_lahir' => '2011-03-20',
                'alamat' => 'Jl. Sudirman No. 456, Jakarta',
                'telepon' => '081234567891',
                'email' => 'bima.sakti@student.com',
                'minat_posisi' => json_encode(['Midfielder']),
                'tinggi_badan' => 160,
                'berat_badan' => 50,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Citra Dewi',
                'foto' => 'https://ui-avatars.com/api/?name=Citra+Dewi&background=f093fb&color=fff&size=200',
                'tanggal_lahir' => '2012-07-10',
                'alamat' => 'Jl. Gatot Subroto No. 789, Jakarta',
                'telepon' => '081234567892',
                'email' => 'citra.dewi@student.com',
                'minat_posisi' => json_encode(['Defender', 'Goalkeeper']),
                'tinggi_badan' => 155,
                'berat_badan' => 48,
                'status' => 'aktif',
            ],
        ];

        foreach ($siswa as $s) {
            Siswa::create($s);
        }
    }
}
