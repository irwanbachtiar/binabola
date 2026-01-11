<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateMiniGameSeeder extends Seeder
{
    public function run(): void
    {
        // Get all Mini Game parent categories (both U-7 and U-12)
        $miniGameParents = DB::table('kategori_penilaians')
            ->where('nama', 'Mini Game')
            ->whereNull('parent_id')
            ->get();

        foreach ($miniGameParents as $parent) {
            // Update parent
            DB::table('kategori_penilaians')
                ->where('id', $parent->id)
                ->update(['minggu_terakhir' => 1]);

            // Update all children
            DB::table('kategori_penilaians')
                ->where('parent_id', $parent->id)
                ->update(['minggu_terakhir' => 1]);
        }

        echo "Updated " . $miniGameParents->count() . " Mini Game parent categories and their children\n";
    }
}
