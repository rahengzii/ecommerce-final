@extends('master')
@section('title','Register — Sample Store')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    {{-- Header --}}
                    <div class="text-center mb-4">
                        <h2 class="h4 font-weight-bold">Create Account</h2>
                        <p class="text-muted">Join Sample Store today</p>
                    </div>

                    {{-- Flash Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    {{-- Registration Form --}}
                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        
                        {{-- Full Name --}}
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required>
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
                            <small class="form-text text-muted">Must be at least 6 characters long.</small>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirm Password *</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required>
                        </div>

                        {{-- Address --}}
                        <div class="form-group">
                            <label for="address" class="form-label">Address *</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3" 
                                      required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number *</label>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}" 
                                   required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Terms Agreement --}}
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input @error('terms') is-invalid @enderror" 
                                       id="terms" 
                                       name="terms" 
                                       required>
                                <label class="form-check-label small" for="terms">
                                    I agree to the <a href="#" class="text-dark">Terms of Service</a> 
                                    and <a href="#" class="text-dark">Privacy Policy</a> *
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Newsletter --}}
                        <div class="form-group mb-4">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="newsletter" 
                                       name="newsletter" 
                                       checked>
                                <label class="form-check-label small" for="newsletter">
                                    Send me special offers and updates
                                </label>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" class="btn btn-dark btn-lg btn-block rounded-pill">
                            Create Account
                        </button>
                    </form>

                    {{-- Divider --}}
                    <div class="text-center my-4">
                        <span class="bg-light px-3 text-muted small">OR</span>
                    </div>

                    {{-- Social Register --}}
                    <div class="text-center">
                        <p class="small text-muted mb-3">Sign up with social media</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="#" class="btn btn-outline-danger rounded-pill px-4">
                                <i class="fab fa-google mr-2"></i>Google
                            </a>
                            <a href="#" class="btn btn-outline-primary rounded-pill px-4">
                                <i class="fab fa-facebook-f mr-2"></i>Facebook
                            </a>
                        </div>
                    </div>

                    {{-- Login Link --}}
                    <div class="text-center mt-4">
                        <p class="mb-0">Already have an account? 
                            <a href="{{ route('login') }}" class="text-dark font-weight-bold">Sign in</a>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Benefits --}}
            <div class="row mt-4 text-center">
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fas fa-shipping-fast text-primary mr-2"></i>
                        <span class="small">Free Shipping</span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fas fa-shield-alt text-success mr-2"></i>
                        <span class="small">Secure Checkout</span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fas fa-undo-alt text-info mr-2"></i>
                        <span class="small">Easy Returns</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password strength indicator (optional enhancement)
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 'weak';
            
            if (password.length >= 8) strength = 'medium';
            if (password.length >= 10 && /[A-Z]/.test(password) && /[0-9]/.test(password)) {
                strength = 'strong';
            }
            
            // You can add visual feedback here
        });
    }
});
</script>
@endpush