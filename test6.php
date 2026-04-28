<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ticker = 'TCS.NS';
$url = "https://query1.finance.yahoo.com/v8/finance/chart/" . urlencode($ticker);
$data = Illuminate\Support\Facades\Http::get($url)->json();

if (isset($data['chart']['result'][0]['meta']['regularMarketPrice'])) {
    echo "PRICE IN META: " . $data['chart']['result'][0]['meta']['regularMarketPrice'] . "\n";
} else {
    echo "NO 'regularMarketPrice' IN META\n";
    print_r(array_keys($data['chart']['result'][0]['meta']));
}

if (isset($data['chart']['result'][0]['indicators']['quote'][0]['close'])) {
    $closes = $data['chart']['result'][0]['indicators']['quote'][0]['close'];
    // filter nulls
    $closes = array_filter($closes, function($val) { return $val !== null; });
    $lastClose = end($closes);
    echo "LAST CLOSE IN SERIES: " . $lastClose . "\n";
}
