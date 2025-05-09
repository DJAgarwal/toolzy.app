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
                    <div class="mb-3">
                        <label for="fileUpload" class="form-label fw-semibold">Or Upload JSON File:</label>
                        <input type="file" id="fileUpload" class="form-control" accept=".json" onchange="uploadFile()">
                    </div>

                    <!-- Buttons -->
                    <div class="mb-3 d-flex flex-wrap gap-2">
                        <button class="btn btn-success" onclick="formatJSON()">Beautify</button>
                        <button class="btn btn-warning" onclick="minifyJSON()">Minify</button>
                        <button class="btn btn-primary" onclick="validateJSON()">Validate</button>
                        <button class="btn btn-secondary" onclick="copyJSON()">Copy</button>
                        <button class="btn btn-danger" onclick="clearJSON()">Clear</button>
                        <button class="btn btn-info" onclick="loadExample()">Load Example</button>
                        <button class="btn btn-success" onclick="downloadFile()">Download JSON</button>
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
    function uploadFile() {
    const fileInput = document.getElementById('fileUpload');
    const file = fileInput.files[0];

    if (file && file.type === 'application/json') {
        const reader = new FileReader();
        reader.onload = function(event) {
            const fileContent = event.target.result;
            try {
                const json = JSON.parse(fileContent);
                document.getElementById('jsonInput').value = JSON.stringify(json, null, 2); // Format JSON for better readability
                toastr.success('File successfully uploaded.');
            } catch (e) {
                toastr.error('Invalid JSON file.');
            }
        };
        reader.readAsText(file);
    } else {
        toastr.error('Please upload a valid JSON file.');
    }
}
function downloadFile() {
    const jsonContent = document.getElementById('jsonInput').value;
    try {
        // Parse to ensure it's valid JSON
        const json = JSON.parse(jsonContent);

        // Create a Blob from JSON content
        const blob = new Blob([JSON.stringify(json, null, 2)], { type: 'application/json' });

        // Create a link element and trigger the download
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'formatted-json.json'; // The name of the downloaded file
        a.click();

        toastr.success('File downloaded successfully.');
    } catch (e) {
        toastr.error('Invalid JSON data, cannot download.');
    }
}

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
