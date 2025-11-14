@extends('master')
@section('title','Contact Us — Sample Store')
@section('content')
    {{-- Hero Section --}}
    <section class="container-fluid py-5 bg-primary text-white">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="display-4 font-weight-bold mb-4">Get In Touch</h1>
                    <p class="lead mb-0">
                        We'd love to hear from you. Send us a message and we'll respond as soon as possible.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact Form & Info --}}
    <section class="container py-5">
        <div class="row">
            {{-- Contact Form --}}
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h4 font-weight-bold mb-4">Send us a Message</h3>
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('contact.submit') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject *</label>
                                <input type="text" 
                                       class="form-control @error('subject') is-invalid @enderror" 
                                       id="subject" 
                                       name="subject" 
                                       value="{{ old('subject') }}" 
                                       required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message *</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          id="message" 
                                          name="message" 
                                          rows="5" 
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-paper-plane mr-2"></i>Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Contact Information --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h4 class="h5 font-weight-bold mb-4">Contact Information</h4>
                        
                        <div class="d-flex mb-4">
                            <div class="mr-3">
                                <i class="fas fa-map-marker-alt text-primary fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold mb-1">Address</h6>
                                <p class="text-muted mb-0">
                                    123 Commerce Street<br>
                                    Suite 100<br>
                                    San Francisco, CA 94105
                                </p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="mr-3">
                                <i class="fas fa-phone text-primary fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold mb-1">Phone</h6>
                                <p class="text-muted mb-0">
                                    +1 (555) 123-4567<br>
                                    Mon-Fri 9am-6pm PST
                                </p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="mr-3">
                                <i class="fas fa-envelope text-primary fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold mb-1">Email</h6>
                                <p class="text-muted mb-0">
                                    hello@samplestore.com<br>
                                    support@samplestore.com
                                </p>
                            </div>
                        </div>

                        <div class="d-flex">
                            <div class="mr-3">
                                <i class="fas fa-clock text-primary fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold mb-1">Business Hours</h6>
                                <p class="text-muted mb-0">
                                    Monday - Friday: 9:00 - 18:00<br>
                                    Saturday: 10:00 - 16:00<br>
                                    Sunday: Closed
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Social Media --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="h5 font-weight-bold mb-4">Follow Us</h4>
                        <div class="d-flex justify-content-between">
                            <a href="#" class="btn btn-outline-primary btn-sm rounded-circle">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-sm rounded-circle">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-sm rounded-circle">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-sm rounded-circle">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-sm rounded-circle">
                                <i class="fab fa-pinterest"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Map Section --}}
    <section class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="bg-light" style="height: 400px;">
                    <!-- Replace with actual Google Maps embed -->
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <div class="text-center">
                            <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Interactive Map</h4>
                            <p class="text-muted">Google Maps integration would go here</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ Section --}}
    <section class="container py-5">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="h1 font-weight-bold mb-3">Frequently Asked Questions</h2>
                <p class="lead text-muted">Quick answers to common questions</p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-white border-0" id="faq1">
                            <h5 class="mb-0">
                                <button class="btn btn-link btn-block text-left text-dark font-weight-bold text-decoration-none" 
                                        type="button" 
                                        data-toggle="collapse" 
                                        data-target="#collapse1">
                                    What is your return policy?
                                </button>
                            </h5>
                        </div>
                        <div id="collapse1" class="collapse show" data-parent="#faqAccordion">
                            <div class="card-body text-muted">
                                We offer a 30-day return policy for all items in original condition. 
                                Items must be unused and in their original packaging.
                            </div>
                        </div>
                    </div>
                    
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-white border-0" id="faq2">
                            <h5 class="mb-0">
                                <button class="btn btn-link btn-block text-left text-dark font-weight-bold text-decoration-none collapsed" 
                                        type="button" 
                                        data-toggle="collapse" 
                                        data-target="#collapse2">
                                    How long does shipping take?
                                </button>
                            </h5>
                        </div>
                        <div id="collapse2" class="collapse" data-parent="#faqAccordion">
                            <div class="card-body text-muted">
                                Standard shipping takes 3-5 business days. Express shipping is available 
                                for 1-2 business days. Free shipping on orders over $50.
                            </div>
                        </div>
                    </div>
                    
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-white border-0" id="faq3">
                            <h5 class="mb-0">
                                <button class="btn btn-link btn-block text-left text-dark font-weight-bold text-decoration-none collapsed" 
                                        type="button" 
                                        data-toggle="collapse" 
                                        data-target="#collapse3">
                                    Do you ship internationally?
                                </button>
                            </h5>
                        </div>
                        <div id="collapse3" class="collapse" data-parent="#faqAccordion">
                            <div class="card-body text-muted">
                                Currently, we ship within the United States only. We're working on 
                                expanding our international shipping options.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation and enhancement
    const contactForm = document.querySelector('form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            // Add loading state to submit button
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
            submitBtn.disabled = true;
            
            // Re-enable button after 5 seconds (in case of error)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 5000);
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.btn-rounded-circle {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.accordion .card-header {
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.accordion .card-header:hover {
    background-color: #f8f9fa !important;
}

.accordion .btn-link {
    padding: 1rem 1.25rem;
}

.accordion .btn-link:focus {
    box-shadow: none;
}

.accordion .btn-link:not(.collapsed)::after {
    content: "−";
    float: right;
}

.accordion .btn-link.collapsed::after {
    content: "+";
    float: right;
}
</style>
@endpush