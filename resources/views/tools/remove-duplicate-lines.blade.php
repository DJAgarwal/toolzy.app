@extends('layouts.app')

@section('content')
<div class="mb-3">
    <label for="inputText" class="form-label fw-semibold">Enter or Paste Your Text:</label>
    <textarea id="inputText" class="form-control" rows="12" placeholder="Paste or type your text here..."></textarea>
</div>

<div class="mb-3">
    <input type="file" id="importTextFile" accept=".txt" hidden>
    <button class="btn btn-outline-secondary mb-2" id="importFileBtn">Import .txt File</button>
</div>

<div class="mb-3">
    <p class="mb-1">
        <strong>Total lines:</strong> <span id="totalLines">0</span> |
        <strong>Unique lines:</strong> <span id="uniqueLines">0</span> |
        <strong>Duplicate lines:</strong> <span id="duplicateLines">0</span> |
        <strong>Blank lines:</strong> <span id="blankLines">0</span>
    </p>
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
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="removeEmptyLines">
        <label class="form-check-label fw-semibold" for="removeEmptyLines">Remove empty lines</label>
    </div>
</div>

<div class="mb-3 d-flex flex-wrap gap-2">
    <button id="removeDuplicatesBtn" class="btn btn-primary">Remove Duplicates</button>
    <button id="restoreOriginalBtn" class="btn btn-warning" disabled>Restore Original</button>
    <button id="copyToClipboardBtn" class="btn btn-secondary">Copy to Clipboard</button>
    <button id="downloadResultBtn" class="btn btn-success">Download Result</button>
    <button id="clearTextBtn" class="btn btn-outline-danger">Clear</button>
</div>

<div class="mb-3">
    <label for="uniquePreview" class="form-label fw-semibold">Preview (Unique Lines):</label>
    <textarea id="uniquePreview" class="form-control bg-light" rows="8" readonly style="resize:vertical"></textarea>
</div>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function() {
    const inputText = document.getElementById('inputText');
    const totalLines = document.getElementById('totalLines');
    const uniqueLines = document.getElementById('uniqueLines');
    const duplicateLines = document.getElementById('duplicateLines');
    const blankLines = document.getElementById('blankLines');
    const trimLinesToggle = document.getElementById('trimLines');
    const caseSensitiveToggle = document.getElementById('caseSensitive');
    const removeEmptyLinesToggle = document.getElementById('removeEmptyLines');
    const removeDuplicatesBtn = document.getElementById('removeDuplicatesBtn');
    const restoreOriginalBtn = document.getElementById('restoreOriginalBtn');
    const copyToClipboardBtn = document.getElementById('copyToClipboardBtn');
    const downloadResultBtn = document.getElementById('downloadResultBtn');
    const uniquePreview = document.getElementById('uniquePreview');
    const clearTextBtn = document.getElementById('clearTextBtn');
    const importTextFile = document.getElementById('importTextFile');
    const importFileBtn = document.getElementById('importFileBtn');

    let originalText = '';

    // Update counts and preview on input or option changes
    inputText.addEventListener('input', updatePreviewAndCounts);
    trimLinesToggle.addEventListener('change', updatePreviewAndCounts);
    caseSensitiveToggle.addEventListener('change', updatePreviewAndCounts);
    removeEmptyLinesToggle.addEventListener('change', updatePreviewAndCounts);

    removeDuplicatesBtn.addEventListener('click', function() {
        originalText = inputText.value;
        const lines = processLines(inputText.value);
        inputText.value = lines.unique.join('\n');
        restoreOriginalBtn.disabled = false;
        updatePreviewAndCounts();
    });

    restoreOriginalBtn.addEventListener('click', function() {
        if (originalText !== '') {
            inputText.value = originalText;
            restoreOriginalBtn.disabled = true;
            updatePreviewAndCounts();
        }
    });

    copyToClipboardBtn.addEventListener('click', function() {
        navigator.clipboard.writeText(uniquePreview.value).then(() => {
            showToast('Copied to clipboard!', 'success');
        });
    });

    downloadResultBtn.addEventListener('click', function() {
        const blob = new Blob([uniquePreview.value], { type: 'text/plain' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'cleaned-text.txt';
        link.click();
    });

    clearTextBtn.addEventListener('click', function() {
        if (confirm('This will clear the input. Continue?')) {
            inputText.value = '';
            originalText = '';
            restoreOriginalBtn.disabled = true;
            updatePreviewAndCounts();
        }
    });

    importFileBtn.addEventListener('click', function() {
        importTextFile.click();
    });

    importTextFile.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            inputText.value = e.target.result;
            originalText = '';
            restoreOriginalBtn.disabled = true;
            updatePreviewAndCounts();
        };
        reader.readAsText(file);
        this.value = '';
    });

    // Core logic to get stats and preview
    function processLines(text) {
        let lines = text.split('\n');
        if (trimLinesToggle.checked) {
            lines = lines.map(line => line.trim());
        }
        let blankLinesCount = lines.filter(line => line === '').length;
        if (removeEmptyLinesToggle.checked) {
            lines = lines.filter(line => line !== '');
        }
        const caseSensitive = caseSensitiveToggle.checked;
        const seen = new Set();
        const unique = [];
        const lowerMap = new Map();
        for (let line of lines) {
            let key = caseSensitive ? line : line.toLowerCase();
            if (!seen.has(key)) {
                seen.add(key);
                unique.push(line);
                lowerMap.set(key, 1);
            } else {
                lowerMap.set(key, lowerMap.get(key) + 1);
            }
        }
        let duplicateCount = 0;
        for (let v of lowerMap.values()) {
            if (v > 1) duplicateCount += (v-1);
        }
        return {
            total: lines.length,
            unique: unique,
            duplicate: duplicateCount,
            blanks: blankLinesCount
        };
    }

    function updatePreviewAndCounts() {
        const result = processLines(inputText.value);
        totalLines.textContent = inputText.value.split('\n').length;
        uniqueLines.textContent = result.unique.length;
        duplicateLines.textContent = result.duplicate;
        blankLines.textContent = result.blanks;
        uniquePreview.value = result.unique.join('\n');
    }

    // Initial count and preview
    updatePreviewAndCounts();

    // Toast utility (assumes you have showToast globally, else use alert)
    window.showToast = window.showToast || function(msg, type='info') {
        alert(msg);
    };
});
</script>
@endpush