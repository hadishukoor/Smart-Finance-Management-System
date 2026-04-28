<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$url = "https://query1.finance.yahoo.com/v8/finance/chart/TCS.NS?range=1d&interval=1m";
$response = Illuminate\Support\Facades\Http::get($url);
$data = $response->json();
$prices = $data['chart']['result'][0]['indicators']['quote'][0]['close'] ?? [];
$prices = array_filter($prices, function($val) { return $val !== null; });
echo end($prices); 
