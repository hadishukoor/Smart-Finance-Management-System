@extends('layouts.app')

@section('content')

<!-- Partnership Header -->
<div class="row mb-5 align-items-center">
    <div class="col-md-7">
        <h2 class="fw-bold text-dark d-flex align-items-center mb-1" style="font-size: 2.2rem;">
            Indian Equity Markets
        </h2>
        <p class="text-muted fs-6 mb-0 d-flex align-items-center mt-2">
            <i class="bi bi-shield-lock-fill text-primary me-2 fs-5"></i> Official Portfolio Integration Data powered by <span class="fw-bold text-dark ms-2" style="font-size: 1.1rem; letter-spacing: -0.5px;">Groww<span class="text-primary d-inline">.in</span></span>
        </p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <div class="d-inline-flex align-items-center bg-primary bg-opacity-10 text-primary rounded px-4 py-3 border border-primary border-opacity-25 shadow-sm">
            <i class="bi bi-check-circle-fill me-3 fs-3"></i>
            <div class="text-start">
                <h6 class="mb-0 fw-bold tracking-tight">Verified Groww Partner API</h6>
                <small class="opacity-75 fw-medium">Secured Connection Active</small>
            </div>
        </div>
    </div>
</div>

<!-- Groww Style Live Portfolio Mock -->
<div class="glass-card hover-elevate p-4 p-md-5 mb-5 border-0 shadow-sm" style="background: linear-gradient(to right, #ffffff, #f8fafc);">
    <h5 class="fw-bold text-dark mb-4 tracking-tight d-flex align-items-center">
        <div class="bg-primary bg-opacity-10 p-2 rounded me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="bi bi-pie-chart-fill text-primary fs-5"></i>
        </div>
        Your Live Portfolio Dashboard
    </h5>
    
    <div class="row g-4 mt-2">
        <div class="col-md-4">
            <div class="p-4 border border-light-subtle rounded-4 bg-white shadow-sm h-100">
                <div class="text-muted small fw-bold text-uppercase mb-2 tracking-wider d-flex justify-content-between">
                    Total Investment <i class="bi bi-wallet2 opacity-50"></i>
                </div>
                <h3 class="fw-bold text-dark mb-0">₹{{ number_format($totalInvestment, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 border border-light-subtle rounded-4 bg-white shadow-sm h-100 position-relative overflow-hidden">
                <div class="text-muted small fw-bold text-uppercase mb-2 tracking-wider d-flex justify-content-between">
                    Current Value <i class="bi bi-cash-stack opacity-50"></i>
                </div>
                <h3 class="fw-bold text-dark mb-0 d-flex flex-column gap-1">
                    ₹{{ number_format($currentValue, 2) }}
                    <span class="{{ $returnPercentage >= 0 ? 'text-success' : 'text-danger' }} fw-bold mt-1" style="font-size: 0.95rem;">
                        <i class="bi {{ $returnPercentage >= 0 ? 'bi-caret-up-fill' : 'bi-caret-down-fill' }} me-1"></i>{{ number_format(abs($returnPercentage), 2) }}% All Time
                    </span>
                </h3>
                <div class="position-absolute {{ $returnPercentage >= 0 ? 'text-success' : 'text-danger' }} opacity-10" style="bottom: -20px; right: -10px;">
                    <i class="bi {{ $returnPercentage >= 0 ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow' }}" style="font-size: 6rem;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 border border-primary border-opacity-50 rounded-4 bg-primary bg-opacity-10 shadow-sm h-100">
                <div class="text-dark opacity-75 small fw-bold text-uppercase mb-2 tracking-wider d-flex justify-content-between">
                    1D Return (Live) <i class="bi bi-clock-history opacity-50"></i>
                </div>
                <h3 class="fw-bold {{ $dayReturn >= 0 ? 'text-primary' : 'text-danger' }} mb-0 d-flex flex-column gap-1">
                    {{ $dayReturn >= 0 ? '+' : '-' }}₹{{ number_format(abs($dayReturn), 2) }}
                    <span class="text-primary fw-bold mt-1" style="font-size: 0.95rem;"><i class="bi bi-activity me-1"></i>Realtime Trace</span>
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4 mt-5 flex-wrap gap-3">
    <h4 class="fw-bold text-dark mb-0 d-flex align-items-center"><i class="bi bi-briefcase-fill text-primary me-2"></i> Your Active Holdings</h4>
    <div class="d-flex gap-2">
        <form action="{{ route('investments.sync') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-light border-primary border-opacity-25 text-primary rounded-pill px-4 py-2 shadow-sm fw-bold hover-elevate d-flex align-items-center" onclick="this.innerHTML='<i class=\'bi bi-arrow-repeat me-2 fs-5\'></i> Syncing Markets...'">
                <i class="bi bi-cloud-arrow-down-fill me-2 fs-5"></i> Fetch Live Prices
            </button>
        </form>
        <button class="btn btn-gradient text-white rounded-pill px-4 py-2 shadow-sm fw-bold hover-elevate d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addHoldingModal">
            <i class="bi bi-plus-circle-fill me-2 fs-5"></i> Track New Asset
        </button>
    </div>
</div>

<!-- Active Holdings Database Loop -->
@if($holdings->count() > 0)
    <div class="glass-card hover-elevate p-0 border-0 overflow-hidden mb-5 shadow-sm">
        <div class="table-responsive">
            <table class="table table-borderless table-hover mb-0 align-middle">
                <thead class="bg-primary bg-opacity-10 text-primary">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase tracking-wider fw-bold" style="font-size: 0.8rem;">Stock / Asset</th>
                        <th class="py-3 text-uppercase tracking-wider fw-bold" style="font-size: 0.8rem;">Qty & Date</th>
                        <th class="py-3 text-uppercase tracking-wider fw-bold" style="font-size: 0.8rem;">Buy Price</th>
                        <th class="py-3 text-uppercase tracking-wider fw-bold" style="font-size: 0.8rem;">Current Price</th>
                        <th class="py-3 text-uppercase tracking-wider fw-bold" style="font-size: 0.8rem;">Total P&L</th>
                        <th class="pe-4 py-3 text-end text-uppercase tracking-wider fw-bold" style="font-size: 0.8rem;">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($holdings as $holding)
                        @php
                            $invested = $holding->quantity * $holding->buy_price;
                            $current = $holding->quantity * $holding->current_price;
                            $pl = $current - $invested;
                            $plPercent = $invested > 0 ? ($pl / $invested) * 100 : 0;
                            $plClass = $pl >= 0 ? 'success' : 'danger';
                        @endphp
                        <tr class="border-bottom border-light-subtle">
                            <td class="ps-4 py-3">
                                <h5 class="fw-bold text-dark mb-0">{{ $holding->stock_name }}</h5>
                                <span class="badge bg-primary bg-opacity-10 text-primary mt-1 px-2 py-1"><i class="bi bi-shield-check me-1"></i>Tracked</span>
                            </td>
                            <td class="py-3">
                                <div class="fw-bold text-dark">{{ number_format($holding->quantity, 2) }} units</div>
                                <small class="text-muted fw-medium">{{ $holding->buy_date->format('M d, Y') }}</small>
                            </td>
                            <td class="py-3 fw-bold text-muted">₹{{ number_format($holding->buy_price, 2) }}</td>
                            <td class="py-3 fw-bold">
                                ₹{{ number_format($holding->current_price, 2) }}
                            </td>
                            <td class="py-3">
                                <span class="fw-bold text-{{ $plClass }} d-block fs-5">₹{{ number_format($pl, 2) }}</span>
                                <span class="badge bg-{{ $plClass }} bg-opacity-10 text-{{ $plClass }} mt-1 px-2 py-1 border border-{{ $plClass }} border-opacity-25 shadow-sm"><i class="bi bi-caret-{{ $pl >= 0 ? 'up' : 'down' }}-fill"></i> {{ number_format(abs($plPercent), 2) }}%</span>
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <form action="{{ route('investments.destroy', $holding->id) }}" method="POST" onsubmit="return confirm('Sell this holding from portfolio securely?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger shadow-sm fw-bold hover-elevate px-3">Liquidate <i class="bi bi-box-arrow-right ms-1"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="glass-card hover-elevate border-0 p-5 mb-5 text-center shadow-sm">
        <i class="bi bi-briefcase text-muted opacity-25" style="font-size: 4.5rem;"></i>
        <h3 class="fw-bold text-dark mt-4">Portfolio Empty</h3>
        <p class="text-muted lh-base px-lg-5 fs-5">You currently possess zero active assets initialized in your tracking matrix. Log your first investment parameters to activate mathematical growth engines securely.</p>
        <button class="btn btn-gradient btn-lg text-white rounded-pill px-5 shadow-sm fw-bold hover-elevate mt-3 mb-2" data-bs-toggle="modal" data-bs-target="#addHoldingModal">
            Initialize First Vector
        </button>
    </div>
@endif

<!-- Recommendation Banner -->
<div class="glass-card hover-elevate overflow-hidden mb-5 shadow-sm border-start border-5 border-primary">
    <div class="row g-0 flex-nowrap align-items-stretch">
        <div class="col-auto p-4 d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary px-md-5">
            <i class="bi bi-robot" style="font-size: 3.5rem;"></i>
        </div>
        <div class="col p-4 p-md-5 bg-white d-flex flex-column justify-content-center">
            <h6 class="fw-bold mb-2 text-primary tracking-wide text-uppercase" style="font-size: 0.85rem;"><i class="bi bi-lightning-fill me-1"></i>Smart Analytical Recommendation</h6>
            <div class="d-flex align-items-center gap-3 mb-3">
                <span class="fs-4 text-dark fw-bold">Active Buying Power: <span class="text-primary bg-primary bg-opacity-10 px-3 py-1 rounded ms-2">₹{{ number_format($savings, 2) }}</span></span>
            </div>
            <p class="mb-0 fs-5 text-secondary fw-medium lh-base" style="max-width: 800px;">{{ $recommendationText }}</p>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4 mt-5">
    <h4 class="fw-bold text-dark mb-0 d-flex align-items-center"><i class="bi bi-fire text-danger me-2"></i> Targeted Trending Vectors</h4>
    <a href="https://groww.in" target="_blank" class="text-decoration-none bg-light px-4 py-2 rounded-pill text-primary fw-bold hover-elevate border border-light-subtle">Explore All <i class="bi bi-arrow-right ms-2"></i></a>
</div>

<div class="row g-4 mb-5">
    @foreach($stocks as $stock)
    <div class="col-lg-6">
        <div class="glass-card hover-elevate h-100 p-4 p-md-5 d-flex flex-column border-0">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 fw-bold {{ $stock['badge_color'] == 'success' ? 'bg-success bg-opacity-10 text-success border border-success border-opacity-25' : 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25' }}" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        {{ substr($stock['name'], 0, 1) }}
                    </div>
                    <div>
                        <h4 class="fw-bold text-dark mb-1" style="font-size: 1.5rem;">{{ $stock['name'] }}</h4>
                        <p class="text-muted fw-bold tracking-tight mb-0 text-uppercase" style="font-size: 0.80rem;">{{ $stock['full_name'] }}</p>
                    </div>
                </div>
                <span class="badge bg-{{ $stock['badge_color'] }} bg-opacity-10 text-{{ $stock['badge_color'] }} rounded px-3 py-2 fw-bold text-uppercase tracking-wider" style="font-size: 0.70rem;">
                    {{ $stock['risk'] }}
                </span>
            </div>
            
            <p class="text-secondary fw-medium mb-5 lh-lg" style="font-size: 1rem;">{{ $stock['description'] }}</p>
            
            <div class="mt-auto pt-4 border-top border-light-subtle d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column gap-1">
                    <span class="text-muted" style="font-size: 0.70rem; font-weight: 700; letter-spacing: 1px;">LIVE MARKET TREND</span>
                    <span class="text-success fw-bold fs-6"><i class="bi bi-caret-up-fill me-1"></i>STRONGLY BULLISH</span>
                </div>
                <a href="{{ $stock['link'] }}" target="_blank" class="btn btn-gradient text-white rounded px-4 py-3 shadow-sm fw-bold hover-elevate d-flex align-items-center">
                    Simulate & Review <i class="bi bi-box-arrow-up-right ms-2 fs-6 opacity-75"></i>
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Add Holding Vector Overlay (Bootstrap Modal) -->
<div class="modal fade" id="addHoldingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-gradient bg-opacity-10 border-0 p-4 pb-3">
                <h4 class="modal-title fw-bold text-dark"><i class="bi bi-briefcase-fill text-primary me-2"></i> Register Holding Parameter</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('investments.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 pt-3 bg-light bg-opacity-50">
                    <div class="mb-4 position-relative">
                        <label class="form-label fw-bold fw-bolder text-muted tracking-wider text-uppercase" style="font-size: 0.8rem;">Live Asset Ticker (NSE)</label>
                        <input type="text" id="liveTickerInput" name="stock_name" class="form-control form-control-lg rounded-pill px-4 shadow-sm border-0 @error('stock_name') is-invalid @enderror" placeholder="e.g., RELIANCE, TCS, INFY" required>
                        <div id="tickerSpinner" class="spinner-border text-primary spinner-border-sm position-absolute d-none" style="right: 20px; top: 42px;" role="status"></div>
                        @error('stock_name') <div class="invalid-feedback fw-bold ps-4 mt-2">{{ $message }}</div> @enderror
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label fw-bold fw-bolder text-muted tracking-wider text-uppercase" style="font-size: 0.8rem;">Qty Bought</label>
                            <input type="number" step="0.01" name="quantity" class="form-control form-control-lg rounded-pill px-4 shadow-sm border-0" placeholder="15" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold fw-bolder text-muted tracking-wider text-uppercase" style="font-size: 0.8rem;">Execution Value (₹)</label>
                            <input type="number" id="livePriceOutput" step="0.01" name="buy_price" class="form-control form-control-lg rounded-pill px-4 shadow-sm border-0" placeholder="Auto-fetching..." required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold fw-bolder text-muted tracking-wider text-uppercase" style="font-size: 0.8rem;">Acquisition Date</label>
                        <input type="date" name="buy_date" class="form-control form-control-lg rounded-pill px-4 shadow-sm border-0" required value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 bg-white d-flex flex-nowrap gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold flex-grow-1 py-3" data-bs-dismiss="modal">Abort</button>
                    <button type="submit" class="btn btn-gradient rounded-pill px-4 fw-bold shadow-sm flex-grow-1 py-3">Instantiate Matrix Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any())
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('addHoldingModal'));
        myModal.show();
    });
</script>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tickerInput = document.getElementById('liveTickerInput');
        const priceOutput = document.getElementById('livePriceOutput');
        const spinner = document.getElementById('tickerSpinner');
        
        let timeout = null;

        tickerInput.addEventListener('keyup', function() {
            clearTimeout(timeout);
            const ticker = this.value.trim();
            
            if(ticker.length > 2) {
                spinner.classList.remove('d-none');
                
                timeout = setTimeout(() => {
                    fetch(`{{ route('investments.live_price') }}?ticker=${ticker}`)
                        .then(response => response.json())
                        .then(data => {
                            spinner.classList.add('d-none');
                            if(data.valid && data.price) {
                                priceOutput.value = data.price.toFixed(2);
                                priceOutput.classList.add('bg-success', 'bg-opacity-10', 'text-success');
                                priceOutput.style.fontWeight = 'bold';
                                setTimeout(() => {
                                    priceOutput.classList.remove('bg-success', 'bg-opacity-10', 'text-success');
                                    priceOutput.style.fontWeight = 'normal';
                                }, 1500);
                            }
                        })
                        .catch(() => spinner.classList.add('d-none'));
                }, 800); // 800ms debounce
            }
        });
    });
</script>

@endsection
