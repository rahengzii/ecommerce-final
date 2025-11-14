<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Sample Store')</title>

    {{-- Bootstrap 4.6 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    {{-- Theme --}}
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>

<body class="bg-light d-flex flex-column min-vh-100">
    <div id="app" class="d-flex flex-column min-vh-100">
        {{-- Update this section in master.blade.php --}}
        <!-- <section class="py-4 hero bg-white border-bottom">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="display-5 mb-3">New arrivals</h1>
                        <p class="lead text-secondary mb-0">Fresh picks curated for you. Simple, stylish, fast.</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        {{-- Auth Links --}}
                        @if(session('logged_in'))
                        <div class="dropdown">
                            <button class="btn btn-outline-dark dropdown-toggle" type="button" id="userDropdown" data-toggle="dropdown">
                                <i class="fas fa-user mr-2"></i>{{ session('customer_name') }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user mr-2"></i>My Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('cart_index') }}">
                                    <i class="fas fa-shopping-cart mr-2"></i>My Cart
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                        @else
                        <div class="d-flex gap-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-dark">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-dark">
                                <i class="fas fa-user-plus mr-2"></i>Register
                            </a>
                        </div>
                        @endif

                        {{-- Cart Button --}}
                        <a href="{{ route('cart_index') }}" class="btn btn-outline-dark position-relative">
                            <i class="fas fa-shopping-cart"></i>
                            @if(isset($cartCount) && $cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge badge-danger">
                                {{ $cartCount }}
                            </span>
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        </section> -->

        {{-- In your master.blade.php or navigation section --}}
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
            <div class="container">
                <a class="navbar-brand font-weight-bold text-dark" href="{{ url('/') }}">
                    <i class="fas fa-store mr-2"></i>Sample Store
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('food') }}">Food</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('drink') }}">Drink</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('about') }}">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                        </li>
                    </ul>

                    <div class="d-flex align-items-center">
                        <div class="input-group input-group-sm mr-3" style="width: 200px;">
                            <input type="text" class="form-control search-input" placeholder="Search...">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary search-btn" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex mr-3">
                            <a href="#" class="text-dark mx-2 position-relative">
                                <i class="fas fa-heart"></i>
                                <span class="badge badge-danger badge-pill position-absolute" style="top: -8px; right: -8px; font-size: 0.6rem;">3</span>
                            </a>
                            <a href="{{ route('cart_index') }}" class="text-dark mx-2 position-relative">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="badge badge-danger badge-pill position-absolute" style="top: -8px; right: -8px; font-size: 0.6rem;">{{ $cartCount ?? 0 }}</span>
                            </a>
                        </div>

                        {{-- User Authentication Dropdown --}}
                        @if(session('logged_in'))
                        <div class="dropdown ml-3">
                            <button class="btn btn-outline-dark dropdown-toggle" type="button" id="userDropdown" data-toggle="dropdown">
                                <i class="fas fa-user mr-2"></i>{{ session('customer_name') }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="fas fa-user mr-2"></i>My Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('cart_index') }}">
                                    <i class="fas fa-shopping-cart mr-2"></i>My Cart
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                        @else
                        <div class="d-flex gap-2 ml-3">
                            <a href="{{ route('login') }}" class="btn btn-outline-dark">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-dark">
                                <i class="fas fa-user-plus mr-2"></i>Register
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        {{-- Flash messages --}}
        <div class="container mt-3">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>@endif
            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>@endif
        </div>

        <main class="flex-fill">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="mt-auto bg-white border-top">
            <div class="container py-5">
                <div class="row">
                    <div class="col-md-4 mb-4 mb-md-0">
                        <h6 class="text-uppercase text-muted small">About</h6>
                        <p class="mb-0 text-secondary">We provide quality products with a focus on simplicity and speed.</p>
                    </div>
                    <div class="col-md-4 mb-4 mb-md-0">
                        <h6 class="text-uppercase text-muted small">Links</h6>
                        <ul class="list-unstyled mb-0">
                            <li><a href="/">Home</a></li>
                            <li><a href="/product">Product</a></li>
                            <li><a href="/contact">Contact</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-uppercase text-muted small">Contact</h6>
                        <ul class="list-unstyled mb-0 text-secondary">
                            <li>Phone: +855 123 456 789</li>
                            <li>Email: info@fake.com</li>
                        </ul>
                    </div>
                </div>
                <div class="text-center mt-4 small text-muted">&copy; {{ date('Y') }} Your Company. All rights reserved.
                </div>
            </div>
        </footer>
    </div>

    {{-- Core JS --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

    <script>
        const {
            createApp
        } = Vue;
        createApp({
            delimiters: ['[[', ']]'],
            data() {
                return {
                    cart_list: []
                }
            },
            methods: {
                addToCart(product_id) {
                    let url = "{{ url('/cart/add-to-cart') }}"
                    $.LoadingOverlay("show");
                    axios.post(url, {
                            product_id: product_id
                        })
                        .then(function(response) {
                            console.log(response);
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: "Product added to cart successfully!",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        })
                        .catch(function(error) {
                            console.log(error);
                        }).finally(function() {

                            $.LoadingOverlay("hide");
                        })
                },
                removeCart(cart_id) {
                    let url = "{{ url('/cart/remove') }}"
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.LoadingOverlay("show");
                            axios.post(url, {
                                    cart_id: cart_id
                                })
                                .then(function(response) {
                                    Swal.fire({
                                        position: "top-end",
                                        icon: "success",
                                        title: "Remove successfully!",
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    window.location.href = "{{ url('/cart') }}";
                                })
                                .catch(function(error) {
                                    console.log(error);
                                }).finally(function() {
                                    $.LoadingOverlay("hide");
                                })
                        }
                    });
                }
            }
        }).mount('#app');
    </script>
</body>

</html>