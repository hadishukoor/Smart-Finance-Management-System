@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="glass-card border-0 shadow-lg p-4 p-md-5 position-relative overflow-hidden hover-elevate" style="border-radius: 24px;">
            <div class="position-relative z-1">
                <div class="text-center mb-5 mt-2">
                    <div class="d-inline-flex bg-primary bg-opacity-10 rounded-circle p-3 mb-4 shadow-sm border border-primary border-opacity-25">
                        <i class="bi bi-person-fill text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h2 class="fw-bold tracking-tight text-dark mb-1" style="font-size: 2.2rem;">Welcome Back</h2>
                    <p class="text-muted fw-medium">Sign in to manage your finances.</p>
                </div>
                
                <form action="{{ route('login') }}" method="POST">
                    @csrf
    
                    <div class="form-floating mb-4">
                        <input type="email" name="email" class="form-control form-control-lg rounded-pill px-4 shadow-sm border-0 bg-light @error('email') is-invalid @enderror" id="floatingEmail" placeholder="name@example.com" value="{{ old('email') }}" required>
                        <label for="floatingEmail" class="px-4 text-muted fw-bold" style="font-size: 0.9rem;"><i class="bi bi-envelope-fill me-2 text-primary"></i> Email Address</label>
                        @error('email') <div class="invalid-feedback fw-bold ps-4">{{ $message }}</div> @enderror
                    </div>
    
                    <div class="form-floating mb-4">
                        <input type="password" name="password" class="form-control form-control-lg rounded-pill px-4 shadow-sm border-0 bg-light @error('password') is-invalid @enderror" id="floatingPassword" placeholder="Password" required>
                        <label for="floatingPassword" class="px-4 text-muted fw-bold" style="font-size: 0.9rem;"><i class="bi bi-lock-fill me-2 text-primary"></i> Password</label>
                        @error('password') <div class="invalid-feedback fw-bold ps-4">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-5 px-3">
                        <div class="form-check">
                            <input class="form-check-input shadow-sm border-primary" type="checkbox" name="remember" id="remember" checked>
                            <label class="form-check-label fw-bold small text-muted" for="remember">
                                Remember Me
                            </label>
                        </div>
                        <a href="#" class="text-primary text-decoration-none fw-bold small hover-elevate">Forgot Password?</a>
                    </div>
    
                    <button type="submit" class="btn btn-gradient btn-lg w-100 rounded-pill shadow-sm fw-bold tracking-wide py-3 d-flex justify-content-center align-items-center gap-2 hover-elevate overflow-hidden position-relative">
                        <span class="position-relative z-1">Sign In</span> 
                        <i class="bi bi-box-arrow-in-right ms-2 fs-5 position-relative z-1"></i>
                    </button>
                    
                    <div class="text-center mt-5 mb-2">
                        <p class="text-muted fw-medium fs-6 mb-0">Don't have an account? <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-bold ms-1" style="border-bottom: 2px solid var(--bs-primary); padding-bottom: 2px;">Sign Up</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
