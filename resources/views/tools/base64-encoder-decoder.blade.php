@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center fw-bold">Base64 Encoder and Decoder</h1>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">

                    <!-- Encoding Section -->
                    <div class="mb-4">
                        <h5 class="fw-semibold">Base64 Encoder</h5>
                        <textarea class="form-control" id="encodeInput" rows="4" placeholder="Enter text to encode..."></textarea>
                        <button class="btn btn-primary mt-3" onclick="encodeBase64()">Encode</button>
                        <div class="mt-3">
                            <label for="encodedOutput" class="form-label fw-semibold">Encoded Output:</label>
                            <textarea class="form-control" id="encodedOutput" rows="4" readonly></textarea>
                        </div>
                    </div>

                    <!-- Decoding Section -->
                    <div class="mb-4">
                        <h5 class="fw-semibold">Base64 Decoder</h5>
                        <textarea class="form-control" id="decodeInput" rows="4" placeholder="Enter Base64 encoded text..."></textarea>
                        <button class="btn btn-primary mt-3" onclick="decodeBase64()">Decode</button>
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
    function encodeBase64() {
        const input = document.getElementById('encodeInput').value;
        const encoded = btoa(input); // Encoding to Base64
        document.getElementById('encodedOutput').value = encoded;
    }

    function decodeBase64() {
        const input = document.getElementById('decodeInput').value;
        try {
            const decoded = atob(input); // Decoding from Base64
            document.getElementById('decodedOutput').value = decoded;
        } catch (e) {
            document.getElementById('decodedOutput').value = 'Invalid Base64 string.';
        }
    }
</script>
@endpush