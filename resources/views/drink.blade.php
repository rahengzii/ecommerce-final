@extends('master')
@section('title','Drinks — Sample Store')
@section('content')
    {{-- Hero Section --}}
    <section class="container-fluid py-5 bg-info">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 font-weight-bold text-white mb-4">Refreshing Drinks</h1>
                    <p class="lead text-white mb-4">
                        Quench your thirst with our premium selection of beverages. 
                        From hot coffees to cold refreshments, we have something for every taste.
                    </p>
                    <div class="d-flex flex-wrap">
                        <span class="badge badge-light badge-pill py-2 px-3 mb-2 mr-2">Coffee</span>
                        <span class="badge badge-light badge-pill py-2 px-3 mb-2 mr-2">Tea</span>
                        <span class="badge badge-light badge-pill py-2 px-3 mb-2 mr-2">Smoothies</span>
                        <span class="badge badge-light badge-pill py-2 px-3 mb-2 mr-2">Juices</span>
                        <span class="badge badge-light badge-pill py-2 px-3 mb-2 mr-2">Soft Drinks</span>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="https://via.placeholder.com/600x400/4fc3f7/ffffff?text=Refreshing+Drinks" 
                         alt="Refreshing Drinks" 
                         class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    {{-- All Drink Products --}}
<section class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h2 class="h1 font-weight-bold mb-3">All Drink Products</h2>
            <p class="lead text-muted">Discover our complete beverage collection</p>
        </div>
    </div>
    <div class="row">
        @forelse($drink_products->where('category_name', 'Drink') as $product)
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
                    <p class="text-muted small mb-2">{{ $product->category_name ?? 'Drink' }}</p>
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="price-section">
                                <span class="h5 text-dark">${{ number_format($product->price, 2) }}</span>
                            </div>
                            <button class="btn btn-info btn-sm rounded-pill" 
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
                <i class="fas fa-glass-whiskey fa-2x mb-3"></i>
                <h5>No drink products available</h5>
                <p class="mb-0">Check back later for new drink arrivals!</p>
            </div>
        </div>
        @endforelse
    </div>
</section>

    {{-- Drink Categories Grid --}}
    <section class="container py-5 bg-light">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="h1 font-weight-bold mb-3">Drink Categories</h2>
                <p class="lead text-muted">Explore by category</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="category-card card border-0 shadow-sm text-white overflow-hidden">
                    <img src="https://via.placeholder.com/400x300/8d6e63/ffffff?text=Coffee" 
                         class="card-img" 
                         alt="Coffee"
                         style="height: 200px; object-fit: cover;">
                    <div class="card-img-overlay d-flex align-items-center justify-content-center" 
                         style="background: rgba(0,0,0,0.4);">
                        <div class="text-center">
                            <h4 class="card-title">Coffee</h4>
                            <p class="card-text">Brewed to perfection</p>
                            <a href="#" class="btn btn-light btn-sm">Shop Coffee</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="category-card card border-0 shadow-sm text-white overflow-hidden">
                    <img src="https://via.placeholder.com/400x300/78909c/ffffff?text=Tea" 
                         class="card-img" 
                         alt="Tea"
                         style="height: 200px; object-fit: cover;">
                    <div class="card-img-overlay d-flex align-items-center justify-content-center" 
                         style="background: rgba(0,0,0,0.4);">
                        <div class="text-center">
                            <h4 class="card-title">Tea</h4>
                            <p class="card-text">Soothing blends</p>
                            <a href="#" class="btn btn-light btn-sm">Explore Tea</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="category-card card border-0 shadow-sm text-white overflow-hidden">
                    <img src="https://via.placeholder.com/400x300/66bb6a/ffffff?text=Smoothies" 
                         class="card-img" 
                         alt="Smoothies"
                         style="height: 200px; object-fit: cover;">
                    <div class="card-img-overlay d-flex align-items-center justify-content-center" 
                         style="background: rgba(0,0,0,0.4);">
                        <div class="text-center">
                            <h4 class="card-title">Smoothies</h4>
                            <p class="card-text">Fresh & nutritious</p>
                            <a href="#" class="btn btn-light btn-sm">Discover</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="category-card card border-0 shadow-sm text-white overflow-hidden">
                    <!-- <img src="https://via.placeholder.com/400x300/ffb74d/ffffff?text=Juices" 
                         class="card-img" 
                         alt="Juices"
                         style="height: 200px; object-fit: cover;"> -->
                    <div class="card-img-overlay d-flex align-items-center justify-content-center" 
                         style="background: rgba(0,0,0,0.4);">
                        <div class="text-center">
                            <h4 class="card-title">Juices</h4>
                            <p class="card-text">Refreshing drinks</p>
                            <a href="#" class="btn btn-light btn-sm">Browse</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Drink Combos --}}
    <section class="container py-5">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="h1 font-weight-bold mb-3">Drink Combos</h2>
                <p class="lead text-muted">Save more with our specially curated combos</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card border-0 bg-warning text-dark shadow-lg">
                    <div class="card-body text-center p-4">
                        <h4 class="font-weight-bold">Morning Boost</h4>
                        <p class="mb-3">Coffee + Breakfast Sandwich</p>
                        <h3 class="font-weight-bold text-danger">$12.99</h3>
                        <p class="small text-muted">Save $3.00</p>
                        <button class="btn btn-dark btn-sm rounded-pill">Add Combo</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 bg-success text-white shadow-lg">
                    <div class="card-body text-center p-4">
                        <h4 class="font-weight-bold">Afternoon Refresh</h4>
                        <p class="mb-3">Smoothie + Energy Bar</p>
                        <h3 class="font-weight-bold">$10.99</h3>
                        <p class="small">Save $2.50</p>
                        <button class="btn btn-light btn-sm rounded-pill">Add Combo</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 bg-info text-white shadow-lg">
                    <div class="card-body text-center p-4">
                        <h4 class="font-weight-bold">Evening Relax</h4>
                        <p class="mb-3">Tea + Cookies</p>
                        <h3 class="font-weight-bold">$8.99</h3>
                        <p class="small">Save $2.00</p>
                        <button class="btn btn-light btn-sm rounded-pill">Add Combo</button>
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
        .then(function (response) {
            showSuccessAlert(1, product_name);
        })
        .catch(function (error) {
            console.log(error);
            showErrorAlert();
        }).finally(function () {
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
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.product-card a:hover h5 {
    color: #007bff !important;
}

.bg-info {
    background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%) !important;
}

.btn-info {
    background-color: #29b6f6;
    border-color: #29b6f6;
}

.btn-info:hover {
    background-color: #0288d1;
    border-color: #0288d1;
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