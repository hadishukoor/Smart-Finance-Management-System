<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'monthly_salary' => 'required|numeric|min:0',
            'current_debt' => 'required|numeric|min:0',
            'target_budget' => 'required|numeric|min:0',
        ]);

        auth()->user()->update($request->only(['monthly_salary', 'current_debt', 'target_budget']));

        return redirect()->back()->with('success', 'Your global financial profile was updated securely.');
    }
}
