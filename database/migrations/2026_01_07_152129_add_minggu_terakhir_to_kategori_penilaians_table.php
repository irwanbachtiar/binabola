<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kategori_penilaians', function (Blueprint $table) {
            $table->boolean('minggu_terakhir')->default(false)->after('aktif');
        });
        
        // Update existing Mini Game parent category for both U-7 and U-12
        $miniGameParents = DB::table('kategori_penilaians')
            ->where('nama', 'Mini Game')
            ->whereNull('parent_id')
            ->get();
        
        foreach ($miniGameParents as $parent) {
            // Update parent
            DB::table('kategori_penilaians')
                ->where('id', $parent->id)
                ->update(['minggu_terakhir' => true]);
            
            // Update children
            DB::table('kategori_penilaians')
                ->where('parent_id', $parent->id)
                ->update(['minggu_terakhir' => true]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kategori_penilaians', function (Blueprint $table) {
            $table->dropColumn('minggu_terakhir');
        });
    }
};
