@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center fw-bold">JSON Formatter and Validator</h1>

    <div class="row justify-content-center">
        <div class="col-12 col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">

                    <!-- JSON Input Section -->
                    <div class="mb-3">
                        <label for="jsonInput" class="form-label fw-semibold">Enter JSON Data:</label>
                        <textarea class="form-control" id="jsonInput" rows="10" placeholder="Paste your JSON data here..."></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="mb-3 d-flex flex-wrap gap-2">
                        <button class="btn btn-success" onclick="formatJSON()">Beautify</button>
                        <button class="btn btn-warning" onclick="minifyJSON()">Minify</button>
                        <button class="btn btn-primary" onclick="validateJSON()">Validate</button>
                        <button class="btn btn-secondary" onclick="copyJSON()">Copy</button>
                        <button class="btn btn-danger" onclick="clearJSON()">Clear</button>
                        <button class="btn btn-info" onclick="loadExample()">Load Example</button>
                    </div>

                    <!-- Result Section -->
                    <div class="mb-3">
                        <h5 class="fw-semibold">Validation Result:</h5>
                        <p id="validationResult" class="text-muted mb-0"></p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function formatJSON() {
        const input = document.getElementById('jsonInput').value;
        try {
            const obj = JSON.parse(input);
            document.getElementById('jsonInput').value = JSON.stringify(obj, null, 4);
            showSuccess('JSON formatted successfully.');
        } catch (e) {
            showError('Invalid JSON: ' + e.message);
        }
    }

    function minifyJSON() {
        const input = document.getElementById('jsonInput').value;
        try {
            const obj = JSON.parse(input);
            document.getElementById('jsonInput').value = JSON.stringify(obj);
            showSuccess('JSON minified successfully.');
        } catch (e) {
            showError('Invalid JSON: ' + e.message);
        }
    }

    function validateJSON() {
        const input = document.getElementById('jsonInput').value;
        const result = document.getElementById('validationResult');
        try {
            JSON.parse(input);
            result.textContent = 'The JSON is valid!';
            result.classList.remove('text-danger');
            result.classList.add('text-success');
            showSuccess('JSON is valid.');
        } catch (e) {
            result.textContent = 'Invalid JSON: ' + e.message;
            result.classList.remove('text-success');
            result.classList.add('text-danger');
            showError('Invalid JSON.');
        }
    }

    function copyJSON() {
        const input = document.getElementById('jsonInput');
        input.select();
        input.setSelectionRange(0, 99999); // For mobile devices
        document.execCommand('copy');
        showSuccess('Copied to clipboard!');
    }

    function clearJSON() {
        document.getElementById('jsonInput').value = '';
        document.getElementById('validationResult').textContent = '';
    }

    function loadExample() {
        const example = {
            "name": "John Doe",
            "email": "john@example.com",
            "skills": ["JavaScript", "Laravel", "Vue"],
            "active": true
        };
        document.getElementById('jsonInput').value = JSON.stringify(example, null, 4);
        document.getElementById('validationResult').textContent = '';
        showSuccess('Example JSON loaded.');
    }

    // Toaster helpers
    function showSuccess(message) {
        showToast(message,'success');
    }

    function showError(message) {
        showToast(message,'danger');
    }
</script>
@endpush
