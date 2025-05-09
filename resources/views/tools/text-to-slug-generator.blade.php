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
                        <div class="input-group">
                            <input type="text" class="form-control" id="slugOutput" readonly />
                            <button class="btn btn-outline-secondary" type="button" onclick="copySlug()">Copy</button>
                        </div>
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
    const input = document.getElementById('textInput').value.trim();

    if (!input) {
        showToast("Please enter some text to generate slug.", "danger");
        return;
    }

    const slug = input
        .replace(/\s+/g, '-')
        .replace(/[^\w-]+/g, '')
        .toLowerCase();

    document.getElementById('slugOutput').value = slug;
}

function copySlug() {
    const slugField = document.getElementById('slugOutput');
    if (!slugField.value) {
        showToast("Nothing to copy!", "danger");
        return;
    }

    slugField.select();
    slugField.setSelectionRange(0, 99999); // For mobile
    navigator.clipboard.writeText(slugField.value).then(() => {
        showToast("Slug copied to clipboard!", "success");
    });
}
function showToast(message, type = "success") {
    const toastEl = document.getElementById("mainToast");
    const toastBody = document.getElementById("mainToastBody");

    toastBody.textContent = message;
    toastEl.className = `toast align-items-center text-white bg-${type} border-0`;

    const bsToast = new bootstrap.Toast(toastEl);
    bsToast.show();
}
</script>
@endpush