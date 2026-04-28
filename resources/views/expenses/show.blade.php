@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <a href="{{ route('expenses.index') }}" class="btn btn-light shadow-sm mb-4 border rounded-pill px-4 fw-bold">&larr; Back to Dashboard</a>

        <div class="card shadow-lg border-0 p-5 rounded-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0 fw-bold text-dark">{{ $expense->title }}</h2>
                <span class="badge bg-secondary fs-5 px-3 py-2 rounded-pill shadow-sm">{{ $expense->category }}</span>
            </div>
            
            <div class="row mb-5 text-center mt-3 border bg-light py-4 rounded-4">
                <div class="col-12">
                    <h6 class="text-uppercase text-muted fw-bold">Deduction Amount</h6>
                    <h2 class="fw-bold text-danger mb-0">-₹{{ number_format($expense->amount, 2) }}</h2>
                </div>
            </div>
            
            <hr>
            
            <div class="d-flex gap-3 justify-content-end mt-4">
                <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-warning btn-lg rounded-pill shadow-sm fw-bold px-5 text-dark">Edit Record</a>
                <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('Delete this record definitively?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-lg rounded-pill shadow-sm fw-bold px-5">Erase</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
