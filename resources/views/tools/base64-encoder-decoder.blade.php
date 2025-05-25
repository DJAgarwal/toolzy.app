@extends('layouts.app')

@section('content')
<div class="mb-4">
    <label for="mainInput" class="form-label fw-semibold">Enter or Paste Text / Base64:</label>
    <textarea class="form-control" id="mainInput" rows="10" placeholder="Enter your text here..."></textarea>
</div>
<div class="mb-4 d-flex flex-wrap gap-3 align-items-center">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="eachLine">
        <label class="form-check-label" for="eachLine">Encode each line separately</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="wrap76">
        <label class="form-check-label" for="wrap76">Split into 76-character chunks (MIME)</label>
    </div>
</div>
<div class="mb-4 d-flex flex-wrap gap-2">
    <button class="btn btn-success" onclick="encodeBase64()">Encode</button>
    <button class="btn btn-secondary" onclick="decodeBase64()">Decode</button>
    <button class="btn btn-outline-primary" onclick="copyToClipboard()">Copy</button>
    <button class="btn btn-outline-dark" onclick="downloadResult()">Download (.txt)</button>
    <label class="btn btn-outline-info mb-0">
        Upload Text File
        <input type="file" hidden accept=".txt,.json" onchange="loadFromFile(event)">
    </label>
</div>
<hr>
<div class="mb-4">
    <label class="form-label fw-semibold">Encode Any File to Base64:</label>
    <input type="file" class="form-control" onchange="encodeFileToBase64(this.files[0])" accept="*/*">
</div>
<div class="mb-4">
    <label class="form-label fw-semibold">Decode Base64 Text to File:</label>
    <div class="input-group">
        <input type="text" class="form-control" id="decodedFileName" placeholder="e.g. image.png">
        <button class="btn btn-outline-secondary" onclick="decodeBase64ToFile()">Download</button>
    </div>
</div>
<hr class="my-4">
<div class="mb-3">
    <label class="form-label fw-semibold">History Log(Stays after leaving the page):</label>
    <div id="historyLog" class="bg-light p-3 border rounded small history-log"></div>
</div>
@endsection

@push('scripts')
<script>
    function encodeBase64() {
        const textarea = document.getElementById('mainInput');
        const wrap76 = document.getElementById('wrap76').checked;
        const eachLine = document.getElementById('eachLine').checked;
        let text = textarea.value.trim();

        if (!text) return showToast('Please enter some text.', 'warning');

        let result;

        if (eachLine) {
            result = text.split('\n').map(line => btoa(line)).join('\n');
        } else {
            result = btoa(text);
        }

        if (wrap76) {
            result = result.match(/.{1,76}/g).join('\n');
        }

        textarea.value = result;
        addToHistory("Encoded");
    }

    function decodeBase64() {
        const textarea = document.getElementById('mainInput');
        const eachLine = document.getElementById('eachLine').checked;
        let text = textarea.value.trim();

        if (!text) return showToast('Please enter Base64 text.', 'warning');

        try {
            let result;

            if (eachLine) {
                result = text.split('\n').map(line => atob(line)).join('\n');
            } else {
                result = atob(text.replace(/\n/g, ''));
            }

            textarea.value = result;
            addToHistory("Decoded");
        } catch {
            showToast('Invalid Base64 input.', 'danger');
        }
    }

    function copyToClipboard() {
        const textarea = document.getElementById('mainInput');
        textarea.select();
        document.execCommand('copy');
        showToast('Copied to clipboard.', 'success');
    }

    function downloadResult() {
        const content = document.getElementById('mainInput').value;
        const blob = new Blob([content], { type: "text/plain" });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = "base64-result.txt";
        a.click();
    }

    function loadFromFile(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('mainInput').value = e.target.result;
        };
        reader.readAsText(file);
    }

    function addToHistory(action) {
        const log = document.getElementById('historyLog');
        const time = new Date().toLocaleTimeString();
        const snippet = document.getElementById('mainInput').value.slice(0, 100).replace(/\n/g, ' ');
        const item = `<div>[${time}] ${action}: ${snippet}</div>`;
        log.innerHTML = item + log.innerHTML;
        localStorage.setItem('base64History', log.innerHTML);
    }

    function loadHistory() {
        const saved = localStorage.getItem('base64History');
        if (saved) document.getElementById('historyLog').innerHTML = saved;
    }
    function encodeFileToBase64(file) {
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function () {
        document.getElementById('mainInput').value = reader.result;
        showToast("File encoded to Base64.", "success");
        addToHistory("File Encoded");
    };
    reader.onerror = function () {
        showToast("Error reading file.", "danger");
    };
    reader.readAsDataURL(file);
}

function decodeBase64ToFile() {
    const base64 = document.getElementById('mainInput').value.trim();
    const filename = document.getElementById('decodedFileName').value.trim() || 'downloaded-file';

    const matches = base64.match(/^data:(.+);base64,(.*)$/);
    if (!matches) {
        showToast("Invalid Base64 format. Expected 'data:[mime];base64,...'", "warning");
        return;
    }

    try {
        const mimeType = matches[1];
        const byteCharacters = atob(matches[2]);
        const byteNumbers = Array.from(byteCharacters, char => char.charCodeAt(0));
        const byteArray = new Uint8Array(byteNumbers);
        const blob = new Blob([byteArray], { type: mimeType });

        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();

        showToast("File downloaded successfully.", "success");
        addToHistory("File Decoded");
    } catch (e) {
        showToast("Decoding failed.", "danger");
    }
}
window.onload = loadHistory;
</script>
@endpush