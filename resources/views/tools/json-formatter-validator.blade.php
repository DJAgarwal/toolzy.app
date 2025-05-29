@extends('layouts.app')

@section('content')
<div class="mb-3">
    <label for="jsonInput" class="form-label fw-semibold">Enter JSON Data:</label>
    <textarea class="form-control" id="jsonInput" rows="10" placeholder="Paste your JSON data here..."></textarea>
</div>
<div class="mb-3">
    <label for="fileUpload" class="form-label fw-semibold">Or Upload JSON File:</label>
    <input type="file" id="fileUpload" class="form-control" accept=".json">
</div>
<div class="mb-3 d-flex flex-wrap gap-2">
    <button class="btn btn-success" id="formatJsonBtn">Beautify</button>
    <button class="btn btn-warning" id="minifyJsonBtn">Minify</button>
    <button class="btn btn-primary" id="validateJsonBtn">Validate</button>
    <button class="btn btn-secondary" id="copyJsonBtn">Copy</button>
    <button class="btn btn-danger" id="clearJsonBtn">Clear</button>
    <button class="btn btn-info" id="loadExampleBtn">Load Example</button>
    <button class="btn btn-success" id="downloadFileBtn">Download JSON</button>
</div>
<div class="mb-3">
    <h5 class="fw-semibold">Validation Result:</h5>
    <p id="validationResult" class="text-muted mb-0"></p>
</div>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('fileUpload').addEventListener('change', uploadFile);
    document.getElementById('formatJsonBtn').addEventListener('click', formatJSON);
    document.getElementById('minifyJsonBtn').addEventListener('click', minifyJSON);
    document.getElementById('validateJsonBtn').addEventListener('click', validateJSON);
    document.getElementById('copyJsonBtn').addEventListener('click', copyJSON);
    document.getElementById('clearJsonBtn').addEventListener('click', clearJSON);
    document.getElementById('loadExampleBtn').addEventListener('click', loadExample);
    document.getElementById('downloadFileBtn').addEventListener('click', downloadFile);
});

function uploadFile() {
    const fileInput = document.getElementById('fileUpload');
    const file = fileInput.files[0];

    if (file && file.type === 'application/json') {
        const reader = new FileReader();
        reader.onload = function(event) {
            const fileContent = event.target.result;
            try {
                const json = JSON.parse(fileContent);
                document.getElementById('jsonInput').value = JSON.stringify(json, null, 2);
                showToast('File successfully uploaded.','success');
            } catch (e) {
                showToast('Invalid JSON file.','danger');
            }
        };
        reader.readAsText(file);
    } else {
        showToast('Please upload a valid JSON file.','danger');
    }
}

function downloadFile() {
    const jsonContent = document.getElementById('jsonInput').value;
    try {
        const json = JSON.parse(jsonContent);
        const blob = new Blob([JSON.stringify(json, null, 2)], { type: 'application/json' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'formatted-json.json';
        a.click();
        showToast('File downloaded successfully.','success');
    } catch (e) {
        showToast('Invalid JSON data, cannot download.','danger');
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

function showSuccess(message) {
    showToast(message,'success');
}

function showError(message) {
    showToast(message,'danger');
}
</script>
@endpush