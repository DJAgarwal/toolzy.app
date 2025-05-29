@extends('layouts.app')

@section('content')
<div class="mb-3">
    <label for="inputText" class="form-label fw-semibold">Enter or Paste Your Text:</label>
    <textarea id="inputText" class="form-control" rows="12" placeholder="Paste or type your text here..."></textarea>
</div>

<div class="mb-3">
    <p class="mb-1"><strong>Total lines:</strong> <span id="totalLines">0</span> | 
    <strong>Unique lines:</strong> <span id="uniqueLines">0</span></p>
</div>

<div class="mb-3 d-flex flex-wrap gap-3">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="trimLines" checked>
        <label class="form-check-label fw-semibold" for="trimLines">Trim whitespace</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="caseSensitive">
        <label class="form-check-label fw-semibold" for="caseSensitive">Case sensitive</label>
    </div>
</div>

<div class="mb-3 d-flex flex-wrap gap-2">
    <button id="removeDuplicatesBtn" class="btn btn-primary">Remove Duplicates</button>
    <button id="copyToClipboardBtn" class="btn btn-secondary">Copy to Clipboard</button>
    <button id="downloadResultBtn" class="btn btn-success">Download Result</button>
</div>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function() {
    const inputText = document.getElementById('inputText');
    const totalLines = document.getElementById('totalLines');
    const uniqueLines = document.getElementById('uniqueLines');
    const trimLinesToggle = document.getElementById('trimLines');
    const caseSensitiveToggle = document.getElementById('caseSensitive');
    const removeDuplicatesBtn = document.getElementById('removeDuplicatesBtn');
    const copyToClipboardBtn = document.getElementById('copyToClipboardBtn');
    const downloadResultBtn = document.getElementById('downloadResultBtn');

    inputText.addEventListener('input', updateLineCounts);
    trimLinesToggle.addEventListener('change', updateLineCounts);
    caseSensitiveToggle.addEventListener('change', updateLineCounts);
    removeDuplicatesBtn.addEventListener('click', removeDuplicates);
    copyToClipboardBtn.addEventListener('click', copyToClipboard);
    downloadResultBtn.addEventListener('click', downloadResult);

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
            showToast('Copied to clipboard!', 'success');
        });
    }

    function downloadResult() {
        const blob = new Blob([inputText.value], { type: 'text/plain' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'cleaned-text.txt';
        link.click();
    }

    // Initial count
    updateLineCounts();
});
</script>
@endpush