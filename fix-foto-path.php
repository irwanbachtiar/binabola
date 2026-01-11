<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Siswa;

echo "=== FIX FOTO PATH SISWA ===" . PHP_EOL . PHP_EOL;

$siswas = Siswa::all();
$updated = 0;

foreach ($siswas as $siswa) {
    if ($siswa->foto && !str_starts_with($siswa->foto, 'uploads/')) {
        $oldPath = $siswa->foto;
        $siswa->foto = 'uploads/siswa/' . $siswa->foto;
        $siswa->save();
        
        echo "✓ Updated: {$siswa->nama}" . PHP_EOL;
        echo "  Old: {$oldPath}" . PHP_EOL;
        echo "  New: {$siswa->foto}" . PHP_EOL . PHP_EOL;
        
        $updated++;
    }
}

if ($updated > 0) {
    echo "Total updated: {$updated} siswa" . PHP_EOL;
} else {
    echo "No updates needed. All paths are correct." . PHP_EOL;
}

echo PHP_EOL . "✓ DONE!" . PHP_EOL;
