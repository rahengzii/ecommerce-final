@extends('master')
@section('title','About Us — Sample Store')
@section('content')
    {{-- Hero Section --}}
    <section class="container-fluid py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 font-weight-bold mb-4">Our Story</h1>
                    <p class="lead text-muted mb-4">
                        Welcome to Sample Store, where quality meets affordability. Since our founding in 2015, 
                        we've been committed to bringing you the best products with exceptional customer service.
                    </p>
                    <div class="d-flex">
                        <div class="mr-4">
                            <h3 class="font-weight-bold text-primary">50K+</h3>
                            <p class="text-muted">Happy Customers</p>
                        </div>
                        <div class="mr-4">
                            <h3 class="font-weight-bold text-primary">8+</h3>
                            <p class="text-muted">Years Experience</p>
                        </div>
                        <div>
                            <h3 class="font-weight-bold text-primary">100%</h3>
                            <p class="text-muted">Satisfaction Guarantee</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://via.placeholder.com/600x400/667eea/ffffff?text=Our+Team" 
                         alt="Our Team" 
                         class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    {{-- Mission & Vision --}}
    <section class="container py-5">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="text-center p-4">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-bullseye text-white fa-2x"></i>
                    </div>
                    <h3 class="h4 font-weight-bold mb-3">Our Mission</h3>
                    <p class="text-muted">
                        To provide high-quality products at affordable prices while delivering exceptional 
                        customer experiences that build lifelong relationships.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="text-center p-4">
                    <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-eye text-white fa-2x"></i>
                    </div>
                    <h3 class="h4 font-weight-bold mb-3">Our Vision</h3>
                    <p class="text-muted">
                        To become the most trusted and customer-centric online store, recognized for 
                        innovation, quality, and community impact.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="text-center p-4">
                    <div class="bg-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-hand-holding-heart text-white fa-2x"></i>
                    </div>
                    <h3 class="h4 font-weight-bold mb-3">Our Values</h3>
                    <p class="text-muted">
                        Integrity, innovation, customer-first approach, and sustainability guide 
                        every decision we make.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Team Section --}}
    <section class="container py-5 bg-light">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="h1 font-weight-bold mb-3">Meet Our Team</h2>
                <p class="lead text-muted">The passionate people behind Sample Store</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <img src="https://via.placeholder.com/300x300/667eea/ffffff?text=John+Doe" 
                         class="card-img-top rounded-circle mx-auto mt-4" 
                         alt="John Doe"
                         style="width: 150px; height: 150px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold">John Doe</h5>
                        <p class="text-muted">CEO & Founder</p>
                        <p class="small text-muted">
                            With over 15 years of experience in e-commerce and retail management.
                        </p>
                        <div class="social-links">
                            <a href="#" class="text-dark mx-1"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-dark mx-1"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-dark mx-1"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <img src="https://via.placeholder.com/300x300/f093fb/ffffff?text=Jane+Smith" 
                         class="card-img-top rounded-circle mx-auto mt-4" 
                         alt="Jane Smith"
                         style="width: 150px; height: 150px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold">Jane Smith</h5>
                        <p class="text-muted">Head of Operations</p>
                        <p class="small text-muted">
                            Ensures smooth operations and excellent customer service delivery.
                        </p>
                        <div class="social-links">
                            <a href="#" class="text-dark mx-1"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-dark mx-1"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-dark mx-1"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <img src="https://via.placeholder.com/300x300/4facfe/ffffff?text=Mike+Johnson" 
                         class="card-img-top rounded-circle mx-auto mt-4" 
                         alt="Mike Johnson"
                         style="width: 150px; height: 150px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold">Mike Johnson</h5>
                        <p class="text-muted">Marketing Director</p>
                        <p class="small text-muted">
                            Drives brand growth and customer engagement strategies.
                        </p>
                        <div class="social-links">
                            <a href="#" class="text-dark mx-1"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-dark mx-1"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-dark mx-1"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <img src="https://via.placeholder.com/300x300/43e97b/ffffff?text=Sarah+Wilson" 
                         class="card-img-top rounded-circle mx-auto mt-4" 
                         alt="Sarah Wilson"
                         style="width: 150px; height: 150px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold">Sarah Wilson</h5>
                        <p class="text-muted">Product Manager</p>
                        <p class="small text-muted">
                            Curates the best products and ensures quality standards.
                        </p>
                        <div class="social-links">
                            <a href="#" class="text-dark mx-1"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-dark mx-1"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-dark mx-1"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Why Choose Us --}}
    <section class="container py-5">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="h1 font-weight-bold mb-3">Why Choose Sample Store?</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="d-flex">
                    <div class="mr-4">
                        <i class="fas fa-shipping-fast fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h4 class="font-weight-bold">Fast & Free Shipping</h4>
                        <p class="text-muted">
                            Enjoy free shipping on all orders over $50. We deliver to your doorstep within 2-3 business days.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="d-flex">
                    <div class="mr-4">
                        <i class="fas fa-shield-alt fa-2x text-success"></i>
                    </div>
                    <div>
                        <h4 class="font-weight-bold">Secure Shopping</h4>
                        <p class="text-muted">
                            Your security is our priority. We use SSL encryption to protect your personal information.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="d-flex">
                    <div class="mr-4">
                        <i class="fas fa-undo-alt fa-2x text-info"></i>
                    </div>
                    <div>
                        <h4 class="font-weight-bold">Easy Returns</h4>
                        <p class="text-muted">
                            Not satisfied? Return any item within 30 days for a full refund, no questions asked.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="d-flex">
                    <div class="mr-4">
                        <i class="fas fa-headset fa-2x text-warning"></i>
                    </div>
                    <div>
                        <h4 class="font-weight-bold">24/7 Support</h4>
                        <p class="text-muted">
                            Our customer support team is available around the clock to assist you with any queries.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
.team-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.team-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.1) !important;
}

.social-links a {
    transition: color 0.3s ease;
}

.social-links a:hover {
    color: #007bff !important;
}

.bg-light {
    background-color: #f8f9fa !important;
}
</style>
@endpush