<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'admin@binabola.com')->first();
if (!$user) {
    echo "NOT_FOUND\n";
    exit(0);
}

echo $user->email . '|' . ($user->role ?? 'NULL') . PHP_EOL;
