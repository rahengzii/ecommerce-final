@extends('master')
@section('title','Invoice #' . $order->order_id . ' - Sample Store')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            {{-- Invoice Header --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="font-weight-bold text-dark">INVOICE</h2>
                            <p class="text-muted mb-0">Order #{{ $order->order_id }}</p>
                            <p class="text-muted">Date: {{ \Carbon\Carbon::parse($order->created_at)->format('F d, Y') }}</p>
                        </div>
                        <div class="col-md-6 text-right">
                            <h4 class="font-weight-bold text-dark">Sample Store</h4>
                            <p class="text-muted mb-0">123 Business Street</p>
                            <p class="text-muted mb-0">Phnom Penh, Cambodia</p>
                            <p class="text-muted">+855 123 456 789</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Customer & Shipping Info --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 font-weight-bold">Customer Information</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>{{ $order->fullname }}</strong></p>
                            <p class="mb-1 text-muted">{{ $order->email }}</p>
                            <p class="mb-1 text-muted">{{ $order->phone }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 font-weight-bold">Shipping Address</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>{{ $order->fullname }}</strong></p>
                            <p class="mb-1 text-muted">{{ $order->address }}</p>
                            <p class="mb-0 text-muted">{{ $order->city }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order Items --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0 font-weight-bold">Order Items</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th class="border-0">Product</th>
                                    <th class="border-0 text-center">Quantity</th>
                                    <th class="border-0 text-center">Price</th>
                                    <th class="border-0 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orderItems as $item)
                                <tr>
                                    <td class="border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3">
                                                <div class="bg-light rounded" style="width: 40px; height: 40px;"></div>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold">{{ $item->product_name }}</div>
                                                <small class="text-muted">SKU: {{ $item->product_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-0 text-center align-middle">{{ $item->quantity }}</td>
                                    <td class="border-0 text-center align-middle">${{ number_format($item->price, 2) }}</td>
                                    <td class="border-0 text-right align-middle">${{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="row">
                <div class="col-md-6 offset-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="font-weight-bold mb-3">Order Summary</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal:</span>
                                <span>${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Shipping:</span>
                                <span>${{ number_format($order->shipping, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tax:</span>
                                <span>${{ number_format($order->tax, 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="font-weight-bold">Total:</span>
                                <span class="font-weight-bold text-success">${{ number_format($order->total, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Payment Method:</span>
                                <span class="text-uppercase">{{ $order->payment_method }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Status:</span>
                                <span class="badge badge-{{ $order->status == 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="text-center mt-4">
                <button onclick="window.print()" class="btn btn-dark mr-2">
                    <i class="fas fa-print mr-2"></i>Print Invoice
                </button>
                <a href="{{ route('profile') }}" class="btn btn-outline-dark">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Profile
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    .navbar, footer, .btn {
        display: none !important;
    }
    .card {
        border: 1px solid #dee2e6 !important;
        box-shadow: none !important;
    }
}
</style>
@endpush