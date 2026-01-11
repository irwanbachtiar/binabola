<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class SiswaUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get orangtua users
        $budi = User::where('email', 'budi@example.com')->first();
        $siti = User::where('email', 'siti@example.com')->first();

        // Get siswa
        $ahmad = Siswa::where('nama', 'Ahmad Ridwan')->first();
        $bima = Siswa::where('nama', 'Bima Sakti')->first();
        $citra = Siswa::where('nama', 'Citra Dewi')->first();
        
        // Connect Budi Santoso with Ahmad and Bima (his children)
        if ($ahmad && $budi) {
            DB::table('siswa_user')->insert([
                'siswa_id' => $ahmad->id,
                'user_id' => $budi->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        if ($bima && $budi) {
            DB::table('siswa_user')->insert([
                'siswa_id' => $bima->id,
                'user_id' => $budi->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Connect Siti Nurhaliza with Citra (her child)
        if ($citra && $siti) {
            DB::table('siswa_user')->insert([
                'siswa_id' => $citra->id,
                'user_id' => $siti->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        \Log::info('SiswaUserSeeder: Connected orangtua with siswa successfully');
    }
}
