<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$id = DB::table('liquidations')->insertGetId([
    'user_id' => null,
    'cutoff_period' => 'May 2026',
    'amount' => 1000,
    'status' => 'pending',
    'created_at' => now(),
    'updated_at' => now(),
]);

echo "Inserted liquidation id: {$id}\n";
