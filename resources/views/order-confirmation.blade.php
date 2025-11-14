@extends('master')
@section('title','Order Confirmation — Sample Store')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Success Alert --}}
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                <strong>Success!</strong> Your order has been placed successfully.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Order Confirmation Card --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center py-5">
                    <div class="display-1 text-success mb-3">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2 class="h4 mb-3">Thank You for Your Order!</h2>
                    <p class="text-muted mb-4">Your order has been confirmed and will be shipped soon.</p>

                    {{-- Order Details --}}
                    <div class="row justify-content-center mb-4">
                        <div class="col-md-8">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Order Details</h5>
                                    <div class="row text-left">
                                        <div class="col-6">
                                            <strong>Order Number:</strong>
                                        </div>
                                        <div class="col-6">
                                            #{{ $order->id ?? 'ORD-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT) }}
                                        </div>
                                    </div>
                                    <div class="row text-left">
                                        <div class="col-6">
                                            <strong>Order Date:</strong>
                                        </div>
                                        <div class="col-6">
                                            {{ now()->format('F d, Y') }}
                                        </div>
                                    </div>
                                    <div class="row text-left">
                                        <div class="col-6">
                                            <strong>Total Amount:</strong>
                                        </div>
                                        <div class="col-6">
                                            <strong class="text-success">${{ number_format($total ?? 0, 2) }}</strong>
                                        </div>
                                    </div>
                                    <div class="row text-left">
                                        <div class="col-6">
                                            <strong>Payment Method:</strong>
                                        </div>
                                        <div class="col-6">
                                            {{ ucfirst($payment_method ?? 'Credit Card') }}
                                        </div>
                                    </div>
                                    <div class="row text-left">
                                        <div class="col-6">
                                            <strong>Status:</strong>
                                        </div>
                                        <div class="col-6">
                                            <span class="badge badge-success">Confirmed</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Order Items --}}
                    <div class="mb-4">
                        <h5 class="mb-3">Order Items</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order_items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('image/'.$item->image) }}"
                                                    alt="{{ $item->name }}"
                                                    class="mr-3"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                                <div>{{ $item->name }}</div>
                                            </div>
                                        </td>
                                        <td class="align-middle">{{ $item->qty }}</td>
                                        <td class="align-middle">${{ number_format($item->price, 2) }}</td>
                                        <td class="align-middle">${{ number_format($item->price * $item->qty, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                                        <td><strong>${{ number_format($subtotal ?? 0, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Shipping:</strong></td>
                                        <td><strong>${{ number_format($shipping ?? 0, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Tax:</strong></td>
                                        <td><strong>${{ number_format($tax ?? 0, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                        <td><strong class="text-success">${{ number_format($total ?? 0, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    {{-- Shipping Information --}}
                    <div class="row text-left mb-4">
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Shipping Address</h6>
                                    <p class="mb-0">
                                        <strong>{{ $shipping_info['fullname'] ?? 'John Doe' }}</strong><br>
                                        {{ $shipping_info['address'] ?? '123 Main Street' }}<br>
                                        {{ $shipping_info['city'] ?? 'New York' }}, {{ $shipping_info['zip'] ?? '10001' }}<br>
                                        Phone: {{ $shipping_info['phone'] ?? '+1 (555) 123-4567' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Billing Address</h6>
                                    <p class="mb-0">
                                        <strong>{{ $shipping_info['fullname'] ?? 'John Doe' }}</strong><br>
                                        {{ $shipping_info['address'] ?? '123 Main Street' }}<br>
                                        {{ $shipping_info['city'] ?? 'New York' }}, {{ $shipping_info['zip'] ?? '10001' }}<br>
                                        Email: {{ $shipping_info['email'] ?? 'john@example.com' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-home mr-2"></i>Go to Home Page
                        </a>
                        <a href="#" class="btn btn-outline-dark btn-lg">
                            <i class="fas fa-print mr-2"></i>Print Receipt
                        </a>
                        <a href="{{ route('cart_index') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-shopping-cart mr-2"></i>Continue Shopping
                        </a>
                    </div>

                    {{-- Additional Info --}}
                    <div class="mt-5">
                        <p class="text-muted small">
                            A confirmation email has been sent to <strong>{{ $shipping_info['email'] ?? 'your email' }}</strong>.
                            You will receive a shipping confirmation email when your order is on its way.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Next Steps --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">What's Next?</h5>
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="text-primary mb-2">
                                <i class="fas fa-envelope fa-2x"></i>
                            </div>
                            <h6>Order Confirmation</h6>
                            <small class="text-muted">Check your email for order details</small>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="text-warning mb-2">
                                <i class="fas fa-cog fa-2x"></i>
                            </div>
                            <h6>Processing</h6>
                            <small class="text-muted">We're preparing your order</small>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="text-info mb-2">
                                <i class="fas fa-shipping-fast fa-2x"></i>
                            </div>
                            <h6>Shipping</h6>
                            <small class="text-muted">Your order is on the way</small>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="text-success mb-2">
                                <i class="fas fa-box-open fa-2x"></i>
                            </div>
                            <h6>Delivery</h6>
                            <small class="text-muted">Expected in 3-5 business days</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.product-image-main {
    border-radius: 15px;
    overflow: hidden;
}

.quantity-selector .btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    border-width: 2px;
}

.quantity-selector .btn:hover {
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.quantity-selector .btn:active {
    transform: scale(0.95);
}

.opacity-50 {
    opacity: 0.5 !important;
}

.quantity-selector .btn:disabled {
    pointer-events: none;
    opacity: 0.5;
}

.quantity-selector input {
    border-radius: 8px;
}

.action-buttons .btn {
    border-radius: 10px;
    padding: 12px 20px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.action-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.action-buttons .btn:active {
    transform: translateY(0);
}

.action-buttons .btn:disabled {
    opacity: 0.7;
    transform: none;
    box-shadow: none;
}

/* Add to Cart button specific styles */
#addToCartBtn {
    border-width: 2px;
    font-weight: 600;
}

#addToCartBtn:hover:not(:disabled) {
    background-color: #343a40;
    border-color: #343a40;
    color: white;
}

/* Loading state for add to cart button */
.btn-loading {
    position: relative;
}

.btn-loading .fas.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Success pulse animation for cart badge */
.animate__bounce {
    animation: bounce 1s ease-in-out;
}

@keyframes bounce {
    0%, 20%, 53%, 80%, 100% {
        transform: translate3d(0, 0, 0);
    }
    40%, 43% {
        transform: translate3d(0, -8px, 0);
    }
    70% {
        transform: translate3d(0, -4px, 0);
    }
    90% {
        transform: translate3d(0, -2px, 0);
    }
}

.nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
    padding: 1rem 1.5rem;
}

.nav-tabs .nav-link.active {
    color: #343a40;
    border-bottom: 3px solid #343a40;
    background: transparent;
}

.star-rating {
    color: #ffc107;
}

.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.breadcrumb {
    background: transparent;
    padding: 0;
}

.breadcrumb-item a {
    color: #6c757d;
    text-decoration: none;
}

.breadcrumb-item.active {
    color: #343a40;
}

.additional-info .border {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.additional-info .border:hover {
    background-color: #f8f9fa;
    transform: translateY(-2px);
}
</style>
@endpush