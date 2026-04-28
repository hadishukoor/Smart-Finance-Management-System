<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$url = "https://query1.finance.yahoo.com/v8/finance/quote?symbols=RELIANCE.NS,TCS.NS";
$response = Illuminate\Support\Facades\Http::get($url);
echo $response->status();
echo $response->body();
