@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="col-12 col-md-9 col-lg-7 col-xl-6">
        <div class="glass-card border-0 shadow-lg p-4 p-md-5 position-relative overflow-hidden hover-elevate" style="border-radius: 24px;">
            <div class="position-relative z-1">
                <div class="text-center mb-5 mt-2">
                    <div class="d-inline-flex bg-primary bg-opacity-10 rounded p-3 mb-4 shadow-sm border border-primary border-opacity-25 text-primary">
                        <i class="bi bi-person-plus-fill" style="font-size: 2.5rem;"></i>
                    </div>
                    <h2 class="fw-bold tracking-tight text-dark mb-1" style="font-size: 2.2rem;">Create Account</h2>
                    <p class="text-muted fw-medium">Join us and take control of your financial goals.</p>
                </div>
                
                <form action="{{ route('register') }}" method="POST">
                    @csrf
    
                    <div class="form-floating mb-4">
                        <input type="text" name="name" class="form-control form-control-lg rounded-pill px-4 shadow-sm border-0 bg-light @error('name') is-invalid @enderror" id="floatingName" placeholder="Full Name" value="{{ old('name') }}" required>
                        <label for="floatingName" class="px-4 text-muted fw-bold" style="font-size: 0.9rem;"><i class="bi bi-person-fill me-2 text-primary"></i> Full Name</label>
                        @error('name') <div class="invalid-feedback fw-bold ps-4">{{ $message }}</div> @enderror
                    </div>
    
                    <div class="form-floating mb-4">
                        <input type="email" name="email" class="form-control form-control-lg rounded-pill px-4 shadow-sm border-0 bg-light @error('email') is-invalid @enderror" id="floatingEmail" placeholder="name@example.com" value="{{ old('email') }}" required>
                        <label for="floatingEmail" class="px-4 text-muted fw-bold" style="font-size: 0.9rem;"><i class="bi bi-envelope-fill me-2 text-primary"></i> Email Address</label>
                        @error('email') <div class="invalid-feedback fw-bold ps-4">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="row g-3 mb-5">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" name="password" class="form-control form-control-lg rounded-pill px-4 shadow-sm border-0 bg-light @error('password') is-invalid @enderror" id="floatingPass" placeholder="Password" required>
                                <label for="floatingPass" class="px-4 text-muted fw-bold text-truncate w-100" style="font-size: 0.9rem;"><i class="bi bi-lock-fill me-2 text-primary"></i> Password</label>
                                @error('password') <div class="invalid-feedback fw-bold ps-4">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" name="password_confirmation" class="form-control form-control-lg rounded-pill px-4 shadow-sm border-0 bg-light" id="floatingPassConfirm" placeholder="Confirm Password" required>
                                <label for="floatingPassConfirm" class="px-4 text-muted fw-bold text-truncate w-100" style="font-size: 0.9rem;"><i class="bi bi-check-circle-fill me-2 text-primary"></i> Confirm Password</label>
                            </div>
                        </div>
                    </div>
    
                    <button type="submit" class="btn btn-gradient btn-lg w-100 rounded-pill shadow-sm fw-bold tracking-wide py-3 d-flex justify-content-center align-items-center gap-2 hover-elevate">
                        Sign Up <i class="bi bi-arrow-right ms-2 fs-5"></i>
                    </button>
                    
                    <div class="text-center mt-5 mb-2">
                        <p class="text-muted fw-medium fs-6 mb-0">Already have an account? <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-bold ms-1" style="border-bottom: 2px solid var(--bs-primary); padding-bottom: 2px;">Sign In</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
