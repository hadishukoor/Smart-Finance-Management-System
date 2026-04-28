@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-lg border-0 rounded-4 p-5">
            <h3 class="mb-4 fw-bold text-dark text-center">Edit Transaction</h3>
            
            <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">Transaction Title</label>
                    <input type="text" name="title" class="form-control form-control-lg rounded-3 @error('title') is-invalid @enderror" value="{{ old('title', $expense->title) }}" required>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Transfer Amount (₹)</label>
                    <input type="number" step="0.01" name="amount" class="form-control form-control-lg rounded-3 @error('amount') is-invalid @enderror" value="{{ old('amount', $expense->amount) }}" required>
                    @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Category</label>
                    <select name="category" class="form-select form-select-lg rounded-3 @error('category') is-invalid @enderror" required>
                        <option value="" disabled>Select Classification</option>
                        <option value="Food" {{ old('category', $expense->category) == 'Food' ? 'selected' : '' }}>Food / Dining</option>
                        <option value="Travel" {{ old('category', $expense->category) == 'Travel' ? 'selected' : '' }}>Travel / Transport</option>
                        <option value="Bills" {{ old('category', $expense->category) == 'Bills' ? 'selected' : '' }}>Monthly Bills</option>
                        <option value="Shopping" {{ old('category', $expense->category) == 'Shopping' ? 'selected' : '' }}>Retail Shopping</option>
                    </select>
                    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4 d-flex align-items-center bg-light p-3 rounded-4 border border-light-subtle shadow-sm">
                    <div class="form-check form-switch ps-5 mb-0">
                        <input class="form-check-input mt-1 custom-switch" type="checkbox" name="is_recurring" id="recurringSwitch" style="transform: scale(1.4); cursor: pointer;" {{ old('is_recurring', $expense->is_recurring) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold text-primary fs-6 ms-3" style="cursor: pointer;" for="recurringSwitch">Set as Monthly Recurring Auto-Log</label>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('expenses.index') }}" class="btn btn-light btn-lg rounded-pill shadow-sm fw-bold px-4">Cancel</a>
                    <button type="submit" class="btn btn-warning btn-lg text-dark rounded-pill shadow-sm fw-bold px-4">Update Expense</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
