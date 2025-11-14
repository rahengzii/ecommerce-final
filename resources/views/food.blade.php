@extends('master')
@section('title','Food — Sample Store')
@section('content')
{{-- Hero Section --}}
<section class="container-fluid py-5 bg-warning">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 font-weight-bold text-white mb-4">Delicious Food</h1>
                <p class="lead text-white mb-4">
                    Discover our mouth-watering selection of freshly prepared meals,
                    snacks, and gourmet treats. From quick bites to full meals, we've got you covered.
                </p>
                <div class="d-flex flex-wrap">
                    <span class="badge badge-light badge-pill py-2 px-3 mb-2 mr-2">Pizza</span>
                    <span class="badge badge-light badge-pill py-2 px-3 mb-2 mr-2">Burgers</span>
                    <span class="badge badge-light badge-pill py-2 px-3 mb-2 mr-2">Pasta</span>
                    <span class="badge badge-light badge-pill py-2 px-3 mb-2 mr-2">Salads</span>
                    <span class="badge badge-light badge-pill py-2 px-3 mb-2 mr-2">Desserts</span>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://via.placeholder.com/600x400/ff6b6b/ffffff?text=Delicious+Food"
                    alt="Delicious Food"
                    class="img-fluid rounded shadow-lg">
            </div>
        </div>
    </div>
</section>

{{-- All Food Products --}}
<section class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h2 class="h1 font-weight-bold mb-3">All Food Products</h2>
            <p class="lead text-muted">Discover our complete food collection</p>
        </div>
    </div>
    <div class="row">
        @forelse($food_products as $product)
        <div class="col-6 col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100 product-card">
                <a href="{{ route('product.detail', $product->id) }}" class="text-decoration-none text-reset">
                    <div class="position-relative">
                        <img src="{{ asset('image/'.$product->image) }}"
                            class="card-img-top"
                            alt="{{ $product->name }}"
                            style="height: 200px; object-fit: cover;">
                    </div>
                </a>
                <div class="card-body d-flex flex-column">
                    <a href="{{ route('product.detail', $product->id) }}" class="text-decoration-none text-reset">
                        <h5 class="card-title mb-2">{{ $product->name }}</h5>
                    </a>
                    <p class="text-muted small mb-2">{{ $product->category_name ?? 'Food' }}</p>
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="price-section">
                                <span class="h5 text-dark">${{ number_format($product->price, 2) }}</span>
                            </div>
                            <button class="btn btn-warning btn-sm rounded-pill"
                                 @click.prevent="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-secondary text-center mb-0">
                <i class="fas fa-utensils fa-2x mb-3"></i>
                <h5>No food products available</h5>
                <p class="mb-0">Check back later for new food arrivals!</p>
            </div>
        </div>
        @endforelse
    </div>
</section>

{{-- Food Categories Grid --}}
<section class="container py-5 bg-light">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h2 class="h1 font-weight-bold mb-3">Food Categories</h2>
            <p class="lead text-muted">Explore by category</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="category-card card border-0 shadow-sm text-white overflow-hidden">
                <img src="https://via.placeholder.com/400x300/ff6b6b/ffffff?text=Pizza"
                    class="card-img"
                    alt="Pizza"
                    style="height: 250px; object-fit: cover;">
                <div class="card-img-overlay d-flex align-items-center justify-content-center"
                    style="background: rgba(0,0,0,0.4);">
                    <div class="text-center">
                        <h3 class="card-title">Pizza</h3>
                        <p class="card-text">Freshly baked & delicious</p>
                        <a href="#" class="btn btn-light">Shop Pizza</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="category-card card border-0 shadow-sm text-white overflow-hidden">
                <img src="https://via.placeholder.com/400x300/ffa726/ffffff?text=Burgers"
                    class="card-img"
                    alt="Burgers"
                    style="height: 250px; object-fit: cover;">
                <div class="card-img-overlay d-flex align-items-center justify-content-center"
                    style="background: rgba(0,0,0,0.4);">
                    <div class="text-center">
                        <h3 class="card-title">Burgers</h3>
                        <p class="card-text">Juicy & flavorful</p>
                        <a href="#" class="btn btn-light">Explore Burgers</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="category-card card border-0 shadow-sm text-white overflow-hidden">
                <img src="https://via.placeholder.com/400x300/66bb6a/ffffff?text=Pasta"
                    class="card-img"
                    alt="Pasta"
                    style="height: 250px; object-fit: cover;">
                <div class="card-img-overlay d-flex align-items-center justify-content-center"
                    style="background: rgba(0,0,0,0.4);">
                    <div class="text-center">
                        <h3 class="card-title">Pasta</h3>
                        <p class="card-text">Italian classics</p>
                        <a href="#" class="btn btn-light">Discover Pasta</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Special Offers --}}
<section class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h2 class="h1 font-weight-bold mb-3">Special Offers</h2>
            <p class="lead text-muted">Don't miss these amazing deals</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 bg-danger text-white shadow-lg">
                <div class="card-body p-4">
                    <h3 class="h2 font-weight-bold">Family Feast</h3>
                    <p class="mb-3">Get 20% off on all family-sized meals</p>
                    <span class="badge badge-light badge-pill py-2 px-3">Use code: FAMILY20</span>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card border-0 bg-success text-white shadow-lg">
                <div class="card-body p-4">
                    <h3 class="h2 font-weight-bold">Weekend Special</h3>
                    <p class="mb-3">Buy 1 Get 1 Free on all pizzas</p>
                    <span class="badge badge-light badge-pill py-2 px-3">Weekends Only</span>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function addToCart(product_id, product_name, product_price) {
        let url = "{{ url('/cart/add-to-cart') }}";

        if (typeof $.LoadingOverlay === "function") {
            $.LoadingOverlay("show");
        }

        if (typeof axios !== 'undefined') {
            axios.post(url, {
                    product_id: product_id,
                    quantity: 1
                })
                .then(function(response) {
                    showSuccessAlert(1, product_name);
                })
                .catch(function(error) {
                    console.log(error);
                    showErrorAlert();
                }).finally(function() {
                    if (typeof $.LoadingOverlay === "function") {
                        $.LoadingOverlay("hide");
                    }
                });
        } else {
            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: product_id,
                        quantity: 1
                    })
                })
                .then(response => response.json())
                .then(data => {
                    showSuccessAlert(1, product_name);
                })
                .catch(error => {
                    console.log(error);
                    showErrorAlert();
                })
                .finally(() => {
                    if (typeof $.LoadingOverlay === "function") {
                        $.LoadingOverlay("hide");
                    }
                });
        }
    }

    function showSuccessAlert(quantity, productName) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: quantity + " " + productName + " added to cart successfully!",
                showConfirmButton: false,
                timer: 1500
            });
        } else {
            alert(quantity + " " + productName + " added to cart successfully!");
        }
    }

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

    // Product card hover effects
    document.addEventListener('DOMContentLoaded', function() {
        // Make entire product card clickable (except add to cart button)
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (!e.target.closest('button') && !e.target.closest('a')) {
                    const link = this.querySelector('a');
                    if (link) {
                        link.click();
                    }
                }
            });
        });
    });
</script>
@endpush

@push('styles')
<style>
    .category-card {
        transition: transform 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
    }

    .category-card:hover {
        transform: scale(1.05);
    }

    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
        cursor: pointer;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    }

    .product-card a:hover h5 {
        color: #007bff !important;
    }

    .bg-warning {
        background: linear-gradient(135deg, #ffa726 0%, #ff6b6b 100%) !important;
    }

    .btn-warning {
        background-color: #ffa726;
        border-color: #ffa726;
    }

    .btn-warning:hover {
        background-color: #ff9800;
        border-color: #ff9800;
    }

    .text-reset {
        color: inherit !important;
    }

    .text-reset:hover {
        color: inherit !important;
        text-decoration: none;
    }
</style>
@endpush