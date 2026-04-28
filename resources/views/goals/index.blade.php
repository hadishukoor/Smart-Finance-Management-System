@extends('layouts.app')

@section('content')

<div class="row mb-5 align-items-center">
    <div class="col-md-8">
        <h2 class="fw-bold text-dark d-flex align-items-center mb-1" style="font-size: 2.2rem;">
            Bucket List & Financial Matrix
        </h2>
        <p class="text-muted fs-6 mb-0 d-flex align-items-center mt-2">
            <i class="bi bi-robot text-primary me-2 fs-5"></i> Proprietary 50/30/20 algorithm actively tracking and scaling your dream purchases securely.
        </p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <button class="btn btn-gradient text-white rounded-pill px-5 shadow-sm fw-bold hover-elevate py-2" data-bs-toggle="modal" data-bs-target="#addGoalModal">
            <i class="bi bi-plus-circle-fill me-2 fs-5"></i> Define New Target
        </button>
    </div>
</div>

<!-- 50/30/20 Rules Metrics Banner -->
<div class="glass-card hover-elevate p-4 border-0 shadow-sm mb-5 position-relative overflow-hidden">
    <div class="position-absolute opacity-10" style="right: -20px; top: -30px;">
        <i class="bi bi-bank" style="font-size: 14rem;"></i>
    </div>
    <div class="d-flex align-items-center gap-3 mb-4 position-relative z-1">
        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
            <i class="bi bi-pie-chart-fill fs-3"></i>
        </div>
        <div>
            <h4 class="fw-bold text-dark mb-0 tracking-tight">System 50/30/20 Bounds</h4>
            <p class="text-muted fs-6 mb-0 fw-medium">Mapped natively against your established ₹{{ number_format($salary, 0) }} monthly capital flow.</p>
        </div>
    </div>
    <div class="row g-4 position-relative z-1">
        <div class="col-md-4">
            <div class="p-4 bg-white border border-light-subtle rounded-4 h-100 shadow-sm border-top border-4 border-info">
                <h6 class="fw-bold text-uppercase tracking-wider text-muted mb-1" style="font-size: 0.8rem;">50% Core Needs</h6>
                <h3 class="fw-bold text-info mb-0">₹{{ number_format($needsMax, 0) }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 bg-white border border-light-subtle rounded-4 h-100 shadow-sm border-top border-4 border-warning">
                <h6 class="fw-bold text-uppercase tracking-wider text-muted mb-1" style="font-size: 0.8rem;">30% Discretionary Wants</h6>
                <h3 class="fw-bold text-warning mb-0">₹{{ number_format($wantsMax, 0) }}</h3>
                <small class="text-muted fw-bold" style="font-size: 0.70rem;">BUCKET LIST MAX BUFFER</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 bg-white border border-light-subtle rounded-4 h-100 shadow-sm border-top border-4 border-success">
                <h6 class="fw-bold text-uppercase tracking-wider text-muted mb-1" style="font-size: 0.8rem;">20% Savings Matrix</h6>
                <h3 class="fw-bold text-success mb-0">₹{{ number_format($savingsMax, 0) }}</h3>
            </div>
        </div>
    </div>
</div>

@if($activeGoals->count() > 0)
    <div class="row g-4">
        @foreach($activeGoals as $goal)
            @php
                $percentage = $goal->target_amount > 0 ? ($goal->saved_amount / $goal->target_amount) * 100 : 0;
                $percentageClamped = min(100, $percentage);
                $isFunded = $percentage >= 100;
                $rec = $recommendations[$goal->id];
            @endphp
            <div class="col-xl-6">
                <div class="glass-card hover-elevate border-0 p-4 p-md-5 h-100 d-flex flex-column shadow-sm overflow-hidden position-relative">
                    
                    @if($isFunded)
                        <div class="position-absolute w-100 h-100 top-0 start-0 bg-success bg-opacity-10 z-0 float-glow" style="pointer-events: none;"></div>
                    @endif
                    
                    <div class="d-flex justify-content-between align-items-start mb-4 position-relative z-1">
                        <div>
                            <h3 class="fw-bold text-dark mb-1">{{ $goal->goal_title }}</h3>
                            <h5 class="fw-bold text-muted mb-0">Target: <span class="text-dark">₹{{ number_format($goal->target_amount, 0) }}</span></h5>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-{{ $isFunded ? 'success' : 'primary' }} bg-opacity-10 text-{{ $isFunded ? 'success' : 'primary' }} rounded-pill px-3 py-2 fw-bold shadow-sm d-flex align-items-center">
                                <i class="bi bi-clock-history me-2"></i> {{ $goal->target_date->format('M Y') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-5 mt-2 position-relative z-1">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-dark" style="font-size: 0.9rem;">Progression Vector</span>
                            <span class="fw-bold text-{{ $isFunded ? 'success' : 'primary' }} fs-5">{{ number_format($percentage, 1) }}%</span>
                        </div>
                        <div class="progress rounded-pill bg-light" style="height: 16px; border: 1px solid rgba(0,0,0,0.05);">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ $isFunded ? 'success' : 'primary' }}" role="progressbar" style="width: {{ $percentageClamped }}%"></div>
                        </div>
                    </div>

                    <!-- 50/30/20 Dynamic Recommendation Engine Banner -->
                    <div class="bg-{{ $rec['status'] }} bg-opacity-10 text-{{ $rec['status'] }} p-3 p-md-4 rounded-4 border border-{{ $rec['status'] }} border-opacity-25 shadow-sm mb-4">
                        <div class="d-flex gap-3 align-items-start">
                            <i class="bi bi-{{ $rec['icon'] }} fs-3 mt-1"></i>
                            <div>
                                <h6 class="fw-bold text-uppercase tracking-wider mb-1" style="font-size: 0.8rem;">Algorithmic Logic</h6>
                                <p class="mb-0 fw-medium lh-base text-dark" style="font-size: 0.95rem;">
                                    {{ $rec['message'] }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-auto pt-4 border-top border-light-subtle d-flex justify-content-between align-items-center align-items-md-end flex-wrap gap-3">
                        <form action="{{ route('goals.update', $goal->id) }}" method="POST" class="d-flex align-items-center">
                            @csrf
                            @method('PUT')
                            <div class="input-group flex-nowrap shadow-sm" style="max-width: 200px;">
                                <span class="input-group-text bg-white border-primary border-opacity-25 fw-bold">₹</span>
                                <input type="number" step="0.01" name="saved_amount" class="form-control border-primary border-opacity-25" value="{{ $goal->saved_amount }}" required>
                                <button class="btn btn-primary px-3" type="submit"><i class="bi bi-cloud-arrow-up-fill"></i></button>
                            </div>
                        </form>
                        
                        <form action="{{ route('goals.destroy', $goal->id) }}" method="POST" onsubmit="return confirm('Eradicate this structural target entirely?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger fw-bold rounded-pill px-4 hover-elevate shadow-sm">Drop <i class="bi bi-trash-fill ms-1"></i></button>
                        </form>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="glass-card hover-elevate border-0 p-5 p-md-5 mb-5 text-center shadow-sm">
        <i class="bi bi-bag-heart-fill text-muted opacity-25" style="font-size: 5rem;"></i>
        <h3 class="fw-bold text-dark mt-4">No Active Targets</h3>
        <p class="text-muted lh-base px-lg-5 fs-5">Your active Bucket List is empty. Initialize a dream asset mapping (like a car or gadget) and allow our 50/30/20 algorithm to reverse-engineer an absolute timeline for mathematically secure acquisition.</p>
        <button class="btn btn-gradient btn-lg text-white rounded-pill px-5 shadow-sm fw-bold hover-elevate mt-3 mb-2" data-bs-toggle="modal" data-bs-target="#addGoalModal">
            Create Dream Target
        </button>
    </div>
@endif

@if($achievedGoals->count() > 0)
<div class="mt-5 pt-4 border-top border-light-subtle">
    <h3 class="fw-bold text-dark mb-4"><i class="bi bi-trophy-fill text-warning me-2"></i> Achieved & Acquired ({{ $achievedGoals->count() }})</h3>
    <div class="row g-4 opacity-75">
        @foreach($achievedGoals as $goal)
            <div class="col-xl-4 col-lg-6">
                <div class="glass-card border-0 p-4 h-100 shadow-sm bg-success bg-opacity-10 border border-success border-opacity-25 position-relative">
                    <div class="position-absolute top-0 end-0 p-3">
                        <i class="bi bi-check-circle-fill text-success fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1 pe-4">{{ $goal->goal_title }}</h5>
                    <p class="text-muted mb-3 fw-bold">₹{{ number_format($goal->target_amount, 0) }}</p>
                    <form action="{{ route('goals.destroy', $goal->id) }}" method="POST" onsubmit="return confirm('Remove this achieved record?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3 shadow-sm mt-2">Clear Record</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- Add Goal Bootstrap Modal -->
<div class="modal fade" id="addGoalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-gradient bg-opacity-10 border-0 p-4 pb-3">
                <h4 class="modal-title fw-bold text-dark"><i class="bi bi-bullseye text-primary me-2"></i> Instantiate Dream Target</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('goals.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 pt-3 bg-light bg-opacity-50">
                    <div class="mb-4">
                        <label class="form-label fw-bold fw-bolder text-muted tracking-wider text-uppercase" style="font-size: 0.8rem;">Item Designation</label>
                        <input type="text" name="goal_title" class="form-control form-control-lg rounded-pill px-4 shadow-sm border-0" placeholder="e.g. Range Rover, iPhone 16 Pro" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold fw-bolder text-muted tracking-wider text-uppercase" style="font-size: 0.8rem;">Capital Threshold (₹)</label>
                        <input type="number" step="0.01" name="target_amount" class="form-control form-control-lg rounded-pill px-4 shadow-sm border-0" placeholder="e.g. 150000" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold fw-bolder text-muted tracking-wider text-uppercase" style="font-size: 0.8rem;">Target Deadline</label>
                        <input type="date" name="target_date" class="form-control form-control-lg rounded-pill px-4 shadow-sm border-0" required min="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 bg-white d-flex flex-nowrap gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold flex-grow-1 py-3 border border-light-subtle" data-bs-dismiss="modal">Abort</button>
                    <button type="submit" class="btn btn-gradient rounded-pill px-4 fw-bold shadow-sm flex-grow-1 py-3">Validate Algorithm Constraints</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
