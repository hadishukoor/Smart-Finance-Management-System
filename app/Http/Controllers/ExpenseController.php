<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $month = request('month', date('m'));
        $year = request('year', date('Y'));

        $expenses = $user->expenses()
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->latest()
            ->get();
        
        $categoryData = Expense::where('user_id', $user->id)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();

        $total = $expenses->sum('amount');
        
        // Locked correctly to User Profile Globally! 
        $budget = $user->target_budget;
        $salary = $user->monthly_salary;
        $debt = $user->current_debt;
        
        $status = $total > $budget ? "Over Budget" : "Within Budget";
        $savings = $salary - $total;
        
        // Feature 1: Spending Insight Detection
        $highestCategory = $categoryData->sortByDesc('total')->first();
        $insight = $highestCategory ? "You are currently bleeding the most capital on " . $highestCategory->category . " ($" . number_format($highestCategory->total, 2) . ")." : "No analytical spending patterns detected.";

        // Feature 2: Monthly Comparison System
        $dateObj = \Carbon\Carbon::createFromDate($year, $month, 1);
        $lastMonthDate = $dateObj->copy()->subMonth();
        $lastMonthTotal = $user->expenses()
            ->whereMonth('created_at', $lastMonthDate->month)
            ->whereYear('created_at', $lastMonthDate->year)
            ->sum('amount');
        
        $change = $total - $lastMonthTotal;
        if ($change > 0) {
            $comparison = "Your spending increased by $" . number_format($change, 2) . " compared to last month.";
            $comparisonType = 'danger';
        } elseif ($change < 0) {
            $comparison = "You reduced spending by $" . number_format(abs($change), 2) . " — good job!";
            $comparisonType = 'success';
        } else {
            $comparison = "Your spending is exactly identical to last month.";
            $comparisonType = 'info';
        }

        // Feature 3: Savings Goal Tracker
        $progress = $budget > 0 ? max(0, min(100, ($savings / $budget) * 100)) : 0;

        // Feature 4: Top 3 Expenses Highlights
        $topExpenses = $user->expenses()
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('amount', 'desc')
            ->take(3)
            ->get();
        
        if ($salary <= 0 && $budget <= 0) {
            $suggestion = "Set up your Financial Profile settings first.";
        } elseif ($debt > 0) {
            $suggestion = "Clear debt first";
        } elseif ($savings < 1000) {
            $suggestion = "Start saving";
        } elseif ($savings < 5000) {
            $suggestion = "Low-risk investment";
        } else {
            $suggestion = "Diversified investments";
        }
        
        return view('expenses.index', compact('expenses', 'total', 'budget', 'status', 'savings', 'suggestion', 'categoryData', 'month', 'year', 'insight', 'comparison', 'comparisonType', 'progress', 'topExpenses'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required|string',
        ]);

        $data = $request->all();
        $data['is_recurring'] = $request->has('is_recurring');

        auth()->user()->expenses()->create($data);

        return redirect()->route('expenses.index')->with('success', 'Expense securely logged!');
    }

    public function show(Expense $expense)
    {
        if($expense->user_id !== auth()->id()) abort(403);
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        if($expense->user_id !== auth()->id()) abort(403);
        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        if($expense->user_id !== auth()->id()) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required|string',
        ]);

        $data = $request->all();
        $data['is_recurring'] = $request->has('is_recurring');

        $expense->update($data);

        return redirect()->route('expenses.index')->with('success', 'Record updated permanently!');
    }

    public function destroy(Expense $expense)
    {
        if($expense->user_id !== auth()->id()) abort(403);
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense removed forever.');
    }

    public function export(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        
        $expenses = auth()->user()->expenses()
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->latest()
            ->get();

        $filename = "financial_report_{$year}_{$month}.csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Date', 'Title', 'Category', 'Amount', 'Recurring'];

        $callback = function() use($expenses, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($expenses as $expense) {
                fputcsv($file, [
                    $expense->created_at->format('Y-m-d H:i:s'),
                    $expense->title,
                    $expense->category,
                    $expense->amount,
                    $expense->is_recurring ? 'Yes' : 'No'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
