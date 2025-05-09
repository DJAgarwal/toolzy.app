@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center fw-bold">Text to Slug Generator - Toolzy</h1>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">

                    <div class="mb-4">
                        <label for="textInput" class="form-label fw-semibold">Enter Text:</label>
                        <input type="text" class="form-control" id="textInput" placeholder="Enter the text you want to convert to slug" />
                    </div>

                    <div class="mb-4">
                        <label for="slugOutput" class="form-label fw-semibold">Generated Slug:</label>
                        <input type="text" class="form-control" id="slugOutput" readonly />
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg" onclick="generateSlug()">Generate Slug</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <section class="my-5">
        @include('components.what-is')
    </section>

    <!-- FAQs Section -->
    <section class="my-5">
        @include('components.faq')
    </section>
</div>
@endsection

@push('scripts')
<script>
function generateSlug() {
    var textInput = document.getElementById('textInput').value;
    
    // Replace spaces with hyphens and convert to lowercase
    var slug = textInput.trim()
                        .replace(/\s+/g, '-')
                        .replace(/[^\w-]+/g, '')  // Remove special characters
                        .toLowerCase();

    // Set the generated slug to the output field
    document.getElementById('slugOutput').value = slug;
}
</script>
@endpush