<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Finance App</title>
    <!-- Modern Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4f46e5, #7c3aed);
            --success-gradient: linear-gradient(135deg, #10b981, #059669);
            --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
            --dark-gradient: linear-gradient(135deg, #1e293b, #0f172a);
            --surface-color: #f3f4f6;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--surface-color);
            color: #334155;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand, .nav-link, .btn {
            font-family: 'Outfit', sans-serif;
        }

        /* Navbar Styling */
        .navbar-custom {
            background: var(--primary-gradient);
            padding: 1rem 0;
        }
        
        /* Glassmorphism & Cards */
        .glass-card {
            background: white;
            border-radius: 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-elevate:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.12);
            border-color: rgba(79, 70, 229, 0.2);
        }

        /* Buttons Update */
        .btn-gradient {
            background: var(--primary-gradient);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #4338ca, #6d28d9);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(124, 58, 237, 0.25);
        }

        /* Tab Styling */
        .custom-nav-tabs {
            border-bottom: none;
            gap: 1rem;
        }
        .custom-nav-tabs .nav-link {
            border: none;
            color: #64748b;
            font-weight: 600;
            padding: 0.75rem 1.75rem;
            border-radius: 50rem;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }
        .custom-nav-tabs .nav-link:hover {
            background: #f1f5f9;
            transform: translateY(-2px);
        }
        .custom-nav-tabs .nav-link.active {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.25);
            transform: translateY(-2px);
        }

        /* Metric Cards internally */
        .metric-card {
            position: relative;
            overflow: hidden;
        }
        .metric-card::after {
            content: '';
            position: absolute;
            top: 0; right: 0; width: 120px; height: 120px;
            background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
            border-radius: 0 0 0 120px;
        }

        .hover-white { transition: all 0.3s ease; }
        .hover-white:hover { color: #ffffff !important; opacity: 1 !important; transform: translateY(-1px); display: inline-block; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom mb-5 border-bottom border-light border-opacity-25">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3 text-white tracking-tight d-flex align-items-center" href="{{ url('/') }}">
                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 40px; height: 40px;">
                    <i class="bi bi-wallet2 fs-5"></i>
                </div>
                SmartFinance
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                <ul class="navbar-nav me-auto ps-lg-4 gap-1">
                    <li class="nav-item">
                        <a class="nav-link text-white rounded-pill px-4 {{ request()->is('expenses') ? 'bg-white bg-opacity-25 fw-bold' : 'opacity-75' }}" href="{{ route('expenses.index') }}">
                            <i class="bi bi-grid-1x2-fill me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white rounded-pill px-4 {{ request()->is('profile') ? 'bg-white bg-opacity-25 fw-bold' : 'opacity-75 hover-white' }}" href="{{ route('profile.index') }}">
                            <i class="bi bi-person-badge-fill me-1"></i> My Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white rounded-pill px-4 {{ request()->is('goals') ? 'bg-white bg-opacity-25 border-bottom border-light fw-bold shadow-sm' : 'opacity-75 hover-white' }}" href="{{ route('goals.index') }}">
                            <i class="bi bi-bullseye text-warning me-1"></i> Smart Goals
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link rounded-pill py-2 mt-1 px-4 fw-bold shadow-sm d-flex align-items-center gap-2 {{ request()->is('investments') ? 'bg-white text-primary' : 'text-white' }}" href="{{ route('investments.index') }}" style="{{ request()->is('investments') ? '' : 'background: var(--primary-gradient); border: 1px solid rgba(255,255,255,0.2);' }} transition: all 0.3s ease;">
                            <i class="bi bi-lightning-charge-fill {{ request()->is('investments') ? 'text-warning' : 'text-white' }}"></i> 
                            Investments <span class="badge {{ request()->is('investments') ? 'bg-primary text-white' : 'bg-white text-primary' }} ms-1 rounded-pill" style="font-size: 0.65rem;">by Groww</span>
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    <div class="d-flex align-items-center bg-black bg-opacity-10 rounded-pill px-3 py-1 border border-light border-opacity-10">
                        <i class="bi bi-emoji-smile-fill text-warning me-2 fs-5"></i>
                        <span class="text-white fw-medium opacity-75">{{ Auth::user()->name }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm rounded-pill px-4 py-2 fw-bold shadow-sm">Logout</button>
                    </form>
                </div>
                @else
                <ul class="navbar-nav ms-auto gap-3 mt-3 mt-lg-0">
                    <li class="nav-item d-flex align-items-center">
                        <a class="nav-link text-white fw-bold" href="{{ route('login') }}">Sign In</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-light rounded-pill px-4 py-2 shadow-sm fw-bold text-primary" href="{{ route('register') }}">Create Account</a>
                    </li>
                </ul>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show glass-card border-0 border-start border-5 border-success p-4 d-flex align-items-center mb-5" role="alert">
                <i class="bi bi-check-circle-fill fs-3 text-success me-3"></i>
                <div class="fs-5 fw-medium text-dark">{{ session('success') }}</div>
                <button type="button" class="btn-close mt-2 me-3" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Dashboard Footer -->
    <footer class="mt-auto py-5 border-top border-white border-opacity-10" style="background: var(--primary-gradient);">
        <div class="container text-center text-md-start">
            <div class="row align-items-center">
                <div class="col-md-6 text-white text-opacity-75 small fw-medium mb-3 mb-md-0">
                    &copy; {{ date('Y') }} Smart Finance. Developed Integrations.
                </div>
                <div class="col-md-6 text-md-end">
                    <ul class="list-inline mb-0 small fw-medium">
                        <li class="list-inline-item me-4"><a href="#" class="text-decoration-none text-white text-opacity-50 hover-white">Privacy Policy</a></li>
                        <li class="list-inline-item me-4"><a href="#" class="text-decoration-none text-white text-opacity-50 hover-white">Data Security</a></li>
                        <li class="list-inline-item"><a href="#" class="text-decoration-none text-white text-opacity-50 hover-white">Support Network</a></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 text-center text-md-start">
                    <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-10 rounded-pill px-3 py-2 font-monospace fw-bold tracking-wide shadow-sm" style="font-size: 0.70rem;">
                        <i class="bi bi-broadcast text-success me-2 fs-6" style="vertical-align: middle;"></i> System Operational (v2.4.0)
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
