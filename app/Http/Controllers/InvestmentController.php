<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Holding;
use App\Services\YahooFinanceService;

class InvestmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $month = date('m');
        $year = date('Y');
        
        $totalExpenses = $user->expenses()
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('amount');
            
        $savings = $user->monthly_salary - $totalExpenses;

        if ($savings < 1000) {
            $recommendationText = "Focus on saving aggressively. Build a capital safety buffer before engaging actively in volatile markets.";
        } elseif ($savings < 5000) {
            $recommendationText = "You have established a modest structural buffer. It is highly recommended to explore automated SIPs and stable Mutual Funds.";
        } else {
            $recommendationText = "Healthy surplus detected! You hold enough capital to actively map direct equity grids and secure high-yield Indian investments.";
        }
        
        // Complex structural mapping of all user holdings natively
        $holdings = $user->holdings()->latest()->get();
        
        $totalInvestment = 0;
        $currentValue = 0;
        
        foreach ($holdings as $holding) {
            $totalInvestment += ($holding->buy_price * $holding->quantity);
            $currentValue += ($holding->current_price * $holding->quantity);
        }
        
        $totalReturn = $currentValue - $totalInvestment;
        $returnPercentage = $totalInvestment > 0 ? ($totalReturn / $totalInvestment) * 100 : 0;
        
        $dayReturn = 0;
        foreach ($holdings as $holding) {
            $prev = $holding->previous_close ?? $holding->current_price;
            $dayReturn += ($holding->current_price - $prev) * $holding->quantity;
        }
        
        $stocks = [
            [
                'name' => 'TCS',
                'full_name' => 'Tata Consultancy Services',
                'description' => 'A global leader in IT services, consulting & business solutions. A highly reliable large-cap technology stock.',
                'link' => 'https://groww.in/stocks/tata-consultancy-services-ltd',
                'risk' => 'Low Risk',
                'badge_color' => 'success'
            ],
            [
                'name' => 'Reliance',
                'full_name' => 'Reliance Industries Ltd.',
                'description' => 'India\'s largest monolithic conglomerate spanning energy, petrochemicals, telecommunications, and retail sectors.',
                'link' => 'https://groww.in/stocks/reliance-industries-ltd',
                'risk' => 'Moderate Risk',
                'badge_color' => 'warning'
            ],
            [
                'name' => 'Infosys',
                'full_name' => 'Infosys Limited',
                'description' => 'Next-generation digital services and consulting firm providing an absolute technological backbone in Asian markets.',
                'link' => 'https://groww.in/stocks/infosys-ltd',
                'risk' => 'Moderate Risk',
                'badge_color' => 'warning'
            ],
            [
                'name' => 'HDFC',
                'full_name' => 'HDFC Bank',
                'description' => 'Leading private sector centralized bank offering premier fiscal, financial, and digital investment parameters natively.',
                'link' => 'https://groww.in/stocks/hdfc-bank-ltd',
                'risk' => 'Low Risk',
                'badge_color' => 'success'
            ],
            [
                'name' => 'Redington',
                'full_name' => 'Redington Ltd.',
                'description' => 'A premier end-to-end supply chain logistics provider mapping global IT expansion natively across structural enterprise markets.',
                'link' => 'https://groww.in/stocks/redington-india-ltd',
                'risk' => 'Moderate Risk',
                'badge_color' => 'warning'
            ]
        ];

        return view('investments.index', compact(
            'savings', 'recommendationText', 'stocks', 
            'holdings', 'totalInvestment', 'currentValue', 
            'totalReturn', 'returnPercentage', 'dayReturn'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'stock_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'buy_price' => 'required|numeric|min:0.01',
            'buy_date' => 'required|date'
        ]);
        
        // Securely intercept the request and validate physically against the Live NSE Stock Exchange
        $liveData = YahooFinanceService::getLivePrice($request->stock_name);
        
        if (!$liveData['valid']) {
            return back()->withInput()->withErrors(['stock_name' => 'Error: Initialization Failed. The provided Asset Designation (' . $request->stock_name . ') does not physically exist on tracked live markets! Please enter a valid Indian Ticker (e.g., RELIANCE).']);
        }
        
        $tickerName = str_replace('.NS', '', $liveData['ticker']);
        
        $existingHolding = auth()->user()->holdings()->where('stock_name', $tickerName)->first();
        
        if ($existingHolding) {
            // Calculate new average buy price and total quantity
            $totalOldValue = $existingHolding->quantity * $existingHolding->buy_price;
            $totalNewValue = $request->quantity * $request->buy_price;
            $newQuantity = $existingHolding->quantity + $request->quantity;
            $averageBuyPrice = ($totalOldValue + $totalNewValue) / $newQuantity;
            
            $existingHolding->update([
                'quantity' => $newQuantity,
                'buy_price' => $averageBuyPrice,
                'current_price' => $liveData['price'],
                'previous_close' => $liveData['previousClose']
            ]);
            
            return back()->with('success', 'Existing holding updated with new quantity and averaged buy price.');
        }

        auth()->user()->holdings()->create([
            'stock_name' => $tickerName,
            'quantity' => $request->quantity,
            'buy_price' => $request->buy_price,
            'buy_date' => $request->buy_date,
            'current_price' => $liveData['price'],
            'previous_close' => $liveData['previousClose']
        ]);
        
        return back()->with('success', 'Investment holding instantiated and synced live to market coordinates.');
    }
    
    public function syncAll()
    {
        $holdings = auth()->user()->holdings;
        $successCount = 0;
        
        foreach ($holdings as $holding) {
            $liveData = YahooFinanceService::getLivePrice($holding->stock_name);
            if ($liveData['valid']) {
                $holding->update([
                    'current_price' => $liveData['price'],
                    'previous_close' => $liveData['previousClose']
                ]);
                $successCount++;
            }
        }
        
        return back()->with('success', "Live Market Sync Complete! Instantaneously refreshed {$successCount} vectors mapping real-time valuation boundaries.");
    }
    
    public function update(Request $request, Holding $holding)
    {
        if ($holding->user_id !== auth()->id()) {
            abort(403);
        }
        
        $request->validate([
            'current_price' => 'required|numeric|min:0.01',
        ]);
        
        $holding->update([
            'current_price' => $request->current_price
        ]);
        
        return back()->with('success', 'Live market price logically synchronized across portfolio arrays.');
    }
    
    public function destroy(Holding $holding)
    {
        if ($holding->user_id !== auth()->id()) {
            abort(403);
        }
        
        $holding->delete();
        
        return back()->with('success', 'Holding liquidated and natively detached from core portfolio tracking.');
    }
}
