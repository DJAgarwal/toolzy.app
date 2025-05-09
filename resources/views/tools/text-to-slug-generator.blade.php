@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center fw-bold">Text to Slug Generator - Toolzy</h1>
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label for="textInput" class="form-label fw-semibold">Enter Text or Lines (Bulk Supported):</label>
                        <textarea class="form-control" id="textInput" rows="4" placeholder="Enter one or more lines of text..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Options:</label>
                        <div class="d-flex flex-wrap gap-3 align-items-center">
                            <div>
                                <label class="me-1">Separator:</label>
                                <select id="separatorSelect" class="form-select form-select-sm d-inline-block w-auto" onchange="generateSlug()">
                                    <option value="-">-</option>
                                    <option value="_">_</option>
                                    <option value=".">.</option>
                                </select>
                            </div>
                            <div>
                                <input class="form-check-input" type="checkbox" id="removeStopWords" onchange="generateSlug()">
                                <label class="form-check-label" for="removeStopWords">Remove Stop Words(the, and, of etc.)</label>
                            </div>
                        </div>
                    </div>

                    <!-- Output -->
                    <div class="mb-4">
                        <label for="slugOutput" class="form-label fw-semibold">Generated Slugs:</label>
                        <textarea class="form-control" id="slugOutput" rows="4" readonly></textarea>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                            <!-- <button class="btn btn-primary" onclick="generateSlug()">Generate</button> -->
                            <button class="btn btn-outline-secondary" onclick="copySlug()">Copy</button>
                            <button class="btn btn-outline-success" onclick="downloadSlug()">Download (.txt)</button>
                        </div>

                    <!-- Slug History -->
                    <div>
                        <label class="form-label fw-semibold">Slug History(Stays after leaving the page):</label>
                        <ul class="list-group" id="slugHistory"></ul>
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
const stopWords = new Set([
    'the', 'and', 'of', 'a', 'an', 'in', 'on', 'with', 'to', 'at', 'for', 'from', 'by', 'is', 'are', 'was', 'were', 'it', 'this', 'that'
]);

document.getElementById('textInput').addEventListener('input', generateSlug);

function generateSlug() {
    const input = document.getElementById('textInput').value.trim();
    const separator = document.getElementById('separatorSelect').value;
    const removeStops = document.getElementById('removeStopWords').checked;

    if (!input) {
        document.getElementById('slugOutput').value = '';
        return;
    }

    const lines = input.split('\n');
    const slugs = lines.map(line => {
        let words = line.trim().toLowerCase().split(/\s+/);
        if (removeStops) {
            words = words.filter(word => !stopWords.has(word));
        }
        return words.join(separator).replace(/[^\w\-._~]/g, '');
    });

    const output = slugs.join('\n');
    document.getElementById('slugOutput').value = output;
    saveToHistory(slugs);
}

function copySlug() {
    const slugField = document.getElementById('slugOutput');
    if (!slugField.value) {
        showToast("Nothing to copy!", "danger");
        return;
    }
    navigator.clipboard.writeText(slugField.value).then(() => {
        showToast("Slug copied to clipboard!", "success");
    });
}

function downloadSlug() {
    const content = document.getElementById('slugOutput').value;
    if (!content) {
        showToast("Nothing to download!", "danger");
        return;
    }
    const blob = new Blob([content], { type: 'text/plain' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'slug.txt';
    a.click();
    showToast("Slug downloaded!", "success");
}

function saveToHistory(slugs) {
    const history = JSON.parse(localStorage.getItem('slugHistory') || '[]');
    const updated = [...new Set([...slugs, ...history])].slice(0, 10);
    localStorage.setItem('slugHistory', JSON.stringify(updated));
    renderHistory();
}

function renderHistory() {
    const historyList = document.getElementById('slugHistory');
    const history = JSON.parse(localStorage.getItem('slugHistory') || '[]');
    historyList.innerHTML = history.length
        ? history.map(s => `<li class="list-group-item">${s}</li>`).join('')
        : '<li class="list-group-item text-muted">No history yet.</li>';
}

// On page load
renderHistory();

function showToast(message, type = "success") {
    const toastEl = document.getElementById("mainToast");
    const toastBody = document.getElementById("mainToastBody");

    toastBody.textContent = message;
    toastEl.className = `toast align-items-center text-white bg-${type} border-0`;

    const bsToast = new bootstrap.Toast(toastEl);
    bsToast.show();
}
</script>
@endpush