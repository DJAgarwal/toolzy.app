@extends('layouts.app')
@section('content')

<section class="position-relative text-center">
    <img src="{{ asset('images/banner.webp') }}" alt="Banner with natural products" class="img-fluid w-100">

    <div class="center-banner-buttons">
        <div class="d-flex justify-content-center dynamic-gap mt-3 flex-wrap">
            <a href="#categories" class="btn btn-success home-btn">Explore Categories</a>
            <a href="#search" class="btn btn-success home-btn-second">Search Ingredients</a>
        </div>
    </div>
</section>

<!-- Search Section -->
<section id="search" class="py-5 bg-light text-center">
    <div class="container">
        <h2 class="mb-4 fw-bold display-6 text-dark">Search for Any Product or Ingredient</h2>
        
        <form class="d-flex justify-content-center" role="search" aria-label="Product or ingredient search">
            <input type="text" class="form-control form-control-lg w-75 w-md-50 rounded-pill shadow-sm px-4"
                placeholder="e.g. Aloe Vera, Parabens, Dove Shampoo" required>
        </form>
    </div>
</section>
<!-- Categories Section -->
<section id="categories" class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Explore by Category</h2>
        <div class="row g-4 justify-content-center">

        @foreach ($categories as $category)
    <div class="col-6 col-md-4 col-lg-3">
        <a href="{{ url('/category/' . $category->slug) }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden bg-light hover-zoom"
                 style="background-image: url('{{ asset('images/categories/' . $category->slug . '.webp') }}'); background-size: contain; background-repeat: no-repeat; background-position: center; height: 200px;">
            </div>
        </a>
    </div>
        @endforeach
        </div>
    </div>
</section>



<!-- Top Picks Section -->
<section class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center mb-4">Top Picks This Month</h2>
        <div class="row g-4">
            @foreach (range(1, 3) as $i)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset('images/product' . $i . '.jpg') }}" class="card-img-top" alt="Natural Shampoo {{ $i }}" loading="lazy">
                        <div class="card-body">
                            <h5 class="card-title">Natural Shampoo {{ $i }}</h5>
                            <p class="card-text">Key Ingredients: Aloe Vera ✅, SLS ❌, Paraben ❌</p>
                            <a href="#" class="btn btn-sm btn-outline-success">See Full Review</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Why Trust Us -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Why Trust Natural Products India?</h2>
        <div class="row text-center g-4">
            <div class="col-md-3">
                <div class="border p-4 rounded h-100">
                    <h5>✅ Ingredient Based</h5>
                    <p>We rate products solely based on what’s inside, not marketing hype.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border p-4 rounded h-100">
                    <h5>🔍 Research-Backed</h5>
                    <p>All ingredients are checked against global databases and INCI standards.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border p-4 rounded h-100">
                    <h5>🇮🇳 Indian Focus</h5>
                    <p>Made for Indian skin, hair, and climate – by someone who understands it.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border p-4 rounded h-100">
                    <h5>🤝 100% Honest</h5>
                    <p>No sponsorships. No ads. Just genuine reviews based on safety.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section (with Schema.org markup) -->
<section class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center mb-4">What Our Readers Say</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <blockquote class="blockquote border p-4 rounded shadow-sm" itemscope itemtype="https://schema.org/Review">
                    <p itemprop="reviewBody">“Finally, a site that helps me pick products safely for my sensitive skin!”</p>
                    <footer class="blockquote-footer mt-2">
                        <span itemprop="author" itemscope itemtype="https://schema.org/Person">
                            <span itemprop="name">Neha Sharma</span>, Delhi
                        </span>
                    </footer>
                </blockquote>
            </div>
            <div class="col-md-4">
                <blockquote class="blockquote border p-4 rounded shadow-sm">
                    <p>“Thanks to this site, I now know what to avoid in hair oils. Super useful.”</p>
                    <footer class="blockquote-footer mt-2">Rahul Verma, Bangalore</footer>
                </blockquote>
            </div>
            <div class="col-md-4">
                <blockquote class="blockquote border p-4 rounded shadow-sm">
                    <p>“Simple, clear, and honest. This is now my go-to for product research.”</p>
                    <footer class="blockquote-footer mt-2">Pooja Patel, Ahmedabad</footer>
                </blockquote>
            </div>
        </div>
    </div>
</section>

<!-- Latest Blog Posts -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Latest from the Blog</h2>
        <div class="row g-4">
            @foreach (range(1, 3) as $i)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset('images/blog' . $i . '.jpg') }}" class="card-img-top" alt="Blog Post {{ $i }}" loading="lazy">
                        <div class="card-body">
                            <h5 class="card-title">How to Read Product Labels Effectively</h5>
                            <p class="card-text">Learn how to quickly identify harmful ingredients on your next purchase.</p>
                            <a href="#" class="btn btn-sm btn-outline-success">Read More</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Newsletter Signup -->
<section class="py-5 bg-white text-center">
    <div class="container">
        <h2 class="mb-4">Stay Updated with Natural Tips</h2>
        <p class="text-muted mb-4">Join our newsletter to get weekly updates on safe products and ingredient news.</p>
        <form class="row justify-content-center g-2">
            <div class="col-md-4">
                <input type="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="col-md-2">
                <button class="btn btn-success w-100">Subscribe</button>
            </div>
        </form>
    </div>
</section>

<!-- Final CTA Section -->
<section class="py-5 bg-success text-white text-center">
    <div class="container">
        <h2>Ready to Choose Natural, Safe Products?</h2>
        <p class="lead">Start exploring our curated ingredient-safe product lists now.</p>
        <a href="/categories" class="btn btn-light mt-3">Browse Categories</a>
    </div>
</section>

@endsection