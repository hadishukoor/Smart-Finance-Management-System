<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ticker = 'TCS.NS';
$url = "https://query1.finance.yahoo.com/v8/finance/chart/" . urlencode($ticker);
$response = Illuminate\Support\Facades\Http::get($url);
print_r($response->json()['chart']['result'][0]['meta']);
