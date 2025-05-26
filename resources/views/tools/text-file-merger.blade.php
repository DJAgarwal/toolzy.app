@extends('layouts.app')

@section('content')
<div class="mb-3">
    <label for="fileInput" class="form-label fw-semibold">Upload Text Files to Merge(.txt,.js,.css,.html,.json,.md)</label>
    <input type="file" id="fileInput" class="form-control" multiple accept=".txt,.js,.css,.html,.json,.md">
</div>
<div class="mb-3" id="fileList"></div>
<div class="mb-3">
    <button class="btn btn-primary btn-lg" onclick="mergeFiles()">Merge Files</button>
</div>
<div class="d-none" id="downloadSection">
    <label class="form-label fw-semibold">Download Merged File:</label>
    <div class="input-group">
        <input type="text" id="fileName" class="form-control" value="merged-file.txt">
        <button class="btn btn-success" onclick="downloadMergedFile()">Download</button>
    </div>
</div>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
let mergedContent = '';

document.getElementById('fileInput').addEventListener('change', function() {
    const fileList = document.getElementById('fileList');
    fileList.innerHTML = '';

    for (const file of this.files) {
        const item = document.createElement('div');
        item.className = 'mb-1 text-muted small';
        item.textContent = file.name;
        fileList.appendChild(item);
    }
});

function mergeFiles() {
    const input = document.getElementById('fileInput');
    if (!input.files.length) {
        showToast('Please upload at least one file.', 'danger');
        return;
    }

    mergedContent = '';
    let filesProcessed = 0;

    Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            mergedContent += `\n\n/* File: ${file.name} */\n` + e.target.result;
            filesProcessed++;
            if (filesProcessed === input.files.length) {
                document.getElementById('downloadSection').classList.remove('d-none');
                showToast('Files merged successfully!', 'success');
            }
        };
        reader.readAsText(file);
    });
}

function downloadMergedFile() {
    const filename = document.getElementById('fileName').value || 'merged-file.txt';
    const blob = new Blob([mergedContent], { type: 'text/plain' });
    const link = document.createElement('a');

    link.href = URL.createObjectURL(blob);
    link.download = filename;
    link.click();
}
</script>
@endpush