<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;
use Carbon\Carbon;

class GoalController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $salary = $user->monthly_salary;
        
        // 50/30/20 Structural Algorithm Parameters
        $needsMax = $salary * 0.50;
        $wantsMax = $salary * 0.30;
        $savingsMax = $salary * 0.20;
        
        $goals = $user->goals()->orderBy('target_date', 'asc')->get();
        
        // Array mapping complex algorithmic recommendations dynamically per goal
        $recommendations = [];
        
        foreach($goals as $goal) {
            $remainingAmount = $goal->target_amount - $goal->saved_amount;
            if($remainingAmount <= 0) {
                $recommendations[$goal->id] = [
                    'status' => 'success',
                    'icon' => 'check-circle-fill',
                    'message' => 'Fully Funded! You have successfully achieved this structural goal target!'
                ];
                continue;
            }
            
            $monthsLeft = ceil(now()->floatDiffInMonths($goal->target_date));
            if($monthsLeft <= 0) $monthsLeft = 1; // Prevent division by zero
            
            $monthlyRequirement = $remainingAmount / $monthsLeft;
            
            if($monthlyRequirement > $wantsMax) {
                // Rule Broken
                $recommendedMonths = ceil($remainingAmount / $wantsMax);
                $recommendedDate = now()->addMonths($recommendedMonths)->format('F Y');
                $recommendations[$goal->id] = [
                    'status' => 'danger',
                    'icon' => 'exclamation-triangle-fill',
                    'message' => 'Warning: Saving ₹' . number_format($monthlyRequirement, 0) . ' monthly wildly exceeds your 30% discretionary budget (₹' . number_format($wantsMax, 0) . '). We systematically recommend pushing the deadline back to ' . $recommendedDate . ' to prevent budget collapse.'
                ];
            } elseif ($monthlyRequirement > ($wantsMax * 0.6)) {
                // High Margin Warning
                $recommendations[$goal->id] = [
                    'status' => 'warning',
                    'icon' => 'exclamation-circle-fill',
                    'message' => 'Caution: Saving ₹' . number_format($monthlyRequirement, 0) . ' monthly consumes over 60% of your discretionary budget. You are on track, but monitor secondary wants carefully.'
                ];
            } else {
                // Safe Margin
                $recommendations[$goal->id] = [
                    'status' => 'primary',
                    'icon' => 'shield-check-fill',
                    'message' => 'Perfect Tracking: Allocating ₹' . number_format($monthlyRequirement, 0) . ' securely fits directly inside your 30% financial rule constraints.'
                ];
            }
        }
        $activeGoals = $goals->filter(fn($g) => $g->saved_amount < $g->target_amount);
        $achievedGoals = $goals->filter(fn($g) => $g->saved_amount >= $g->target_amount);
        
        return view('goals.index', compact('activeGoals', 'achievedGoals', 'salary', 'needsMax', 'wantsMax', 'savingsMax', 'recommendations'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'goal_title' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'target_date' => 'required|date|after:today',
        ]);
        
        auth()->user()->goals()->create([
            'goal_title' => $request->goal_title,
            'target_amount' => $request->target_amount,
            'target_date' => $request->target_date,
            'saved_amount' => 0
        ]);
        
        return back()->with('success', 'Dream Bucket-List vector securely instantiated into system trackers!');
    }
    
    public function update(Request $request, Goal $goal)
    {
        if ($goal->user_id !== auth()->id()) {
            abort(403);
        }
        
        $request->validate([
            'saved_amount' => 'required|numeric|min:0'
        ]);
        
        $goal->update([
            'saved_amount' => $request->saved_amount
        ]);
        
        if ($goal->saved_amount >= $goal->target_amount) {
            return back()->with('success', 'Congratulations! You logically fully funded ' . $goal->goal_title . '!');
        }
        
        return back()->with('success', 'Progress mathematically synchronized into matrix arrays.');
    }
    
    public function destroy(Goal $goal)
    {
        if ($goal->user_id !== auth()->id()) {
            abort(403);
        }
        
        $goal->delete();
        
        return back()->with('success', 'Bucket-List goal parameters securely eradicated from system bounds.');
    }
}
