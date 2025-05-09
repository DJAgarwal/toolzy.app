@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center fw-bold">URL Encoder and Decoder</h1>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">

                    <div class="mb-4">
                        <label for="mainInput" class="form-label fw-semibold">Enter Text:</label>
                        <textarea class="form-control" id="mainInput" rows="5" placeholder="Type or paste your text here..."></textarea>
                    </div>

                    <div class="d-flex justify-content-between gap-3">
                        <button class="btn btn-primary w-100" onclick="encodeText()">Encode</button>
                        <button class="btn btn-secondary w-100" onclick="decodeText()">Decode</button>
                        <button class="btn btn-outline-dark w-100" onclick="copyText()">Copy</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <section class="my-5">
        @include('components.what-is')
    </section>
    <section class="my-5">
        @include('components.faq')
    </section>
</div>
@endsection

@push('scripts')
<script>
    function encodeText() {
        const inputField = document.getElementById('mainInput');
        inputField.value = encodeURIComponent(inputField.value);
    }

    function decodeText() {
        const inputField = document.getElementById('mainInput');
        try {
            inputField.value = decodeURIComponent(inputField.value);
        } catch (e) {
            alert('Invalid URL-encoded input!');
        }
    }

    function copyText() {
        const inputField = document.getElementById('mainInput');
        if (!inputField.value) {
            showToast("Nothing to copy!", "danger");
            return;
        }
        inputField.select();
        inputField.setSelectionRange(0, 99999); // For mobile devices
        navigator.clipboard.writeText(inputField.value).then(() => {
            showToast("URL copied to clipboard!", "success");
        });
    }
</script>
@endpush