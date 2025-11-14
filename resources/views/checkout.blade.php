@extends('master')
@section('title','Checkout — Sample Store')
@section('content')
    <div class="container py-4">
        <div class="row">
            {{-- Left: Checkout Form --}}
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h4 class="mb-3">Checkout</h4>
                        
                        {{-- Remove duplicate error messages --}}
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('checkout_process') }}" method="post" id="checkoutForm">
                            @csrf
                            <input type="hidden" name="customer_id" value="{{ $customer_id ?? '' }}">

                            {{-- Contact Information --}}
                            <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3">Contact Information</h6>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="fullname">Full Name *</label>
                                    <input type="text" name="fullname" id="fullname" class="form-control @error('fullname') is-invalid @enderror"
                                           value="{{ old('fullname', $prefill['fullname'] ?? '') }}" required>
                                    @error('fullname')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Email *</label>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $prefill['email'] ?? '') }}" required>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Shipping Address --}}
                            <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3 mt-4">Shipping Address</h6>
                            <div class="form-group">
                                <label for="address">Address *</label>
                                <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror"
                                       value="{{ old('address', $prefill['address'] ?? '') }}" required>
                                @error('address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="city">City *</label>
                                    <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror"
                                           value="{{ old('city', $prefill['city'] ?? '') }}" required>
                                    @error('city')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="phone">Phone *</label>
                                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $prefill['phone'] ?? '') }}" required>
                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Payment Method --}}
                            <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3 mt-4">Payment Method</h6>
                            <div class="form-group">
                                <div class="custom-control custom-radio mb-3">
                                    <input type="radio" id="khqr" name="payment_method" value="khqr" 
                                           class="custom-control-input" 
                                           {{ old('payment_method', 'khqr') == 'khqr' ? 'checked' : '' }} required>
                                    <label class="custom-control-label d-flex align-items-center" for="khqr">
                                        <i class="fas fa-qrcode fa-lg text-success mr-3"></i>
                                        <div>
                                            <strong>KH QR Code</strong>
                                            <small class="d-block text-muted">Scan to pay with KHQR</small>
                                        </div>
                                    </label>
                                </div>
                                <div class="custom-control custom-radio mb-3">
                                    <input type="radio" id="cod" name="payment_method" value="cod" 
                                           class="custom-control-input"
                                           {{ old('payment_method') == 'cod' ? 'checked' : '' }}>
                                    <label class="custom-control-label d-flex align-items-center" for="cod">
                                        <i class="fas fa-money-bill-wave fa-lg text-primary mr-3"></i>
                                        <div>
                                            <strong>Cash on Delivery</strong>
                                            <small class="d-block text-muted">Pay when you receive your order</small>
                                        </div>
                                    </label>
                                </div>
                                @error('payment_method')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- KH QR Instructions --}}
                            <div id="khqr-instructions" class="mt-3 p-3 bg-light rounded" style="display: none;">
                                <h6 class="font-weight-bold text-success">
                                    <i class="fas fa-info-circle mr-2"></i>How to pay with KHQR
                                </h6>
                                <ol class="mb-0 pl-3">
                                    <li>Complete your order placement</li>
                                    <li>We will send you a KHQR code via email/SMS</li>
                                    <li>Open your mobile banking app</li>
                                    <li>Scan the QR code to complete payment</li>
                                    <li>Your order will be processed once payment is confirmed</li>
                                </ol>
                            </div>

                            {{-- Cash on Delivery Instructions --}}
                            <div id="cod-instructions" class="mt-3 p-3 bg-light rounded" style="display: none;">
                                <h6 class="font-weight-bold text-primary">
                                    <i class="fas fa-info-circle mr-2"></i>About Cash on Delivery
                                </h6>
                                <ul class="mb-0 pl-3">
                                    <li>Pay with cash when your order arrives</li>
                                    <li>Exact change is appreciated</li>
                                    <li>Delivery personnel will provide a receipt</li>
                                    <li>No additional fees for COD service</li>
                                </ul>
                            </div>

                            <button type="submit" class="btn btn-dark btn-lg btn-block mt-4" id="placeOrderBtn">
                                <i class="fas fa-lock mr-2"></i>Place Order
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Right: Order Summary --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Order Summary</h5>
                        
                        {{-- Cart Items --}}
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-right">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart_items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('image/'.$item->image) }}" 
                                                     alt="{{ $item->name }}" 
                                                     class="mr-2 rounded" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                                <div>
                                                    <div class="small font-weight-bold text-truncate" style="max-width: 120px;">{{ $item->name }}</div>
                                                    <small class="text-muted">${{ number_format($item->price, 2) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-dark">{{ $item->qty }}</span>
                                        </td>
                                        <td class="text-right align-middle">
                                            ${{ number_format($item->price * $item->qty, 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Order Totals --}}
                        <ul class="list-group mb-3 mt-3">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Subtotal</span>
                                <strong>${{ number_format($subtotal ?? 0, 2) }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Shipping</span>
                                <span>${{ number_format($shipping ?? 0, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Tax</span>
                                <span>${{ number_format($tax ?? 0, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between bg-light">
                                <span class="font-weight-bold">Total</span>
                                <span class="font-weight-bold text-success">${{ number_format($total ?? 0, 2) }}</span>
                            </li>
                        </ul>

                        {{-- Continue Shopping --}}
                        <a href="{{ route('cart_index') }}" class="btn btn-outline-dark btn-block mb-2">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Cart
                        </a>
                    </div>
                </div>

                {{-- Policies --}}
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body small text-secondary">
                        <div class="d-flex align-items-start mb-2">
                            <i class="fa-solid fa-rotate-left mr-2 mt-1"></i>
                            <div><strong>Free returns</strong> within 30 days</div>
                        </div>
                        <div class="d-flex align-items-start mb-2">
                            <i class="fa-solid fa-lock mr-2 mt-1"></i>
                            <div><strong>Secure checkout</strong> powered by SSL</div>
                        </div>
                        <div class="d-flex align-items-start">
                            <i class="fa-solid fa-truck mr-2 mt-1"></i>
                            <div><strong>Free shipping</strong> on orders over $50</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KHQR Modal -->
    <div class="modal fade" id="khqrModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="khqrModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="khqrModalLabel">Scan KHQR to Pay</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="qrcode-container" class="mb-3 d-flex justify-content-center">
                        <canvas id="qrcode"></canvas>
                    </div>
                    <div class="alert alert-info">
                        <strong>Scan this QR code</strong> with your banking app to complete payment
                    </div>

                    <!-- TEST BUTTON - REMOVE IN PRODUCTION -->
                    <button type="button" class="btn btn-warning mb-3" id="test-payment-success">
                        🧪 Test Payment Success (Dev Only)
                    </button>

                    <div id="payment-status" class="mt-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Checking payment...</span>
                        </div>
                        <p class="mt-2">Waiting for payment confirmation...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const khqrInstructions = document.getElementById('khqr-instructions');
    const codInstructions = document.getElementById('cod-instructions');
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const checkoutForm = document.getElementById('checkoutForm');
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    const testPaymentBtn = document.getElementById('test-payment-success');
    
    // Function to toggle payment instructions
    function togglePaymentInstructions() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        
        // Hide all instructions first
        khqrInstructions.style.display = 'none';
        codInstructions.style.display = 'none';
        
        // Show instructions based on selected method
        if (selectedMethod) {
            if (selectedMethod.value === 'khqr') {
                khqrInstructions.style.display = 'block';
            } else if (selectedMethod.value === 'cod') {
                codInstructions.style.display = 'block';
            }
        }
    }
    
    // Add event listeners to payment method radios
    paymentMethods.forEach(radio => {
        radio.addEventListener('change', togglePaymentInstructions);
    });
    
    // Initialize on page load
    togglePaymentInstructions();
    
    // Form submission handler
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            // Basic validation
            const requiredFields = checkoutForm.querySelectorAll('[required]');
            let valid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }
            
            // Show loading state
            placeOrderBtn.disabled = true;
            placeOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
        });
    }

    // Test payment button handler using checkKHQRPayment
    if (testPaymentBtn) {
        testPaymentBtn.addEventListener('click', function() {
            // Get order_id from session or modal data
            const orderId = document.querySelector('[data-order-id]')?.dataset.orderId 
                         || localStorage.getItem('pending_order_id')
                         || prompt('Enter Order ID:');
            
            if (!orderId) {
                alert('Order ID not found');
                return;
            }

            // Get MD5 from canvas data or stored value
            const md5 = localStorage.getItem('qr_code_md5') || 'test-md5-' + Date.now();

            testPaymentBtn.disabled = true;
            testPaymentBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Checking payment...';

            // Call checkKHQRPayment endpoint
            fetch('/api/khqr/check-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    order_id: orderId,
                    md5: md5
                })
            })
            .then(response => response.json())
            .then(data => {
                const paymentStatus = document.getElementById('payment-status');
                
                if (data.success && data.paid) {
                    // Show success message
                    paymentStatus.innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle mr-2"></i>
                            <strong>Payment Confirmed!</strong>
                            <p class="mb-0 mt-2">${data.message}</p>
                        </div>
                    `;

                    // Redirect to order confirmation after 2 seconds
                    setTimeout(() => {
                        window.location.href = '/order-confirmation/' + orderId;
                    }, 2000);
                } else if (data.success && !data.paid) {
                    // Payment pending
                    paymentStatus.innerHTML = `
                        <div class="alert alert-warning">
                            <i class="fas fa-clock mr-2"></i>
                            <strong>Payment Pending</strong>
                            <p class="mb-0 mt-2">${data.message}</p>
                        </div>
                    `;
                    
                    testPaymentBtn.disabled = false;
                    testPaymentBtn.innerHTML = '🧪 Check Payment Status';
                } else {
                    // Show error message
                    paymentStatus.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle mr-2"></i>
                            <strong>Error</strong>
                            <p class="mb-0 mt-2">${data.message || 'An error occurred'}</p>
                        </div>
                    `;
                    
                    testPaymentBtn.disabled = false;
                    testPaymentBtn.innerHTML = '🧪 Check Payment Status';
                }
            })
            .catch(error => {
                console.error('Payment check error:', error);
                const paymentStatus = document.getElementById('payment-status');
                paymentStatus.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Error</strong>
                        <p class="mb-0 mt-2">${error.message}</p>
                    </div>
                `;
                
                testPaymentBtn.disabled = false;
                testPaymentBtn.innerHTML = '🧪 Check Payment Status';
            });
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.sticky-top {
    position: -webkit-sticky;
    position: sticky;
    top: 20px;
}
.custom-control-input:checked ~ .custom-control-label::before {
    border-color: #343a40;
    background-color: #343a40;
}
.text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.custom-control-label {
    cursor: pointer;
    padding: 8px 0;
}
.custom-radio {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 10px 15px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
}
.custom-radio:hover {
    background-color: #f8f9fa;
    border-color: #007bff;
}
</style>
@endpush