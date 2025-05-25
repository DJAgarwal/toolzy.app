@extends('layouts.app')

@section('content')
<div class="mb-4">
    <label for="mainInput" class="form-label fw-semibold">Enter Text:</label>
    <textarea class="form-control" id="mainInput" rows="6" placeholder="Type or paste your text here..."></textarea>
</div>
<div class="mb-4 d-flex flex-wrap gap-3">
    <button class="btn btn-primary" onclick="encodeText()">Encode</button>
    <button class="btn btn-secondary" onclick="decodeText()">Decode</button>
    <button class="btn btn-outline-dark" onclick="copyText()">Copy</button>
    <button class="btn btn-outline-danger" onclick="clearText()">Clear</button>
    <button class="btn btn-outline-success" onclick="downloadResult()">Download</button>
    <label class="btn btn-outline-info mb-0">
        Upload <input type="file" hidden accept=".txt,.json" onchange="uploadFile(event)">
    </label>
</div>
<div class="mb-4 d-flex flex-wrap gap-4 align-items-center">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="useBase64">
        <label class="form-check-label" for="useBase64">Use Base64</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="encodeEachLine">
        <label class="form-check-label" for="encodeEachLine">Encode each line separately</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="chunk76">
        <label class="form-check-label" for="chunk76">Split into 76-character chunks(For MIME format)</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="fullEncode">
        <label class="form-check-label" for="fullEncode">Use full encode (non-standard)</label>
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
    function encodeText() {
        const input = document.getElementById('mainInput');
        const useBase64 = document.getElementById('useBase64').checked;
        const encodeEachLine = document.getElementById('encodeEachLine').checked;
        const chunk76 = document.getElementById('chunk76').checked;
        const fullEncode = document.getElementById('fullEncode').checked;

        let data = input.value;
        if (!data) {
            showToast("Please enter some text.", "warning");
            return;
        }

        let result = "";

        if (encodeEachLine) {
            const lines = data.split(/\r?\n/);
            result = lines.map(line => encodeLine(line)).join('\n');
        } else {
            result = encodeLine(data);
        }

        if (chunk76) {
            result = result.match(/.{1,76}/g).join('\n');
        }

        input.value = result;
        addToHistory('Encoded');
    }

    function encodeLine(text) {
        const useBase64 = document.getElementById('useBase64').checked;
        const fullEncode = document.getElementById('fullEncode').checked;

        if (useBase64) {
            return btoa(text);
        }

        return fullEncode
            ? Array.from(text).map(c => '%' + c.charCodeAt(0).toString(16).padStart(2, '0')).join('')
            : encodeURIComponent(text);
    }

    function decodeText() {
    const input = document.getElementById('mainInput');
    const useBase64 = document.getElementById('useBase64').checked;
    const decodeEachLine = document.getElementById('encodeEachLine').checked;

    let data = input.value;
    if (!data.trim()) {
        showToast("Please enter some text.", "warning");
        return;
    }

    try {
        let result;

        if (decodeEachLine) {
            const lines = data.split(/\r?\n/);
            result = lines.map(line => useBase64 ? atob(line) : decodeURIComponent(line)).join('\n');
        } else {
            result = useBase64 ? atob(data) : decodeURIComponent(data);
        }

        input.value = result;
        addToHistory('Decoded');
    } catch (e) {
        showToast("Invalid input for decoding.", "danger");
    }
}

    function copyText() {
        const input = document.getElementById('mainInput');
        if (!input.value.trim()) {
            showToast("Nothing to copy!", "danger");
            return;
        }
        navigator.clipboard.writeText(input.value).then(() => {
            showToast("Copied to clipboard!", "success");
        });
    }

    function clearText() {
        document.getElementById('mainInput').value = '';
        showToast("Cleared input.", "info");
    }

    function downloadResult() {
        const text = document.getElementById('mainInput').value;
        if (!text.trim()) {
            showToast("Nothing to download.", "warning");
            return;
        }
        const blob = new Blob([text], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'encoded-output.txt';
        a.click();
        URL.revokeObjectURL(url);
        showToast("Download started.", "success");
    }

    function uploadFile(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (event) {
            document.getElementById('mainInput').value = event.target.result;
            showToast("File uploaded.", "success");
        };
        reader.readAsText(file);
    }

    function addToHistory(action) {
    const log = document.getElementById('historyLog');
    const time = new Date().toLocaleTimeString();
    const inputText = document.getElementById('mainInput').value.slice(0, 100).replace(/\n/g, ' ');
    const entry = `[${time}] ${action}: ${inputText}`;
    log.innerHTML = `<div>${entry}</div>` + log.innerHTML;
    let history = JSON.parse(localStorage.getItem('toolHistory')) || [];
    history.unshift(entry);
    if (history.length > 50) history.pop();
    localStorage.setItem('toolHistory', JSON.stringify(history));
}

function loadHistory() {
    const log = document.getElementById('historyLog');
    const history = JSON.parse(localStorage.getItem('toolHistory')) || [];
    log.innerHTML = history.map(entry => `<div>${entry}</div>`).join('');
}

window.onload = loadHistory;
</script>
@endpush