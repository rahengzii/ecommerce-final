@extends('master')

@section('title', 'KH QR Payment – Sample Store')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-4">
                    <div class="text-center">
                        <h2 class="mb-2 text-success">
                            <i class="fas fa-qrcode mr-2"></i>KH QR Payment
                        </h2>
                        <p class="text-muted mb-0">Scan to complete your payment</p>
                    </div>
                </div>
                
                <div class="card-body py-4">
                    {{-- Order Information --}}
                    <div class="alert alert-info border-0 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-lg mr-3 text-info"></i>
                            <div>
                                <h6 class="mb-1 text-info">Order #{{ $order->order_id }}</h6>
                                <p class="mb-0 small">Please complete payment within 30 minutes</p>
                            </div>
                        </div>
                    </div>

                    {{-- QR Code Display --}}
                    <div class="text-center mb-4">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                @if(!empty($qrCode) && (isset($qrCode['qr_code']) || isset($qrCode['qr_string'])))
                                    {{-- QR Code Image --}}
                                    <div class="mb-3">
                                        @if(isset($qrCode['qr_code']) && !empty($qrCode['qr_code']))
                                            <img src="data:image/png;base64,{{ $qrCode['qr_code'] }}" 
                                                 alt="KHQR Code" 
                                                 class="img-fluid border rounded shadow-sm"
                                                 style="max-width: 300px;">
                                        @elseif(isset($qrCode['qr_string']) && !empty($qrCode['qr_string']))
                                            {{-- Fallback: Generate QR from string using external API --}}
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($qrCode['qr_string']) }}" 
                                                 alt="KHQR Code" 
                                                 class="img-fluid border rounded shadow-sm"
                                                 style="max-width: 300px;">
                                        @else
                                            <div class="text-center py-5">
                                                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                                <p class="text-muted">QR code unavailable</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    {{-- Show QR String for manual entry if needed --}}
                                    @if(isset($qrCode['qr_string']) && !empty($qrCode['qr_string']))
                                    <div class="mb-3">
                                        <h6 class="text-muted small">KHQR String:</h6>
                                        <!-- <div class="bg-white p-3 rounded border">
                                            <code style="font-size: 11px; word-break: break-all; display: block;">{{ $qrCode['qr_string'] }}</code>
                                        </div> -->
                                        <small class="text-muted d-block mt-2">
                                            <!-- <i class="fas fa-info-circle mr-1"></i> -->
                                            <!-- Use this string if QR scanning fails -->
                                        </small>
                                    </div>
                                    @endif
                                @else
                                    <div class="text-center py-5">
                                        <div class="spinner-border text-success mb-3" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <p class="text-muted">Generating QR Code...</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <h4 class="text-success font-weight-bold">៛{{ number_format($order->total * 4000, 0) }}</h4>
                            <p class="text-muted small mb-0">Total Amount (KHR)</p>
                        </div>
                    </div>

                    {{-- Order Items Breakdown --}}
                    @if(isset($orderItems) && $orderItems->count() > 0)
                    <div class="card border-0 bg-light mb-4">
                        <div class="card-body">
                            <h6 class="font-weight-bold mb-3 text-uppercase text-muted small">Order Items</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless mb-0">
                                    @foreach($orderItems as $item)
                                    <tr>
                                        <td class="pl-0">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('image/'.$item->image) }}" 
                                                     alt="{{ $item->product_name }}" 
                                                     class="rounded mr-2" 
                                                     style="width: 30px; height: 30px; object-fit: cover;">
                                                <div>
                                                    <small class="font-weight-bold d-block">{{ $item->product_name }}</small>
                                                    <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right pr-0 align-middle">
                                            <small class="font-weight-bold">${{ number_format($item->total, 2) }}</small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Subtotal:</small>
                                <small class="font-weight-bold">${{ number_format($order->subtotal, 2) }}</small>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Shipping:</small>
                                <small class="font-weight-bold">${{ number_format($order->shipping, 2) }}</small>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">Tax:</small>
                                <small class="font-weight-bold">${{ number_format($order->tax, 2) }}</small>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between">
                                <strong class="text-muted">Total:</strong>
                                <strong class="text-success">៛{{ number_format($order->total * 4000, 0) }}</strong>
                            </div>
                        </div>
                    </div>
                    @else
                    {{-- Payment Details (Fallback if no items) --}}
                    <div class="card border-0 bg-light mb-4">
                        <div class="card-body">
                            <h6 class="font-weight-bold mb-3 text-uppercase text-muted small">Payment Details</h6>
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Order ID:</small>
                                    <div class="font-weight-bold">{{ $order->order_id }}</div>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Amount:</small>
                                    <div class="font-weight-bold text-success">${{ number_format($order->total, 2) }}</div>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Currency:</small>
                                    <div class="font-weight-bold">USD</div>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Merchant:</small>
                                    <div class="font-weight-bold">Sample Store</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Instructions --}}
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="font-weight-bold mb-3 text-uppercase text-muted small">
                                <i class="fas fa-mobile-alt mr-2"></i>How to Pay
                            </h6>
                            <ol class="pl-3 mb-0 small">
                                <li class="mb-2">Open your mobile banking app (Bakong, ABA, ACLEDA, etc.)</li>
                                <li class="mb-2">Tap on "Scan QR" or "KHQR" feature</li>
                                <li class="mb-2">Scan the QR code above</li>
                                <li class="mb-2">Confirm payment details and amount</li>
                                <li class="mb-2">Enter your PIN to complete payment</li>
                                <li>You will be redirected automatically upon successful payment</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white border-0 py-4">
                    <div class="row">
                        <div class="col-6">
                            <a href="{{ route('checkout_index') }}" class="btn btn-outline-dark btn-block">
                                <i class="fas fa-arrow-left mr-2"></i>Back
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('profile') }}" class="btn btn-outline-success btn-block">
                                <i class="fas fa-shopping-bag mr-2"></i>My Orders
                            </a>
                        </div>
                    </div>
                    
                    {{-- Payment Status --}}
                    <div id="paymentStatus" class="mt-3 text-center" style="display: none;">
                        <div class="spinner-border spinner-border-sm text-success mr-2" role="status"></div>
                        <span class="text-muted small">Waiting for payment confirmation...</span>
                    </div>
                </div>
            </div>

            {{-- Supported Banks --}}
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body text-center py-3">
                    <h6 class="text-uppercase text-muted small mb-3">Supported Payment Methods</h6>
                    <div class="row align-items-center">
                        <div class="col-3">
                            <div class="text-center">
                                <i class="fas fa-university fa-2x text-primary mb-2"></i>
                                <small class="d-block">ABA Bank</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="text-center">
                                <i class="fas fa-landmark fa-2x text-success mb-2"></i>
                                <small class="d-block">ACLEDA</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="text-center">
                                <i class="fas fa-piggy-bank fa-2x text-warning mb-2"></i>
                                <small class="d-block">Wing</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="text-center">
                                <i class="fas fa-mobile-alt fa-2x text-info mb-2"></i>
                                <small class="d-block">Bakong</small>
                            </div>
                        </div>
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <script>
        $(document).ready(function() {
            let paymentCheckInterval;
            let khqrMd5;
            let orderId;

            // Auto-generate KHQR when page loads if order ID is available
            const pageOrderId = '{{ $order->order_id ?? null }}';
            if (pageOrderId) {
                orderId = pageOrderId;
                generateAndShowKHQR(orderId);
            }

            // Handle form submission
            $('#checkout-form').on('submit', function(e) {
                e.preventDefault();

                const selectedMode = $('input[name="mode"]:checked').val();

                if (selectedMode === 'khqr') {
                    // Submit form via AJAX for KHQR
                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            console.log('Order placed:', response);
                            if (response.success) {
                                orderId = response.order_id;
                                // Generate and show KHQR
                                generateAndShowKHQR(orderId);
                            }
                        },
                        error: function(xhr) {
                            console.error('Order placement error:', xhr);
                            showAlert('Error placing order. Please try again.', 'danger');
                        }
                    });
                } else {
                    // Submit form normally for other payment methods
                    this.submit();
                }
            });

            function generateAndShowKHQR(orderId) {
                console.log('Generating KHQR for order:', orderId);
                // Call API to generate KHQR
                $.ajax({
                    url: '/generate-khqr/' + orderId,
                    method: 'GET',
                    success: function(response) {
                        console.log('KHQR Response:', response);
                        if (response && response.data) {
                            const qrString = response.data.qr;
                            khqrMd5 = response.data.md5;

                            console.log('KHQR MD5:', khqrMd5);

                            // Generate QR code
                            const canvas = document.getElementById('qrcode');
                            QRCode.toCanvas(canvas, qrString, {
                                width: 300,
                                margin: 2
                            }, function(error) {
                                if (error) {
                                    console.error(error);
                                    showAlert('Error generating QR code', 'danger');
                                    return;
                                }
                                console.log('QR Code generated successfully');
                            });

                            // Show modal
                            $('#khqrModal').modal('show');

                            // Start checking for payment
                            startPaymentCheck();
                        } else {
                            console.error('Invalid KHQR response:', response);
                            showAlert('Error generating KHQR. Please try again.', 'danger');
                        }
                    },
                    error: function(xhr) {
                        console.error('KHQR generation error:', xhr);
                        showAlert('Error generating KHQR. Please try again.', 'danger');
                    }
                });
            }

            function startPaymentCheck() {
                console.log('Starting payment check with MD5:', khqrMd5, 'Order ID:', orderId);
                // Check payment status every 3 seconds
                paymentCheckInterval = setInterval(function() {
                    checkPaymentStatus();
                }, 3000);
            }

            function checkPaymentStatus() {
                console.log('Checking payment status...');
                $.ajax({
                    url: '/check-khqr-payment',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        md5: khqrMd5,
                        order_id: orderId
                    },
                    success: function(response) {
                        console.log('Payment check response:', response);
                        console.log('Response success:', response.success);
                        console.log('Response paid:', response.paid);

                        // Check if payment is successful
                        if (response.success === true && response.paid === true) {
                            console.log('Payment confirmed! Closing modal...');
                            handlePaymentSuccess();
                        } else {
                            console.log('Payment still pending...');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error checking payment status:', xhr);
                        console.error('Response text:', xhr.responseText);
                    }
                });
            }

            // Test button click handler
            $(document).on('click', '#test-payment-success', function() {
                console.log('TEST: Simulating successful payment');

                // Stop the payment check interval
                if (paymentCheckInterval) {
                    clearInterval(paymentCheckInterval);
                }

                // Show processing state
                $('#payment-status').html(
                    '<div class="spinner-border text-warning" role="status">' +
                    '<span class="visually-hidden">Processing...</span>' +
                    '</div>' +
                    '<p class="mt-2">Processing test payment...</p>'
                );

                // Manually approve the transaction
                $.ajax({
                    url: '/approve-test-payment',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        order_id: orderId
                    },
                    success: function(response) {
                        console.log('Test payment approved:', response);
                        if (response.success) {
                            handlePaymentSuccess();
                        }
                    },
                    error: function(xhr) {
                        console.error('Failed to approve test payment:', xhr);
                        showAlert('Failed to process test payment', 'danger');
                    }
                });
            });

            function handlePaymentSuccess() {
                // Payment successful
                clearInterval(paymentCheckInterval);

                // Update UI in modal
                $('#payment-status').html(
                    '<div class="alert alert-success">' +
                    '<i class="fas fa-check-circle fa-3x mb-3"></i>' +
                    '<h5>Payment Successful!</h5>' +
                    '<p>Your payment has been confirmed.</p>' +
                    '<p>Redirecting to confirmation page...</p>' +
                    '</div>'
                );

                // Show success notification
                showAlert('Payment confirmed successfully! Your order has been placed.', 'success');

                // Close modal and redirect after 2 seconds
                setTimeout(function() {
                    console.log('Hiding modal and redirecting...');
                    $('#khqrModal').modal('hide');
                    // Redirect to order confirmation
                    window.location.href = '/order-confirmation';
                }, 2000);
            }

            // Function to show alert notification
            function showAlert(message, type) {
                console.log('Showing alert:', type, message);
                // Remove any existing alerts
                $('.custom-alert').remove();

                // Create alert element
                const alertHtml =
                    '<div class="custom-alert alert alert-' + type + ' alert-dismissible fade show" role="alert" ' +
                    'style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">' +
                    '<strong>' + (type === 'success' ? 'Success!' : 'Error!') + '</strong> ' + message +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                    '</div>';

                // Append to body
                $('body').append(alertHtml);

                // Auto dismiss after 5 seconds
                setTimeout(function() {
                    $('.custom-alert').fadeOut('slow', function() {
                        $(this).remove();
                    });
                }, 5000);
            }

            // Clear interval when modal is closed manually
            $('#khqrModal').on('hidden.bs.modal', function() {
                console.log('Modal closed, clearing interval');
                if (paymentCheckInterval) {
                    clearInterval(paymentCheckInterval);
                }
            });
        });
    </script>
@endpush

@push('styles')
<style>
.card {
    border-radius: 15px;
}
.card-header {
    border-radius: 15px 15px 0 0 !important;
}
.card-footer {
    border-radius: 0 0 15px 15px !important;
}
.btn {
    border-radius: 8px;
    font-weight: 600;
}
.alert {
    border-radius: 10px;
}
ol {
    padding-left: 1.2rem;
}
ol li {
    margin-bottom: 0.5rem;
}
code {
    background: #f8f9fa;
    padding: 8px;
    border-radius: 5px;
    font-family: 'Courier New', monospace;
    font-size: 11px;
    line-height: 1.4;
}
</style>
@endpush

@endsection