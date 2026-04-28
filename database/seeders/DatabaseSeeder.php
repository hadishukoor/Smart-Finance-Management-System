<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'testing@gmail.com'],
            [
                'name' => 'Sample User',
                'password' => Hash::make('testing123'),
                'monthly_salary' => 6500.00,
                'target_budget' => 4000.00,
                'current_debt' => 0.00
            ]
        );

        $expenses = [
            ['title' => 'Starbucks Coffee', 'amount' => 5.50, 'category' => 'Food'],
            ['title' => 'Monthly Rent', 'amount' => 1200.00, 'category' => 'Bills'],
            ['title' => 'Uber to Airport', 'amount' => 45.00, 'category' => 'Travel'],
            ['title' => 'Groceries from Walmart', 'amount' => 150.00, 'category' => 'Food'],
            ['title' => 'Netflix Subscription', 'amount' => 15.99, 'category' => 'Bills'],
            ['title' => 'New Headphones', 'amount' => 120.00, 'category' => 'Shopping'],
            ['title' => 'Sushi Dinner', 'amount' => 65.00, 'category' => 'Food'],
            ['title' => 'Gas Station', 'amount' => 40.00, 'category' => 'Travel'],
            ['title' => 'Electric Bill', 'amount' => 85.00, 'category' => 'Bills'],
            ['title' => 'Gym Membership', 'amount' => 30.00, 'category' => 'Bills'],
            ['title' => 'Amazon Purchase', 'amount' => 55.00, 'category' => 'Shopping'],
            ['title' => 'Weekend Train Ticket', 'amount' => 25.00, 'category' => 'Travel'],
            ['title' => 'Fast Food Lunch', 'amount' => 12.50, 'category' => 'Food'],
            ['title' => 'Zara Clothing', 'amount' => 180.00, 'category' => 'Shopping'],
            ['title' => 'Internet Bill', 'amount' => 60.00, 'category' => 'Bills'],
            ['title' => 'Taxi Ride', 'amount' => 18.50, 'category' => 'Travel'],
            ['title' => 'Restaurant Bill', 'amount' => 88.00, 'category' => 'Food'],
            ['title' => 'Movie Tickets', 'amount' => 32.00, 'category' => 'Shopping']
        ];

        foreach($expenses as $data) {
            $user->expenses()->create($data);
        }

        // Seed Holdings for SUZLON and REDINGTON securely using live fetched parameters
        $suzlon = \App\Services\YahooFinanceService::getLivePrice('SUZLON');
        if($suzlon['valid']) {
            $user->holdings()->create([
                'stock_name' => str_replace('.NS', '', $suzlon['ticker']),
                'quantity' => 2000,
                'buy_price' => 38.50,
                'buy_date' => now()->subMonths(3),
                'current_price' => $suzlon['price'],
                'previous_close' => $suzlon['previousClose']
            ]);
        }

        $redington = \App\Services\YahooFinanceService::getLivePrice('REDINGTON');
        if($redington['valid']) {
            $user->holdings()->create([
                'stock_name' => str_replace('.NS', '', $redington['ticker']),
                'quantity' => 150,
                'buy_price' => 195.20,
                'buy_date' => now()->subWeeks(5),
                'current_price' => $redington['price'],
                'previous_close' => $redington['previousClose']
            ]);
        }
    }
}
