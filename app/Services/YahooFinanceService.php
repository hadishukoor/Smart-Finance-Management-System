<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class YahooFinanceService
{
    /**
     * Query the live Yahoo Finance external API for realtime ticker tracking securely.
     */
    public static function getLivePrice($ticker)
    {
        $ticker = strtoupper(trim($ticker));
        
        // Ensure National Stock Exchange formatting natively for valid India stocks
        if (!str_contains($ticker, '.') && !str_contains($ticker, '^')) {
            $ticker = $ticker . '.NS'; 
        }
        
        $url = "https://query1.finance.yahoo.com/v8/finance/chart/" . urlencode($ticker);
        
        try {
            // Establish a live SSL GET request securely pulling the algorithmic chart wrapper
            $response = Http::timeout(5)->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
            ])->get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // If Yahoo successfully maps the JSON array block logically identifying a stock
                if (isset($data['chart']['result'][0]['meta']['regularMarketPrice'])) {
                    $meta = $data['chart']['result'][0]['meta'];
                    return [
                        'valid' => true,
                        'ticker' => $meta['symbol'] ?? $ticker,
                        'price' => $meta['regularMarketPrice'],
                        'previousClose' => $meta['chartPreviousClose'] ?? $meta['regularMarketPrice'],
                        'currency' => $meta['currency'] ?? 'INR'
                    ];
                } elseif (isset($data['chart']['result'][0]['indicators']['quote'][0]['close'])) {
                    $closes = array_filter($data['chart']['result'][0]['indicators']['quote'][0]['close'], function($val) { return $val !== null; });
                    if(!empty($closes)) {
                        $meta = $data['chart']['result'][0]['meta'];
                        $latestClose = end($closes);
                        return [
                            'valid' => true,
                            'ticker' => $meta['symbol'] ?? $ticker,
                            'price' => $latestClose,
                            'previousClose' => $meta['chartPreviousClose'] ?? $latestClose,
                            'currency' => $meta['currency'] ?? 'INR'
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            // Abstraction boundary intercepts timeout/connection issues silently
        }
        
        return [
            'valid' => false,
            'ticker' => $ticker,
            'price' => null,
            'previousClose' => null
        ];
    }
}
