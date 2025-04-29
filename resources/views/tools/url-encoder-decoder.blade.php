@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center fw-bold">URL Encoder and Decoder</h1>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">

                    <!-- URL Encoding Section -->
                    <div class="mb-4">
                        <h5 class="fw-semibold">URL Encoder</h5>
                        <textarea class="form-control" id="encodeInput" rows="4" placeholder="Enter text to encode..."></textarea>
                        <button class="btn btn-primary mt-3" onclick="encodeURL()">Encode</button>
                        <div class="mt-3">
                            <label for="encodedOutput" class="form-label fw-semibold">Encoded Output:</label>
                            <textarea class="form-control" id="encodedOutput" rows="4" readonly></textarea>
                        </div>
                    </div>

                    <!-- URL Decoding Section -->
                    <div class="mb-4">
                        <h5 class="fw-semibold">URL Decoder</h5>
                        <textarea class="form-control" id="decodeInput" rows="4" placeholder="Enter URL encoded text..."></textarea>
                        <button class="btn btn-primary mt-3" onclick="decodeURL()">Decode</button>
                        <div class="mt-3">
                            <label for="decodedOutput" class="form-label fw-semibold">Decoded Output:</label>
                            <textarea class="form-control" id="decodedOutput" rows="4" readonly></textarea>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function encodeURL() {
        const input = document.getElementById('encodeInput').value;
        const encoded = encodeURIComponent(input); // URL Encoding
        document.getElementById('encodedOutput').value = encoded;
    }

    function decodeURL() {
        const input = document.getElementById('decodeInput').value;
        const decoded = decodeURIComponent(input); // URL Decoding
        document.getElementById('decodedOutput').value = decoded;
    }
</script>
@endpush
