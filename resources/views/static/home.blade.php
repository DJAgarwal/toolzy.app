@extends('layouts.app')

@section('content')
<div class="bg-light py-5">
    <div class="container text-center">
        <h1 class="display-5 fw-bold mb-3">Welcome to Toolzy</h1>
        <p class="lead text-muted">Free, fast, and reliable tools to simplify your digital tasks — no signups, no nonsense.</p>
        <a href="#tools" class="btn btn-primary btn-lg mt-3">Explore Tools</a>
    </div>
</div>

<div class="py-5" id="tools">
    <div class="container">
        <h2 class="text-center mb-4">Popular Tools</h2>
        <div class="row g-4">
        <div class="mb-4">
            <form method="GET" action="{{ route('home') }}">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search tools..." value="{{ request()->query('search') }}">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
        </div>
        @foreach ($tools as $tool)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $tool->meta_title }}</h5>
                        <p class="card-text text-muted">{{ $tool->meta_description }}</p>
                        <a href="{{ url('/' . $tool->page_name) }}" class="btn btn-outline-primary mt-2">Use Tool</a>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="d-flex justify-content-center">
            {{ $tools->links() }}
        </div>
    </div>
    </div>
</div>

<div class="bg-primary text-white py-5">
    <div class="container text-center">
        <h3 class="mb-3">Why Choose Toolzy?</h3>
        <p class="mb-4">We believe in simplicity, privacy, and unlimited access to tools that just work — no ads, no tracking.</p>
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="mb-3">
                    <i class="bi bi-lightning-charge-fill fs-1"></i>
                    <h5 class="mt-2">Fast</h5>
                    <p>Tools that run instantly without waiting or loading bars.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <i class="bi bi-shield-lock-fill fs-1"></i>
                    <h5 class="mt-2">Secure</h5>
                    <p>No data tracking, no cookies, your input stays with you.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <i class="bi bi-gem fs-1"></i>
                    <h5 class="mt-2">Free Forever</h5>
                    <p>No subscriptions. Every tool is 100% free to use.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-5 bg-light">
    <div class="container text-center">
        <p class="mb-1">🚀 Built with love by developers who care about simplicity.</p>
        <small class="text-muted">More tools launching soon — stay tuned!</small>
    </div>
</div>
@endsection