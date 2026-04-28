@extends('layouts.app')

@section('content')

<!-- Nav Tabs Setup -->
<div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-4">
    <ul class="nav custom-nav-tabs" id="financeTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fs-5" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                <i class="bi bi-pie-chart-fill me-2"></i>Overview
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fs-5" id="expenses-tab" data-bs-toggle="tab" data-bs-target="#expenses" type="button" role="tab">
                <i class="bi bi-receipt-cutoff me-2"></i>All Transactions
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fs-5" id="insights-tab" data-bs-toggle="tab" data-bs-target="#insights" type="button" role="tab">
                <i class="bi bi-moon-stars-fill text-warning me-2"></i>Deep Insights
            </button>
        </li>
    </ul>
    <div class="d-flex flex-wrap gap-3 align-items-center">
        <form action="{{ route('expenses.index') }}" method="GET" class="d-flex gap-2 mb-0">
            <select name="month" class="form-select rounded-pill border-0 shadow-sm" style="width: 140px; cursor: pointer;">
                @foreach(range(1, 12) as $m)
                    <option value="{{ sprintf('%02d', $m) }}" {{ ($month ?? date('m')) == sprintf('%02d', $m) ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                    </option>
                @endforeach
            </select>
            <select name="year" class="form-select rounded-pill border-0 shadow-sm" style="width: 110px; cursor: pointer;">
                @foreach(range(date('Y')-2, date('Y')) as $y)
                    <option value="{{ $y }}" {{ ($year ?? date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-white bg-white rounded-pill shadow-sm text-primary fw-bold px-4 hover-elevate">Filter</button>
        </form>
    
        <a href="{{ route('expenses.export', ['month' => $month ?? date('m'), 'year' => $year ?? date('Y')]) }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm fw-bold hover-elevate">
            <i class="bi bi-cloud-arrow-down-fill me-2 fs-5"></i>CSV
        </a>
        <a href="{{ route('expenses.create') }}" class="btn btn-gradient rounded-pill px-4 shadow-sm fw-bold hover-elevate">
            <i class="bi bi-plus-circle-fill me-2 fs-5"></i>Log Expense
        </a>
    </div>
</div>

<div class="tab-content mt-2" id="financeTabsContent">

    <!-- OVERVIEW TAB -->
    <div class="tab-pane fade show active" id="overview" role="tabpanel">
        
        <div class="row g-4 mb-5">
            <!-- Left Dashboard Core Metrics -->
            <div class="col-lg-7 col-xl-8 d-flex flex-column gap-4">
                
                <!-- Summary Cards 2x2 Layout -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="glass-card hover-elevate h-100 p-4 border-0">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <p class="text-uppercase tracking-wider fw-bold text-muted mb-1 fs-6">Total Spent</p>
                                    <h2 class="fw-bold text-dark mb-0">₹{{ number_format($total ?? 0, 2) }}</h2>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                                    <i class="bi bi-credit-card-fill fs-4"></i>
                                </div>
                            </div>
                            <div class="mt-4 pt-2 border-top border-light-subtle">
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-bold tracking-wide">Live Tracked</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="glass-card hover-elevate h-100 p-4 border-0">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <p class="text-uppercase tracking-wider fw-bold text-muted mb-1 fs-6">Budget Goal</p>
                                    <h2 class="fw-bold text-dark mb-0">₹{{ number_format($budget ?? 5000, 2) }}</h2>
                                </div>
                                <div class="bg-secondary bg-opacity-10 p-3 rounded-circle text-secondary">
                                    <i class="bi bi-bullseye fs-4"></i>
                                </div>
                            </div>
                            <div class="mt-4 pt-2 border-top border-light-subtle">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 fw-bold tracking-wide">Target Locked</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="glass-card hover-elevate h-100 p-4 border-0">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <p class="text-uppercase tracking-wider fw-bold text-muted mb-1 fs-6">Remaining</p>
                                    <h2 class="fw-bold mb-0 {{ ($savings ?? 0) > 0 ? 'text-success' : 'text-danger' }}">
                                        ₹{{ number_format($savings ?? 0, 2) }}
                                    </h2>
                                </div>
                                <div class="p-3 rounded-circle {{ ($savings ?? 0) > 0 ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' }}">
                                    <i class="bi bi-piggy-bank-fill fs-4"></i>
                                </div>
                            </div>
                            <div class="mt-4 pt-2 border-top border-light-subtle">
                                @if(($savings ?? 0) > 0)
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-bold tracking-wide">Healthy Buffer</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-bold tracking-wide">Deficit Warning</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="glass-card hover-elevate metric-card h-100 p-4 border-0 {{ ($status ?? '') == 'Over Budget' ? 'bg-danger' : 'bg-success' }}">
                            <div class="d-flex justify-content-between align-items-start position-relative z-1 mb-3">
                                <div>
                                    <p class="text-uppercase tracking-wider fw-bold text-white-50 mb-1 fs-6">Status</p>
                                    <h2 class="fw-bold text-white mb-0">{{ $status ?? 'N/A' }}</h2>
                                </div>
                                <div class="text-white opacity-75">
                                    <i class="bi {{ ($status ?? '') == 'Over Budget' ? 'bi-exclamation-triangle-fill' : 'bi-shield-check-fill' }} display-6"></i>
                                </div>
                            </div>
                            <div class="mt-4 pt-2 border-top border-light border-opacity-25 position-relative z-1">
                                <span class="badge bg-black bg-opacity-25 text-white rounded-pill px-3 py-2 fw-bold tracking-wide">System Analysis</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Internal Analytics elements securely ported to the Deep Insights Tab grid -->

                @if(!empty($suggestion))
                <!-- Smart AI Banner dynamically sized -->
                <div class="glass-card border-0 overflow-hidden position-relative p-0 hover-elevate mt-auto">
                    <div class="row g-0 flex-nowrap">
                        <div class="col-auto p-4 d-flex align-items-center justify-content-center text-white" style="width: 100px; background: linear-gradient(135deg, #4f46e5, #3b82f6);">
                            <i class="bi bi-robot display-5"></i>
                        </div>
                        <div class="col p-4 d-flex flex-column justify-content-center">
                            <h5 class="fw-bold mb-1" style="color: #4f46e5;">Smart AI Analyst</h5>
                            <p class="mb-0 fs-6 text-dark fw-medium lh-base">{{ $suggestion }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Highlights array ported safely to Deep Insights Tab grid element -->
            </div>

            <!-- Right Fixed Analytics Panel -->
            <div class="col-lg-5 col-xl-4 d-flex">
                <div class="glass-card flex-grow-1 hover-elevate border-0 p-4 d-flex flex-column">
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-light-subtle gap-3">
                        <div class="p-2 rounded-4 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-pie-chart-fill fs-4"></i>
                        </div>
                        <h4 class="fw-bold mb-0 text-dark">Category Distribution</h4>
                    </div>
                    
                    <div class="flex-grow-1 position-relative d-flex justify-content-center align-items-center" style="min-height: 320px;">
                        @if(count($categoryData) > 0)
                            <canvas id="expenseChart" class="w-100"></canvas>
                        @else
                            <div class="text-center text-muted">
                                <i class="bi bi-pie-chart opacity-25" style="font-size: 6rem;"></i>
                                <h5 class="fw-bold mt-3">No Analytical Data</h5>
                                <p class="small mb-0">No expenses recorded for this specific month.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ALL EXPENSES TAB -->
    <div class="tab-pane fade" id="expenses" role="tabpanel">
        
        <div class="py-3 mb-4 ps-2">
            <h3 class="fw-bold text-dark d-flex align-items-center mb-2">
                <i class="bi bi-card-list me-3 text-primary fs-2"></i> 
                Transaction History
            </h3>
            <p class="text-muted fs-5 ms-5 ps-2">Manage, oversee, and fine-tune your previously logged expenditures directly.</p>
        </div>

        @if($expenses->count() > 0)
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                @foreach($expenses as $expense)
                    <div class="col">
                        <div class="glass-card hover-elevate h-100 border-0 p-4">
                            @php
                                $badgeClass = 'bg-primary text-primary';
                                $iconClass = 'bi-tags-fill';
                                
                                if($expense->category == 'Food') { $badgeClass = 'bg-info text-dark fw-bold border-info'; $iconClass = 'bi-cup-hot-fill'; }
                                elseif($expense->category == 'Travel') { $badgeClass = 'bg-success text-success border-success'; $iconClass = 'bi-car-front-fill'; }
                                elseif($expense->category == 'Bills') { $badgeClass = 'bg-danger text-danger border-danger'; $iconClass = 'bi-lightning-charge-fill'; }
                                elseif($expense->category == 'Shopping') { $badgeClass = 'bg-warning text-dark fw-bold border-warning'; $iconClass = 'bi-bag-fill'; }
                            @endphp
                            
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <span class="badge {{ $badgeClass }} bg-opacity-10 px-3 py-2 rounded-pill fs-6 border border-opacity-25 shadow-sm">
                                    <i class="bi {{ $iconClass }} me-2"></i>
                                    {{ $expense->category }}
                                    @if($expense->is_recurring)
                                        <i class="bi bi-arrow-repeat ms-1" title="Monthly Recurring"></i>
                                    @endif
                                </span>
                                <h3 class="fw-bold text-dark mb-0">₹{{ number_format($expense->amount, 2) }}</h3>
                            </div>
                            
                            <h4 class="card-title fw-bold text-dark mb-3 mt-4">{{ $expense->title }}</h4>
                            <p class="text-muted small fw-medium mb-4 d-flex align-items-center">
                                <i class="bi bi-clock-history me-2 fs-5"></i> {{ $expense->created_at->format('M d, Y') }} &nbsp;&bull;&nbsp; {{ $expense->created_at->format('h:i A') }}
                            </p>
                            
                            <div class="d-flex gap-2 mt-auto pt-4 border-top border-light-subtle">
                                <a href="{{ route('expenses.show', $expense->id) }}" class="btn btn-light rounded-pill flex-grow-1 fw-bold text-primary shadow-sm hover-elevate">View</a>
                                <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-outline-secondary rounded-pill flex-grow-1 fw-bold shadow-sm">Edit</a>
                                
                                <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Erase this financial record securely?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger rounded-circle px-3 shadow-sm hover-elevate"><i class="bi bi-trash3-fill"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="glass-card border-0 mt-4 text-center p-5 py-5 rounded-4 hover-elevate">
                <div class="bg-primary bg-opacity-10 d-inline-block rounded-circle p-4 mb-4 text-primary">
                    <i class="bi bi-inbox-fill display-2"></i>
                </div>
                <h3 class="fw-bold text-dark">No Expenses Tracked</h3>
                <p class="text-muted fs-5 mb-4 px-lg-5 mx-lg-5">You currently have a clean financial slate! Log your first transaction to populate your smart dashboard distribution parameters.</p>
                <a href="{{ route('expenses.create') }}" class="btn btn-gradient btn-lg rounded-pill shadow-sm px-5 fw-bold">
                    <i class="bi bi-plus-lg me-2"></i>Instantiate New Record
                </a>
            </div>
        @endif
    </div>

    <!-- DEEP INSIGHTS TAB -->
    <div class="tab-pane fade" id="insights" role="tabpanel">
        <div class="py-3 mb-4 ps-2 border-bottom border-light-subtle pb-4">
            <h3 class="fw-bold text-dark d-flex align-items-center mb-2">
                <i class="bi bi-moon-stars-fill text-warning me-3 fs-2"></i> 
                Deep Analytics & Insights
            </h3>
            <p class="text-muted fs-5 ms-5 ps-3 mb-0">Advanced algorithmic parameters projecting isolated financial tracking safely.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-7 d-flex flex-column gap-4">
                <div class="glass-card border-0 p-4 hover-elevate">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-dark fs-5"><i class="bi bi-graph-up-arrow text-primary me-2"></i>Target Savings Progress</span>
                        <span class="fw-bold text-{{ $progress >= 100 ? 'success' : 'primary' }} fs-5">{{ number_format($progress, 1) }}%</span>
                    </div>
                    <div class="progress rounded-pill bg-light" style="height: 12px; border: 1px solid rgba(0,0,0,0.05);">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ $progress >= 100 ? 'success' : 'primary' }}" role="progressbar" style="width: {{ $progress }}%"></div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="glass-card border-0 p-4 h-100 hover-elevate border-start border-4 border-warning">
                            <h6 class="text-uppercase tracking-wider fw-bold text-muted mb-2"><i class="bi bi-lightbulb-fill text-warning me-2"></i>Category Insight</h6>
                            <p class="mb-0 fw-medium text-dark">{{ $insight }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="glass-card border-0 p-4 h-100 hover-elevate border-start border-4 border-{{ $comparisonType }}">
                            <h6 class="text-uppercase tracking-wider fw-bold text-muted mb-2"><i class="bi bi-calendar-range-fill text-{{ $comparisonType }} me-2"></i>Historical Delta</h6>
                            <p class="mb-0 fw-medium text-dark">{{ $comparison }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                @if($topExpenses->count() > 0)
                <div class="glass-card border-0 overflow-hidden hover-elevate h-100">
                    <div class="p-4 bg-light bg-opacity-50 border-bottom border-light-subtle d-flex align-items-center">
                        <i class="bi bi-trophy-fill text-danger me-2 fs-5"></i>
                        <h5 class="mb-0 fw-bold text-dark tracking-tight">Highest Impact Hits</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($topExpenses as $top)
                        <div class="list-group-item bg-transparent py-4 border-light-subtle px-4">
                            <div class="d-flex justify-content-between align-items-center w-100 mb-1">
                                <h5 class="mb-0 fw-bold text-dark fs-5">{{ $top->title }}</h5>
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-bold fs-6 shadow-sm border border-danger border-opacity-10">₹{{ number_format($top->amount, 2) }}</span>
                            </div>
                            <small class="text-muted fw-bold d-inline-flex align-items-center"><i class="bi bi-tags-fill me-1 text-primary"></i> <span class="bg-primary bg-opacity-10 px-2 py-1 rounded text-primary">{{ $top->category }}</span></small>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="glass-card border-0 p-4 h-100 d-flex flex-column align-items-center justify-content-center text-center">
                    <i class="bi bi-shield-check text-muted opacity-25" style="font-size: 5rem;"></i>
                    <h5 class="fw-bold mt-3 text-dark">No Data Parsed</h5>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartElement = document.getElementById('expenseChart');
        if (!chartElement) return; // Halt execution if no data is present

        const ctx = chartElement.getContext('2d');
        const categoryData = @json($categoryData);
        
        const labels = categoryData.map(item => item.category);
        const data = categoryData.map(item => item.total);

        // Bold ultra-modern generic palette using hex 
        const backgroundColors = [
            '#4f46e5', // Indigo
            '#10b981', // Emerald
            '#f43f5e', // Rose
            '#f59e0b', // Amber
            '#8b5cf6'  // Violet
        ];

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Spent Amount',
                    data: data,
                    backgroundColor: backgroundColors,
                    borderWidth: 4,
                    borderColor: '#ffffff',
                    hoverOffset: 12
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: 20
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                size: 14,
                                family: "'Inter', sans-serif",
                                weight: '600'
                            },
                            color: '#334155'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 15,
                        titleFont: { size: 14, family: "'Inter', sans-serif" },
                        bodyFont: { size: 16, weight: 'bold', family: "'Outfit', sans-serif" },
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(context.parsed);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
