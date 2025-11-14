@extends('master')
@section('title', $product->name . ' — Sample Store')
@section('content')

    {{-- Breadcrumb --}}
    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent px-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="#">{{ $product->category ?? 'Products' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>

    {{-- Product Detail Section --}}
    <section class="container py-4">
        <div class="row">
            {{-- Product Images --}}
            <div class="col-md-6 mb-4">
                <div class="product-image-main mb-3">
                    <img src="{{ asset('image/'.$product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="img-fluid rounded shadow-sm w-100"
                         style="max-height: 500px; object-fit: cover;">
                </div>
            </div>

            {{-- Product Information --}}
            <div class="col-md-6">
                <div class="product-info">
                    {{-- Category Badge --}}
                    <span class="badge badge-primary mb-2">{{ $product->category ?? 'General' }}</span>
                    
                    {{-- Product Name --}}
                    <h1 class="h2 font-weight-bold mb-3">{{ $product->name }}</h1>
                    
                    {{-- Price --}}
                    <div class="price-section mb-3">
                        <span class="h3 text-dark">${{ number_format($product->price, 2) }}</span>
                        @if(isset($product->compare_at) && $product->compare_at > $product->price)
                            <span class="h5 text-muted text-decoration-line-through ml-2">${{ number_format($product->compare_at, 2) }}</span>
                            <span class="badge badge-danger ml-2">Save ${{ number_format($product->compare_at - $product->price, 2) }}</span>
                        @endif
                    </div>

                    {{-- Stock Status --}}
                    <div class="stock-status mb-3">
                        @if($product->stock > 0)
                            <span class="text-success">
                                <i class="fas fa-check-circle"></i> In Stock ({{ $product->stock }} available)
                            </span>
                        @else
                            <span class="text-danger">
                                <i class="fas fa-times-circle"></i> Out of Stock
                            </span>
                        @endif
                    </div>

                    {{-- Description --}}
                    <div class="description mb-4">
                        <p class="text-muted">{{ $product->description }}</p>
                    </div>

                    {{-- Quantity Selector --}}
                    <div class="quantity-selector mb-4">
                        <label class="font-weight-bold mb-2">Quantity:</label>
                        <div class="d-flex align-items-center">
                            <button class="btn btn-outline-secondary rounded-circle" 
                                id="decreaseQty" 
                                style="width: 40px; height: 40px;"
                                title="Decrease quantity"
                                aria-label="Decrease quantity">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" 
                                   id="quantity" 
                                   name="quantity" 
                                   value="1" 
                                   min="1" 
                                   max="{{ $product->stock }}"
                                   class="form-control text-center mx-2" 
                                   style="width: 80px;"
                                   title="Enter desired quantity"
                                   aria-label="Product quantity"
                            >
                            <button class="btn btn-outline-secondary rounded-circle" 
                                id="increaseQty" 
                                style="width: 40px; height: 40px;"
                                title="Increase quantity"
                                aria-label="Increase quantity">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="action-buttons mb-4">
                        <div class="row">
                            <div class="col-6">
                                @if($product->stock > 0)
                                    <button class="btn btn-outline-dark btn-lg w-100 mb-2"
                                            @click.prevent="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})">
                                        <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                                    </button>
                                @else
                                    <button class="btn btn-secondary btn-lg w-100 mb-2" disabled>
                                        <i class="fas fa-times mr-2"></i>Out of Stock
                                    </button>
                                @endif
                            </div>
                            <div class="col-6">
                                <a href="{{ route('cart_index') }}" class="btn btn-dark btn-lg w-100 mb-2"
                                   @if($product->stock == 0) aria-disabled="true" tabindex="-1" style="pointer-events: none; opacity: 0.7;" @endif>
                                    <i class="fas fa-bolt mr-2"></i>Buy Now
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Additional Info --}}
                    <div class="additional-info">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="border rounded p-2">
                                    <i class="fas fa-shipping-fast text-primary mb-1"></i>
                                    <small class="d-block">Free Shipping</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded p-2">
                                    <i class="fas fa-undo-alt text-success mb-1"></i>
                                    <small class="d-block">30-Day Returns</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded p-2">
                                    <i class="fas fa-shield-alt text-warning mb-1"></i>
                                    <small class="d-block">1 Year Warranty</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Product Details Tabs --}}
    <section class="container py-4">
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="description-tab" data-toggle="tab" href="#description" role="tab">
                            Description
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="specifications-tab" data-toggle="tab" href="#specifications" role="tab">
                            Specifications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab">
                            Reviews (24)
                        </a>
                    </li>
                </ul>
                
                <div class="tab-content p-3 border border-top-0 rounded-bottom" id="productTabsContent">
                    <div class="tab-pane fade show active" id="description" role="tabpanel">
                        <p>{{ $product->description }}</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success mr-2"></i> High-quality materials</li>
                            <li><i class="fas fa-check text-success mr-2"></i> Eco-friendly packaging</li>
                            <li><i class="fas fa-check text-success mr-2"></i> Easy to maintain</li>
                            <li><i class="fas fa-check text-success mr-2"></i> Customer satisfaction guaranteed</li>
                        </ul>
                    </div>
                    
                    <div class="tab-pane fade" id="specifications" role="tabpanel">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td><strong>Category</strong></td>
                                    <td>{{ $product->category ?? 'General' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Material</strong></td>
                                    <td>Premium Quality</td>
                                </tr>
                                <tr>
                                    <td><strong>Weight</strong></td>
                                    <td>2.5 kg</td>
                                </tr>
                                <tr>
                                    <td><strong>Dimensions</strong></td>
                                    <td>30 x 20 x 15 cm</td>
                                </tr>
                                <tr>
                                    <td><strong>Warranty</strong></td>
                                    <td>1 Year</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="tab-pane fade" id="reviews" role="tabpanel">
                        <div class="review-summary mb-4">
                            <h5>Customer Reviews</h5>
                            <div class="d-flex align-items-center">
                                <span class="h3 mr-2">4.8</span>
                                <div class="star-rating mr-3">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star-half-alt text-warning"></i>
                                </div>
                                <span class="text-muted">Based on 24 reviews</span>
                            </div>
                        </div>
                        
                        <div class="review-item border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <strong>John Doe</strong>
                                <small class="text-muted">2 days ago</small>
                            </div>
                            <div class="star-rating mb-2">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <p>Excellent product! Great quality and fast delivery.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Related Products --}}
    <section class="container py-5 bg-light">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="h3 font-weight-bold">Related Products</h2>
                <p class="text-muted">You might also like these products</p>
            </div>
        </div>
        <div class="row">
            @forelse($related_products as $related)
            <div class="col-6 col-md-3 mb-4">
                <div class="card border-0 shadow-sm h-100 product-card">
                    <a href="{{ route('product.detail', $related->id) }}" class="text-decoration-none text-reset">
                        <div class="position-relative">
                            <img src="{{ asset('image/'.$related->image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $related->name }}" 
                                 style="height: 200px; object-fit: cover;">
                            <button class="btn btn-light btn-sm position-absolute top-0 right-0 m-2 rounded-circle favorite-btn">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                    </a>
                    <div class="card-body">
                        <h6 class="card-title mb-2">{{ $related->name }}</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h6 mb-0 text-dark">${{ number_format($related->price, 2) }}</span>
                            <button class="btn btn-dark btn-sm rounded-pill related-add-to-cart" 
                                    data-product-id="{{ $related->id }}"
                                    data-product-name="{{ addslashes($related->name) }}"
                                    data-product-price="{{ $related->price }}">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p class="text-muted">No related products found.</p>
            </div>
            @endforelse
        </div>
    </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity Selector
    const quantityInput = document.getElementById('quantity');
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const addToCartBtn = document.getElementById('addToCartBtn');

    // Decrease quantity
    decreaseBtn.addEventListener('click', function() {
        let currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
            updateButtonStates();
            // Add visual feedback
            this.classList.add('btn-success');
            setTimeout(() => {
                this.classList.remove('btn-success');
            }, 200);
        } else {
            // Visual feedback when can't decrease further
            this.classList.add('btn-danger');
            setTimeout(() => {
                this.classList.remove('btn-danger');
            }, 200);
        }
    });


    

    // Increase quantity
    increaseBtn.addEventListener('click', function() {
        let currentValue = parseInt(quantityInput.value);
        let maxStock = parseInt(quantityInput.max);
        if (currentValue < maxStock) {
            quantityInput.value = currentValue + 1;
            updateButtonStates();
            // Add visual feedback
            this.classList.add('btn-success');
            setTimeout(() => {
                this.classList.remove('btn-success');
            }, 200);
        } else {
            // Show alert when maximum stock reached
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Maximum Stock Reached',
                    text: `Only ${maxStock} units available in stock.`,
                    timer: 2000,
                    showConfirmButton: false
                });
            }
            // Visual feedback when can't increase further
            this.classList.add('btn-danger');
            setTimeout(() => {
                this.classList.remove('btn-danger');
            }, 200);
        }
    });

    // Validate quantity input
    quantityInput.addEventListener('change', function() {
        let value = parseInt(this.value);
        let maxStock = parseInt(this.max);
        
        if (isNaN(value) || value < 1) {
            this.value = 1;
        } else if (value > maxStock) {
            this.value = maxStock;
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Maximum Stock Reached',
                    text: `Only ${maxStock} units available in stock.`,
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        }
    });

    // Keyboard support for quantity input
    quantityInput.addEventListener('keydown', function(e) {
        // Allow arrow keys for increment/decrement
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            increaseBtn.click();
        } else if (e.key === 'ArrowDown') {
            e.preventDefault();
            decreaseBtn.click();
        }
    });

    // Function to update button states
    function updateButtonStates() {
        const currentValue = parseInt(quantityInput.value);
        const maxStock = parseInt(quantityInput.max);
        
        // Update decrease button
        if (currentValue <= 1) {
            decreaseBtn.classList.add('opacity-50');
            decreaseBtn.style.pointerEvents = 'none';
        } else {
            decreaseBtn.classList.remove('opacity-50');
            decreaseBtn.style.pointerEvents = 'auto';
        }
        
        // Update increase button
        if (currentValue >= maxStock) {
            increaseBtn.classList.add('opacity-50');
            increaseBtn.style.pointerEvents = 'none';
        } else {
            increaseBtn.classList.remove('opacity-50');
            increaseBtn.style.pointerEvents = 'auto';
        }
    }

    // Call updateButtonStates when quantity changes
    quantityInput.addEventListener('input', updateButtonStates);
    quantityInput.addEventListener('change', updateButtonStates);
    
    // Initial call to set correct button states
    updateButtonStates();

    // Add to cart functionality (only if button exists and product is in stock)
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
        // Disable button to prevent double-clicks
        this.disabled = true;
        
        // Store original button content
        const originalContent = this.innerHTML;
        
        // Show loading state
        this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding...';
        this.classList.add('btn-secondary');
        this.classList.remove('btn-outline-dark');
        
        const productId = this.getAttribute('data-product-id');
        const productName = this.getAttribute('data-product-name');
        const productPrice = this.getAttribute('data-product-price');
        const quantity = parseInt(quantityInput.value);
        
        // Validate stock before adding
        const maxStock = parseInt(quantityInput.max);
        if (quantity > maxStock) {
            // Reset button state
            this.disabled = false;
            this.innerHTML = originalContent;
            this.classList.remove('btn-secondary');
            this.classList.add('btn-outline-dark');
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Insufficient Stock',
                    text: `Only ${maxStock} units available. Please adjust the quantity.`,
                    confirmButtonText: 'OK'
                });
            }
            return;
        }

        // Call the add to cart function with callback to reset button
        addToCartWithQuantity(productId, productName, productPrice, quantity, () => {
            // Reset button state after completion
            this.disabled = false;
            this.innerHTML = originalContent;
            this.classList.remove('btn-secondary');
            this.classList.add('btn-outline-dark');
        });
    });
    }

    // Enhanced add to cart function with quantity
    function addToCartWithQuantity(product_id, product_name, product_price, quantity, callback = null) {
        let url = "{{ url('/cart/add-to-cart') }}";
        
        // Show loading overlay if available
        if (typeof $.LoadingOverlay === "function") {
            $.LoadingOverlay("show");
        }
        
        // Use axios or fetch to make the request
        if (typeof axios !== 'undefined') {
            axios.post(url, {
                product_id: product_id,
                quantity: quantity
            }, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(function (response) {
                console.log(response);
                showSuccessAlert(quantity, product_name);
                
                // Update cart count in navigation if possible
                updateCartCount();
            })
            .catch(function (error) {
                console.log(error);
                showErrorAlert();
            }).finally(function () {
                if (typeof $.LoadingOverlay === "function") {
                    $.LoadingOverlay("hide");
                }
                
                // Execute callback if provided (to reset button state)
                if (callback && typeof callback === 'function') {
                    callback();
                }
            });
        } else {
            // Fallback to fetch API
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: product_id,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                showSuccessAlert(quantity, product_name);
                updateCartCount();
            })
            .catch(error => {
                console.log(error);
                showErrorAlert();
            })
            .finally(() => {
                if (typeof $.LoadingOverlay === "function") {
                    $.LoadingOverlay("hide");
                }
                
                // Execute callback if provided (to reset button state)
                if (callback && typeof callback === 'function') {
                    callback();
                }
            });
        }
    }

    // Update cart count in navigation
    function updateCartCount() {
        // Try to update cart badge in navigation
        const cartBadge = document.querySelector('.badge-danger');
        if (cartBadge) {
            let currentCount = parseInt(cartBadge.textContent) || 0;
            cartBadge.textContent = currentCount + 1;
            
            // Add animation to cart badge
            cartBadge.classList.add('animate__animated', 'animate__bounce');
            setTimeout(() => {
                cartBadge.classList.remove('animate__animated', 'animate__bounce');
            }, 1000);
        }
    }

    // Show success alert with enhanced options
    function showSuccessAlert(quantity, productName) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: "success",
                title: "Added to Cart!",
                html: `<strong>${quantity} × ${productName}</strong> has been added to your cart.`,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-shopping-cart"></i> View Cart',
                cancelButtonText: '<i class="fas fa-shopping-bag"></i> Continue Shopping',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to cart page
                    window.location.href = "{{ route('cart_index') }}";
                }
            });
        } else {
            // Fallback to basic alert with confirmation
            const viewCart = confirm(quantity + " " + productName + " added to cart successfully!\n\nWould you like to view your cart?");
            if (viewCart) {
                window.location.href = "{{ route('cart_index') }}";
            }
        }
    }

    // Show error alert
    function showErrorAlert() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Failed to add product to cart. Please try again."
            });
        } else {
            alert("Failed to add product to cart. Please try again.");
        }
    }

    // Related products add to cart functionality
    document.querySelectorAll('.related-add-to-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            const productPrice = this.getAttribute('data-product-price');
            
            addToCartWithQuantity(productId, productName, productPrice, 1);
        });
    });

    // Global addToCart function for compatibility (if needed elsewhere)
    window.addToCart = function(product_id, product_name, product_price) {
        addToCartWithQuantity(product_id, product_name, product_price, 1);
    };

    // Favorite button functionality
    document.querySelectorAll('.favorite-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const heartIcon = this.querySelector('i');
            if (heartIcon.classList.contains('far')) {
                heartIcon.classList.remove('far');
                heartIcon.classList.add('fas', 'text-danger');
            } else {
                heartIcon.classList.remove('fas', 'text-danger');
                heartIcon.classList.add('far');
            }
        });
    });
});
</script>
@endpush

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