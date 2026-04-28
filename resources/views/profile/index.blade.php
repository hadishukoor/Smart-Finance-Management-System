@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-6">
        <div class="card shadow-lg border-0 rounded-4 p-5 bg-white">
            <h2 class="fw-bold mb-4 text-dark text-center">My Financial Profile</h2>
            <p class="text-muted text-center mb-4 fs-5">Set your baseline metrics here. These naturally configure your entire smart dashboard and auto-suggestive logic!</p>
            
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="form-label fw-bold">Fixed Monthly Salary (₹)</label>
                    <input type="number" step="0.01" name="monthly_salary" class="form-control form-control-lg rounded-3 @error('monthly_salary') is-invalid @enderror" value="{{ old('monthly_salary', $user->monthly_salary) }}" required>
                    @error('monthly_salary') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Target Spending Budget (₹)</label>
                    <input type="number" step="0.01" name="target_budget" class="form-control form-control-lg rounded-3 @error('target_budget') is-invalid @enderror" value="{{ old('target_budget', $user->target_budget) }}" required>
                    @error('target_budget') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Current Logged Debt (₹)</label>
                    <input type="number" step="0.01" name="current_debt" class="form-control form-control-lg rounded-3 @error('current_debt') is-invalid @enderror" value="{{ old('current_debt', $user->current_debt) }}" required>
                    @error('current_debt') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm fw-bold mt-2">Lock In Global Profile</button>
            </form>
        </div>
    </div>
</div>
@endsection
