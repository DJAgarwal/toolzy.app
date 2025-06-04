@extends('layouts.app')

@section('content')
<div class="mb-3">
    <label id="dragDropArea" for="fileInput" class="border fw-semibold rounded p-3 mt-2 text-center text-muted drag-drop-area position-relative w-100" style="cursor:pointer;">
        <span id="dragDropText">Select or Drag &amp; Drop Text Files Here</span>
        <input type="file" id="fileInput" class="form-control d-inline-block w-auto file-input-cover" multiple accept=".txt,.js,.css,.html,.json,.md,.csv,.xml,.log" hidden />
        <div class="form-text text-success mt-2">
            <strong>All actions are done on your device. No files are uploaded to the server.</strong>
        </div>
        <div class="form-text">You can upload and merge multiple text files (.txt, .js, .css, .html, .json, .md, .csv, .xml, .log).</div>
    </label>
</div>
<div class="mb-3 d-flex align-items-center gap-2 flex-wrap d-none" id="outputControls">
    <div class="input-group w-auto">
        <span class="input-group-text">Output Name</span>
        <input type="text" id="outputName" class="form-control" value="merged-file.txt" />
    </div>
    <button class="btn btn-secondary ms-2" id="resetBtn">Reset</button>
</div>
<div class="mb-3 table-responsive">
    <table class="table align-middle mb-0" id="fileTable">
        <thead>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Size</th>
                <th>Preview</th>
                <th>Remove</th>
            </tr>
        </thead>
        <tbody id="fileTbody" class="sortable-table"></tbody>
    </table>
</div>
<div class="mb-3 d-flex gap-3 align-items-center flex-wrap">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="includeSeparator" checked>
        <label class="form-check-label" for="includeSeparator">Add Separator Between Files</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="includeFileName" checked>
        <label class="form-check-label" for="includeFileName">Include File Name in Output</label>
    </div>
    <button class="btn btn-primary" id="mergeFilesBtn">Merge Files</button>
    <div class="progress flex-grow-1 d-none" id="progressBarWrap">
        <div class="progress-bar" id="progressBar" role="progressbar"></div>
    </div>
</div>
<div class="mt-4 d-none" id="downloadSection">
    <label class="form-label fw-semibold">Download Merged File:</label>
    <div class="input-group">
        <input type="text" id="fileName" class="form-control" value="merged-file.txt">
        <button class="btn btn-success" id="downloadMergedFileBtn">Download</button>
    </div>
</div>

<!-- Modal for File Preview -->
<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="filePreviewLabel">File Preview</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
            <pre id="filePreviewText" class="bg-light rounded p-3 text-start" style="max-height:400px;overflow:auto"></pre>
            <div id="filePreviewFileName" class="mt-2 text-secondary small"></div>
        </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileInput');
    const fileTbody = document.getElementById('fileTbody');
    const outputControls = document.getElementById('outputControls');
    const resetBtn = document.getElementById('resetBtn');
    const outputName = document.getElementById('outputName');
    const mergeFilesBtn = document.getElementById('mergeFilesBtn');
    const progressBarWrap = document.getElementById('progressBarWrap');
    const progressBar = document.getElementById('progressBar');
    const downloadSection = document.getElementById('downloadSection');
    const fileNameInput = document.getElementById('fileName');
    const downloadMergedFileBtn = document.getElementById('downloadMergedFileBtn');
    const includeSeparator = document.getElementById('includeSeparator');
    const includeFileName = document.getElementById('includeFileName');
    const filePreviewModal = document.getElementById('filePreviewModal');
    const filePreviewText = document.getElementById('filePreviewText');
    const filePreviewFileName = document.getElementById('filePreviewFileName');
    const dragDropArea = document.getElementById('dragDropArea');
    const dragDropText = document.getElementById('dragDropText');

    let fileList = []; // [{file, name, size, content}]
    let mergedContent = "";

    fileInput.addEventListener('change', handleFilesChanged);
    mergeFilesBtn.addEventListener('click', mergeFiles);
    downloadMergedFileBtn.addEventListener('click', downloadMergedFile);
    resetBtn.addEventListener('click', resetAll);

    // Drag & drop sorting
    let dragIdx = null;

    function handleFilesChanged() {
        for (const file of this.files) {
            if (!fileList.some(f=>f.name===file.name && f.size===file.size)) {
                fileList.push({file, name: file.name, size: file.size, content: null});
            }
        }
        if (fileList.length > 0) {
            outputControls.classList.remove('d-none');
        }
        renderTable();
    }

    function renderTable() {
        fileTbody.innerHTML = '';
        fileList.forEach((f, idx) => {
            const tr = document.createElement('tr');
            tr.setAttribute('draggable', true);
            tr.dataset.idx = idx;

            // Drag handle
            const dragTd = document.createElement('td');
            dragTd.innerHTML = '<span style="cursor:move;" title="Drag to reorder">&#9776;</span>';
            tr.appendChild(dragTd);

            // Name
            const nameTd = document.createElement('td');
            nameTd.textContent = f.name;
            tr.appendChild(nameTd);

            // Size
            const sizeTd = document.createElement('td');
            sizeTd.textContent = humanFileSize(f.size);
            tr.appendChild(sizeTd);

            // Preview button
            const previewTd = document.createElement('td');
            const previewBtn = document.createElement('button');
            previewBtn.className = "btn btn-link btn-sm px-2";
            previewBtn.textContent = "Preview";
            previewBtn.addEventListener('click', () => showFilePreview(f));
            previewTd.appendChild(previewBtn);
            tr.appendChild(previewTd);

            // Remove button
            const remTd = document.createElement('td');
            const remBtn = document.createElement('button');
            remBtn.className = "btn btn-sm btn-danger";
            remBtn.innerHTML = "&times;";
            remBtn.title = "Remove";
            remBtn.addEventListener('click', () => {
                fileList.splice(idx,1);
                renderTable();
                if (fileList.length === 0) {
                    outputControls.classList.add('d-none');
                    downloadSection.classList.add('d-none');
                }
            });
            remTd.appendChild(remBtn);
            tr.appendChild(remTd);

            // Drag events
            tr.addEventListener('dragstart', function(e) {
                dragIdx = idx;
                tr.classList.add('table-active');
            });
            tr.addEventListener('dragend', function() {
                dragIdx = null;
                tr.classList.remove('table-active');
                fileTbody.querySelectorAll('tr').forEach(row => row.classList.remove('drag-over-row'));
            });
            tr.addEventListener('dragover', function(e) {
                e.preventDefault();
                tr.classList.add('drag-over-row');
            });
            tr.addEventListener('dragleave', function() {
                tr.classList.remove('drag-over-row');
            });
            tr.addEventListener('drop', function(e) {
                e.preventDefault();
                tr.classList.remove('drag-over-row');
                const toIdx = idx;
                if (dragIdx !== null && dragIdx !== toIdx) {
                    const moved = fileList.splice(dragIdx,1)[0];
                    fileList.splice(toIdx,0,moved);
                    renderTable();
                }
            });

            fileTbody.appendChild(tr);
        });
    }

    function showFilePreview(f) {
        // If we already loaded, show immediately
        if (f.content !== null) {
            filePreviewText.textContent = f.content.slice(0, 2000) + (f.content.length > 2000 ? "\n...\n" : "");
            filePreviewFileName.textContent = f.name;
            const modal = bootstrap.Modal.getOrCreateInstance(filePreviewModal);
            modal.show();
            return;
        }
        // Else, read and then show
        const reader = new FileReader();
        reader.onload = function(e) {
            f.content = e.target.result;
            filePreviewText.textContent = f.content.slice(0, 2000) + (f.content.length > 2000 ? "\n...\n" : "");
            filePreviewFileName.textContent = f.name;
            const modal = bootstrap.Modal.getOrCreateInstance(filePreviewModal);
            modal.show();
        };
        reader.readAsText(f.file);
    }

    async function mergeFiles() {
        if (!fileList.length) {
            showToast('Please upload at least one file.', 'danger');
            return;
        }
        progressBarWrap.classList.remove('d-none');
        progressBar.style.width = "0%";
        progressBar.textContent = "Reading files...";
        mergeFilesBtn.disabled = true;
        resetBtn.disabled = true;

        let merged = '';
        let filesProcessed = 0;

        // Read all files in order
        for (let i = 0; i < fileList.length; i++) {
            const f = fileList[i];
            let content = f.content;
            if (content === null) {
                // read synchronously
                content = await new Promise(res => {
                    const reader = new FileReader();
                    reader.onload = e => res(e.target.result);
                    reader.readAsText(f.file);
                });
                f.content = content;
            }
            let block = '';
            if (includeSeparator.checked) {
                if (includeFileName.checked) {
                    block += `\n\n/* File: ${f.name} */\n`;
                } else {
                    block += "\n\n/* --- File Separator --- */\n";
                }
            }
            block += content;
            merged += block;

            progressBar.style.width = Math.round(((i+1)/fileList.length)*100) + "%";
            progressBar.textContent = `Merged: ${f.name}`;
        }

        progressBar.style.width = "100%";
        progressBar.textContent = "Ready to download!";
        mergedContent = merged;
        fileNameInput.value = outputName.value?.trim() || "merged-file.txt";
        downloadSection.classList.remove('d-none');
        setTimeout(()=>progressBarWrap.classList.add('d-none'), 1200);

        mergeFilesBtn.disabled = false;
        resetBtn.disabled = false;
        showToast('Files merged successfully!', 'success');
    }

    function downloadMergedFile() {
        const filename = fileNameInput.value || 'merged-file.txt';
        const blob = new Blob([mergedContent], { type: 'text/plain' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    }

    function resetAll() {
        fileList = [];
        mergedContent = '';
        fileTbody.innerHTML = '';
        fileInput.value = '';
        outputName.value = 'merged-file.txt';
        downloadSection.classList.add('d-none');
        progressBarWrap.classList.add('d-none');
        outputControls.classList.add('d-none');
    }

    function humanFileSize(size) {
        if (size < 1024) return size + ' B';
        if (size < 1024*1024) return (size/1024).toFixed(1) + ' KB';
        return (size/1024/1024).toFixed(2) + ' MB';
    }

    // Toast utility
    window.showToast = function(msg, type='info') {
        if (!window.bootstrap) return alert(msg);
        let toast = document.createElement('div');
        toast.className = `toast align-items-center text-bg-${type} border-0 show position-fixed top-0 end-0 m-3`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `<div class="d-flex"><div class="toast-body">${msg}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
        document.body.appendChild(toast);
        setTimeout(() => { toast.classList.remove('show'); setTimeout(()=>toast.remove(), 500); }, 2500);
    }

    // Drag and Drop for dragDropArea
    ['dragenter', 'dragover'].forEach(eventName => {
        dragDropArea.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dragDropArea.classList.add('border-primary', 'bg-light');
            dragDropText.innerHTML = "Drop your files here!";
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dragDropArea.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dragDropArea.classList.remove('border-primary', 'bg-light');
            dragDropText.innerHTML = "Select or Drag &amp; Drop Text Files Here";
        });
    });

    dragDropArea.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const files = e.dataTransfer.files;
        if (files.length) {
            // Use the same logic as handleFilesChanged but append, not overwrite
            let newFiles = [];
            for (const file of files) {
                if (!fileList.some(f=>f.name===file.name && f.size===file.size)) {
                    newFiles.push({file, name: file.name, size: file.size, content: null});
                }
            }
            fileList = fileList.concat(newFiles);
            if (fileList.length > 0) {
                outputControls.classList.remove('d-none');
            }
            renderTable();
        }
    });

    // Clicking the area opens the file dialog
    dragDropArea.addEventListener('click', function(e) {
        if (e.target.tagName !== 'INPUT') {
            fileInput.click();
        }
    });
});
</script>
@endpush