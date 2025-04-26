@extends('layouts.app')

@section('content')
    <div class="text-center mb-5">
        <h1 class="display-5 fw-semibold">Free Online Tools</h1>
        <p class="text-muted">Convert PDFs, compress images, and much more — all for free and unlimited.</p>

        <input type="text" class="form-control w-50 mx-auto mt-4" placeholder="Search tools... (coming soon)">
    </div>

    <div class="row g-4 justify-content-center">
        <!-- Sample Tool Card -->
        @foreach(['PDF to Word', 'Image Compressor', 'Word Counter', 'QR Code Generator', 'Text Case Converter', 'JSON Formatter'] as $tool)
            <div class="col-md-4 col-sm-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $tool }}</h5>
                        <a href="#" class="btn btn-primary mt-2 disabled">Coming Soon</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection