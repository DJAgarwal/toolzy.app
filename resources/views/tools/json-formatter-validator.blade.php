@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center fw-bold">JSON Formatter and Validator</h1>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">

                    <!-- JSON Input Section -->
                    <div class="mb-4">
                        <label for="jsonInput" class="form-label fw-semibold">Enter JSON Data:</label>
                        <textarea class="form-control" id="jsonInput" rows="8" placeholder="Paste your JSON data here..."></textarea>
                    </div>

                    <!-- JSON Format and Validate Button -->
                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg" onclick="formatAndValidateJSON()">Format & Validate JSON</button>
                    </div>

                    <!-- Result Section -->
                    <div class="mt-4">
                        <h4 class="fw-semibold">Formatted JSON:</h4>
                        <pre id="formattedJSON" class="bg-light p-3 rounded"></pre>
                    </div>

                    <div class="mt-4">
                        <h4 class="fw-semibold">Validation Result:</h4>
                        <p id="validationResult" class="text-muted"></p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Function to format and validate JSON
    function formatAndValidateJSON() {
        const jsonInput = document.getElementById('jsonInput').value;
        const formattedJSON = document.getElementById('formattedJSON');
        const validationResult = document.getElementById('validationResult');

        try {
            // Parse JSON to check validity
            const parsedJSON = JSON.parse(jsonInput);

            // If the JSON is valid, format it with indentation
            formattedJSON.textContent = JSON.stringify(parsedJSON, null, 4);

            // Display validation success
            validationResult.textContent = 'The JSON is valid!';
            validationResult.classList.remove('text-danger');
            validationResult.classList.add('text-success');
        } catch (e) {
            // If the JSON is invalid, display the error
            formattedJSON.textContent = '';
            validationResult.textContent = 'Invalid JSON: ' + e.message;
            validationResult.classList.remove('text-success');
            validationResult.classList.add('text-danger');
        }
    }
</script>
@endpush