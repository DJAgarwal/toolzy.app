@extends('layouts.app')

@section('content')
        <div class="input-group input-group-lg mx-auto" style="max-width: 800px;">
            <textarea id="inputText" class="form-control" rows="12" placeholder="Paste or type your text here..."></textarea>
        </div>

        <div class="text-center my-3">
            <p><strong>Total lines:</strong> <span id="totalLines">0</span> |
               <strong>Unique lines:</strong> <span id="uniqueLines">0</span></p>
        </div>

        <div class="text-center mb-3">
            <div class="form-check d-inline-block mx-2">
                <input class="form-check-input" type="checkbox" id="trimLines" checked>
                <label class="form-check-label" for="trimLines">Trim whitespace</label>
            </div>
            <div class="form-check d-inline-block mx-2">
                <input class="form-check-input" type="checkbox" id="caseSensitive">
                <label class="form-check-label" for="caseSensitive">Case sensitive</label>
            </div>
        </div>

        <div class="text-center mb-4 d-flex flex-wrap justify-content-center gap-3">
            <button onclick="removeDuplicates()" class="btn btn-primary">Remove Duplicates</button>
            <button onclick="copyToClipboard()" class="btn btn-secondary">Copy to Clipboard</button>
            <button onclick="downloadResult()" class="btn btn-success">Download Result</button>
        </div>
@endsection

@push('scripts')
<script>
const inputText = document.getElementById('inputText');
const totalLines = document.getElementById('totalLines');
const uniqueLines = document.getElementById('uniqueLines');
const trimLinesToggle = document.getElementById('trimLines');
const caseSensitiveToggle = document.getElementById('caseSensitive');

inputText.addEventListener('input', updateLineCounts);
trimLinesToggle.addEventListener('change', updateLineCounts);
caseSensitiveToggle.addEventListener('change', updateLineCounts);

function removeDuplicates() {
    let lines = inputText.value.split('\n');

    if (trimLinesToggle.checked) {
        lines = lines.map(line => line.trim()).filter(line => line !== '');
    }

    const seen = new Set();
    const caseSensitive = caseSensitiveToggle.checked;
    const unique = [];

    for (let line of lines) {
        const key = caseSensitive ? line : line.toLowerCase();
        if (!seen.has(key)) {
            seen.add(key);
            unique.push(line);
        }
    }

    inputText.value = unique.join('\n');
    updateLineCounts();
}

function updateLineCounts() {
    const lines = inputText.value.split('\n');
    totalLines.textContent = lines.length;

    const trimmed = trimLinesToggle.checked
        ? lines.map(line => line.trim()).filter(line => line !== '')
        : lines;

    const caseSensitive = caseSensitiveToggle.checked;
    const uniqueSet = new Set(trimmed.map(line => caseSensitive ? line : line.toLowerCase()));
    uniqueLines.textContent = uniqueSet.size;
}

function copyToClipboard() {
    navigator.clipboard.writeText(inputText.value).then(() => {
        alert('Copied to clipboard!');
    });
}

function downloadResult() {
    const blob = new Blob([inputText.value], { type: 'text/plain' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'cleaned-text.txt';
    link.click();
}
</script>
@endpush
