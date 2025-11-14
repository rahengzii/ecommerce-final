@extends('master')
@section('title','Login — Sample Store')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    {{-- Header --}}
                    <div class="text-center mb-4">
                        <h2 class="h4 font-weight-bold">Welcome Back</h2>
                        <p class="text-muted">Sign in to your account</p>
                    </div>

                    {{-- Flash Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    {{-- Login Form --}}
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        
                        {{-- Email --}}
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="form-group">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Remember Me & Forgot Password --}}
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label small" for="remember">Remember me</label>
                            </div>
                            <a href="#" class="small text-muted">Forgot password?</a>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" class="btn btn-dark btn-lg btn-block rounded-pill">
                            Sign In
                        </button>
                    </form>

                    {{-- Divider --}}
                    <div class="text-center my-4">
                        <span class="bg-light px-3 text-muted small">OR</span>
                    </div>

                    {{-- Social Login (Optional) --}}
                    <div class="text-center">
                        <p class="small text-muted mb-3">Sign in with social media</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="#" class="btn btn-outline-danger rounded-circle p-2">
                                <i class="fab fa-google"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary rounded-circle p-2">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="btn btn-outline-dark rounded-circle p-2">
                                <i class="fab fa-apple"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Register Link --}}
                    <div class="text-center mt-4">
                        <p class="mb-0">Don't have an account? 
                            <a href="{{ route('register') }}" class="text-dark font-weight-bold">Sign up</a>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Policies --}}
            <div class="text-center mt-4">
                <p class="small text-muted">
                    By continuing, you agree to our 
                    <a href="#" class="text-muted">Terms of Service</a> and 
                    <a href="#" class="text-muted">Privacy Policy</a>.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.btn-rounded {
    border-radius: 50px;
}
.form-control {
    border-radius: 8px;
    padding: 12px 15px;
}
.card {
    border-radius: 15px;
}
</style>
@endpush