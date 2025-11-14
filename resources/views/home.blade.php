@extends('master')
@section('title','Home — Sample Store')
@section('content')


{{-- Hero Slider --}}
<section class="container-fluid px-0">
    <div id="heroSlider" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="hero-slide d-flex align-items-center position-relative" style="background: linear-gradient(135deg, #000000 0%, #000000 100%); min-height: 500px;">
                    <!-- Background image with 50% opacity -->
                    <img src="{{ asset('image/summer.jpg') }}" alt="Summer Collection"
                        class="position-absolute w-100 h-100"
                        style="object-fit: cover; left: 0; top: 0; opacity: 0.5; z-index: 1;">
                    <div class="container text-white text-center py-5 position-relative" style="z-index: 2;">
                        <h1 class="display-4 font-weight-bold mb-3">Summer Collection</h1>
                        <p class="lead mb-4">Discover the latest trends with up to 50% off</p>
                        <a href="#featured" class="btn btn-light btn-lg rounded-pill px-4">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="hero-slide d-flex align-items-center position-relative" style="background: linear-gradient(135deg, #000000 0%, #000000 100%); min-height: 500px;">
                    <!-- Background image with 50% opacity -->
                    <img src="{{ asset('image/new.jpg') }}" alt="New Arrivals"
                        class="position-absolute w-100 h-100"
                        style="object-fit: cover; left: 0; top: 0; opacity: 0.5; z-index: 1;">
                    <div class="container text-white text-center py-5 position-relative" style="z-index: 2;">
                        <h1 class="display-4 font-weight-bold mb-3">New Arrivals</h1>
                        <p class="lead mb-4">Fresh picks curated just for you</p>
                        <a href="#trending" class="btn btn-light btn-lg rounded-pill px-4">Explore</a>
                    </div>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#heroSlider" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#heroSlider" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</section>


{{-- Trending Products --}}
<section id="trending" class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h2 class="h1 font-weight-bold mb-3">Trending Products</h2>
            <p class="lead text-muted">Top view in this week</p>
        </div>
    </div>
    <div class="row">
        @foreach($trending_products as $product)
        <div class="col-6 col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100 product-card">
                <a href="{{ route('product.detail', $product->id) }}" class="text-decoration-none">
                    <div class="position-relative">
                        <img src="{{ asset('image/'.$product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                    </div>
                </a>
                <div class="card-body">
                    <a href="{{ route('product.detail', $product->id) }}" class="text-decoration-none text-dark">
                        <h5 class="card-title mb-2">{{ $product->name }}</h5>
                    </a>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5 mb-0 text-dark">${{ number_format($product->price, 2) }}</span>
                        <button class="btn btn-dark btn-sm rounded-pill"
                            @click.prevent="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- Best Seller Products --}}
<section class="container py-5 bg-light">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h2 class="h1 font-weight-bold mb-3">Best Seller Products</h2>
            <p class="lead text-muted">Top side in this week</p>
        </div>
    </div>
    <div class="row">
        @foreach($best_seller_products as $product)
        <div class="col-6 col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100 product-card">
                <a href="{{ route('product.detail', $product->id) }}" class="text-decoration-none">
                    <div class="position-relative">
                        <img src="{{ asset('image/'.$product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                        @if(isset($product->discount_price))
                        <span class="badge badge-danger position-absolute top-0 left-0 m-2">SALE</span>
                        @endif
                        <!-- <button class="btn btn-light btn-sm position-absolute top-0 right-0 m-2 rounded-circle favorite-btn">
                                <i class="fas fa-heart"></i>
                            </button> -->
                    </div>
                </a>
                <div class="card-body">
                    <a href="{{ route('product.detail', $product->id) }}" class="text-decoration-none text-dark">
                        <h5 class="card-title mb-2">{{ $product->name }}</h5>
                    </a>
                    <div class="price-section mb-2">
                        @if(isset($product->discount_price))
                        <span class="h5 text-dark">${{ number_format($product->discount_price, 2) }}</span>
                        <span class="text-muted text-decoration-line-through ml-2">${{ number_format($product->price, 2) }}</span>
                        @else
                        <span class="h5 text-dark">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>
                    <button class="btn btn-dark btn-sm rounded-pill w-100"
                        @click.prevent="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- Categories Grid --}}
<section class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h2 class="h1 font-weight-bold mb-3">Shop by Category</h2>
            <p class="lead text-muted">Explore our collections</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="category-card card border-0 shadow-sm text-white overflow-hidden">
                <img src="https://via.placeholder.com/400x300/667eea/ffffff?text=Furniture" class="card-img" alt="Furniture" style="height: 250px; object-fit: cover;">
                <div class="card-img-overlay d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.4);">
                    <div class="text-center">
                        <h3 class="card-title">Furniture</h3>
                        <p class="card-text">Modern & Classic Designs</p>
                        <a href="#" class="btn btn-light">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="category-card card border-0 shadow-sm text-white overflow-hidden">
                <img src="https://via.placeholder.com/400x300/f093fb/ffffff?text=Home+Decor" class="card-img" alt="Home Decor" style="height: 250px; object-fit: cover;">
                <div class="card-img-overlay d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.4);">
                    <div class="text-center">
                        <h3 class="card-title">Home Decor</h3>
                        <p class="card-text">Beautiful Home Accessories</p>
                        <a href="#" class="btn btn-light">Explore</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="category-card card border-0 shadow-sm text-white overflow-hidden">
                <img src="https://via.placeholder.com/400x300/4facfe/ffffff?text=Kitchen" class="card-img" alt="Kitchen" style="height: 250px; object-fit: cover;">
                <div class="card-img-overlay d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.4);">
                    <div class="text-center">
                        <h3 class="card-title">Kitchen</h3>
                        <p class="card-text">Essential Kitchen Tools</p>
                        <a href="#" class="btn btn-light">Discover</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Featured Products --}}
<section id="featured" class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h2 class="h1 font-weight-bold mb-3">Featured Products</h2>
            <p class="lead text-muted">Carefully selected for you</p>
        </div>
    </div>
    <div class="row justify-content-center">
        @forelse($featured_products as $product)
        <div class="col-6 col-md-4 col-lg-3 mb-4">
            <div class="card border-0 shadow-sm h-100 product-card">
                <a href="{{ route('product.detail', $product->id) }}" class="text-decoration-none">
                    <div class="position-relative">
                        <img src="{{ asset('image/'.$product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                        <button class="btn btn-light btn-sm position-absolute top-0 right-0 m-2 rounded-circle favorite-btn">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </a>
                <div class="card-body d-flex flex-column">
                    <a href="{{ route('product.detail', $product->id) }}" class="text-decoration-none text-dark">
                        <h6 class="card-title mb-1">{{ $product->name }}</h6>
                    </a>
                    <p class="text-muted small mb-2">{{ $product->category ?? 'General' }}</p>
                    <div class="mt-auto d-flex align-items-center justify-content-between">
                        <div class="h5 mb-0 text-dark">${{ number_format($product->price, 2) }}</div>
                        <button class="btn btn-dark btn-sm rounded-pill"
                            @click.prevent="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})">
                            <i class="fa-solid fa-plus"></i> Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-secondary text-center mb-0">
                <i class="fas fa-box-open fa-2x mb-3"></i>
                <h5>No featured products available</h5>
                <p class="mb-0">Check back later for new arrivals!</p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- View All Products Button --}}
    @if(count($featured_products) > 0)
    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="{{ route('all_products') }}" class="btn btn-outline-dark btn-lg rounded-pill px-5">
                <i class="fas fa-grid mr-2"></i>View All Products
            </a>
        </div>
    </div>
    @endif
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize carousel
        $('#heroSlider').carousel({
            interval: 5000
        });

        // Favorite button functionality
        $('.favorite-btn').click(function(e) {
            e.preventDefault();
            const heartIcon = $(this).find('i');
            if (heartIcon.hasClass('far')) {
                heartIcon.removeClass('far').addClass('fas text-danger');
            } else {
                heartIcon.removeClass('fas text-danger').addClass('far');
            }
        });

        // Search functionality
        $('.search-btn').click(function() {
            const searchTerm = $('.search-input').val().trim();
            if (searchTerm) {
                alert('Searching for: ' + searchTerm);
            }
        });

        $('.search-input').keypress(function(e) {
            if (e.which === 13) {
                $('.search-btn').click();
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    .navbar-brand {
        font-size: 1.5rem;
    }

    .navbar-nav .nav-item {
        margin: 0 0.5rem;
    }

    .navbar-nav .nav-link {
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .hero-slide {
        min-height: 500px;
        background-size: cover;
        background-position: center;
    }




    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    }

    .category-card {
        transition: transform 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
    }

    .category-card:hover {
        transform: scale(1.05);
    }

    .card-img-overlay {
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
    }

    .favorite-btn {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .favorite-btn:hover {
        background-color: #fff !important;
        transform: scale(1.1);
    }

    .price-section .text-decoration-line-through {
        font-size: 0.9rem;
    }

    .badge {
        font-size: 0.7rem;
        padding: 0.4em 0.6em;
    }

    .btn-rounded {
        border-radius: 25px;
    }

    .sticky-top {
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        z-index: 1020;
    }

    .product-card a:hover h5 {
        color: #007bff !important;
    }

    .product-card .card-img {
        transition: transform 0.3s ease;
    }

    .product-card:hover .card-img {
        transform: scale(1.05);
    }

    @media (max-width: 768px) {
        .hero-slide {
            min-height: 400px;
        }

        .display-4 {
            font-size: 2rem;
        }
    }
</style>
@endpush