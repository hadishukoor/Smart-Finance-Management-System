<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvestmentController;

Route::get('/', function () {
    return redirect('/expenses');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/expenses/export', [ExpenseController::class, 'export'])->name('expenses.export');
    Route::resource('expenses', ExpenseController::class);
    
    // External Integrations & Investments
    Route::get('/investments', [InvestmentController::class, 'index'])->name('investments.index');
    Route::post('/investments', [InvestmentController::class, 'store'])->name('investments.store');
    Route::put('/investments/{holding}', [InvestmentController::class, 'update'])->name('investments.update');
    Route::delete('/investments/{holding}', [InvestmentController::class, 'destroy'])->name('investments.destroy');
    Route::post('/investments/sync', [InvestmentController::class, 'syncAll'])->name('investments.sync');
    Route::get('/api/live-price', function(Illuminate\Http\Request $request) {
        return response()->json(\App\Services\YahooFinanceService::getLivePrice($request->query('ticker')));
    })->name('investments.live_price');
    
    // Bucket-List 50/30/20 Engine
    Route::get('/goals', [App\Http\Controllers\GoalController::class, 'index'])->name('goals.index');
    Route::post('/goals', [App\Http\Controllers\GoalController::class, 'store'])->name('goals.store');
    Route::put('/goals/{goal}', [App\Http\Controllers\GoalController::class, 'update'])->name('goals.update');
    Route::delete('/goals/{goal}', [App\Http\Controllers\GoalController::class, 'destroy'])->name('goals.destroy');
    
    // Auth bound profile management
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});
