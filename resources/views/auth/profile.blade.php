@extends('master')
@section('title','My Profile - Sample Store')
@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-4">
            {{-- Profile Sidebar --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="bg-dark rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-user text-white fa-2x"></i>
                        </div>
                    </div>
                    <h5 class="font-weight-bold">{{ $customer->name }}</h5>
                    <p class="text-muted mb-2">{{ $customer->email }}</p>
                    <p class="text-muted small">Member since {{ \Carbon\Carbon::parse($customer->created_at)->format('M Y') }}</p>
                </div>
            </div>

            {{-- Navigation --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="#profile" class="list-group-item list-group-item-action active" data-toggle="pill" data-target="#profile" role="tab">
                            <i class="fas fa-user mr-2"></i>Profile Information
                        </a>
                        <a href="#orders" class="list-group-item list-group-item-action" data-toggle="pill" data-target="#orders" role="tab">
                            <i class="fas fa-shopping-bag mr-2"></i>My Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="tab-content">
                {{-- Profile Tab --}}
                <div class="tab-pane fade show active" id="profile" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0">Profile Information</h5>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <form action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="name">Full Name *</label>
                                        <input type="text" name="name" id="name" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               value="{{ old('name', $customer->name) }}" required>
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Email Address *</label>
                                        <input type="email" name="email" id="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               value="{{ old('email', $customer->email) }}" required>
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" name="phone" id="phone" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone', $customer->phone) }}">
                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea name="address" id="address" 
                                              class="form-control @error('address') is-invalid @enderror" 
                                              rows="3">{{ old('address', $customer->address) }}</textarea>
                                    @error('address')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-dark">
                                    <i class="fas fa-save mr-2"></i>Update Profile
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Orders Tab --}}
                <div class="tab-pane fade" id="orders" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0">My Orders</h5>
                        </div>
                        <div class="card-body">
                            @if($orders->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Date</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orders as $order)
                                            <tr>
                                                <td class="font-weight-bold">#{{ $order->order_id }}</td>
                                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</td>
                                                <td>${{ number_format($order->total, 2) }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $order->status == 'completed' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('invoice', $order->order_id) }}" 
                                                       class="btn btn-sm btn-outline-dark">
                                                        <i class="fas fa-receipt mr-1"></i>Invoice
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No orders yet</h5>
                                    <p class="text-muted">You haven't placed any orders yet.</p>
                                    <a href="{{ route('food') }}" class="btn btn-dark">Start Shopping</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Profile page JavaScript loaded');
    
    // Bootstrap 4.6 tab functionality with data-toggle="pill"
    $('a[data-toggle="pill"]').on('click', function (e) {
        console.log('Tab clicked:', $(this).attr('data-target'));
        e.preventDefault();
        
        // Remove active class from all tabs
        $('a[data-toggle="pill"]').removeClass('active');
        // Add active class to clicked tab
        $(this).addClass('active');
        
        // Hide all tab panes
        $('.tab-pane').removeClass('show active');
        
        // Show target tab pane
        var target = $(this).attr('data-target');
        console.log('Showing tab:', target);
        $(target).addClass('show active');
    });
    
    // Initialize - make sure profile tab is active on load
    $('a[data-target="#profile"]').addClass('active');
    $('#profile').addClass('show active');
});
</script>
@endpush

@push('styles')
<style>
.list-group-item.active {
    background-color: #343a40;
    border-color: #343a40;
    color: white;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}
</style>
@endpush