@extends('layouts.app')

@section('content')
<div class="mb-3">
    <label id="dragDropArea" for="pdfFiles" class="form-label fw-semibold border rounded p-3 text-center text-muted drag-drop-area w-100">
        <span>Select or Drag &amp; Drop PDF Files Here</span>
        <input type="file" id="pdfFiles" class="form-control file-input-cover" multiple accept="application/pdf" hidden />
        <div class="form-text">You can upload and merge multiple PDF files. Drag-and-drop supported.</div>
    </label>
</div>
<div class="mb-3 d-flex align-items-center gap-2 flex-wrap">
    <div class="input-group w-auto">
        <span class="input-group-text">Output Name</span>
        <input type="text" id="outputName" class="form-control" value="merged.pdf" />
    </div>
    <button class="btn btn-secondary ms-2" id="resetBtn">Reset</button>
</div>
<div class="mb-3 table-responsive">
    <table class="table align-middle mb-0" id="pdfTable">
        <tbody id="pdfTbody" class="sortable-table"></tbody>
    </table>
</div>
<div class="mb-3 d-flex gap-2 align-items-center flex-wrap">
    <button class="btn btn-primary" id="mergePDFsBtn">Merge PDFs</button>
    <div class="progress flex-grow-1 d-none" id="progressBarWrap">
        <div class="progress-bar" id="progressBar" role="progressbar"></div>
    </div>
</div>
<div class="mt-4 d-none" id="downloadLink">
    <a href="#" class="btn btn-success" id="mergedPdfLink" download="merged.pdf">Download Merged PDF</a>
</div>

<!-- Modal for PDF Preview -->
<div class="modal fade" id="pdfPreviewModal" tabindex="-1" aria-labelledby="pdfPreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="pdfPreviewLabel">PDF Preview</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
            <canvas id="pdfPreviewCanvas" class="img-thumbnail bg-light"></canvas>
            <div id="pdfPreviewFileName" class="mt-2 text-secondary small"></div>
        </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<!-- PDF-lib for merging -->
<script nonce="{{ $cspNonce }}" src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.17.1/pdf-lib.min.js"></script>
<!-- PDF.js for preview thumbnails -->
<script nonce="{{ $cspNonce }}" src="https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.min.js"></script>
<script nonce="{{ $cspNonce }}">
pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.worker.min.js";
</script>
<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function() {
    // ... your DOM element lookups ...
    let pdfList = []; // [{file, url, thumb, size, name, pages}]

    // DRAG & DROP
    dragDropArea.addEventListener('click', () => pdfFiles.click());
    dragDropArea.addEventListener('dragover', e => {
        e.preventDefault();
        dragDropArea.classList.add('bg-info','bg-opacity-10');
    });
    dragDropArea.addEventListener('dragleave', e => {
        e.preventDefault();
        dragDropArea.classList.remove('bg-info','bg-opacity-10');
    });
    dragDropArea.addEventListener('drop', e => {
        e.preventDefault();
        dragDropArea.classList.remove('bg-info','bg-opacity-10');
        handleFiles(e.dataTransfer.files);
    });

    pdfFiles.addEventListener('change', () => {
        handleFiles(pdfFiles.files);
    });

    async function handleFiles(files) {
        for (const file of files) {
            if (!file.type.match(/pdf/)) continue;
            if (pdfList.some(p=>p.name===file.name && p.size===file.size)) continue;

            // Only read the file for thumbnail/preview
            let pages = 0, thumb = "";
            try {
                const arrayBuffer = await file.arrayBuffer();
                const pdfDoc = await pdfjsLib.getDocument({data: new Uint8Array(arrayBuffer)}).promise;
                pages = pdfDoc.numPages;
                const page = await pdfDoc.getPage(1);
                const viewport = page.getViewport({ scale: 0.25 });
                const canvas = document.createElement('canvas');
                canvas.width = viewport.width;
                canvas.height = viewport.height;
                await page.render({canvasContext: canvas.getContext('2d'), viewport}).promise;
                thumb = canvas.toDataURL();
            } catch(e) {
                pages = "?";
                thumb = "";
            }
            pdfList.push({
                file,
                url: URL.createObjectURL(file),
                thumb,
                size: file.size,
                name: file.name,
                pages
            });
        }
        renderTable();
    }

    function renderTable() {
        pdfTbody.innerHTML = '';
        pdfList.forEach((pdf, idx) => {
            const tr = document.createElement('tr');
            tr.setAttribute('draggable', true);
            tr.dataset.idx = idx;

            // Thumbnail
            const thumbTd = document.createElement('td');
            if (pdf.thumb) {
                const img = document.createElement('img');
                img.src = pdf.thumb;
                img.alt = "Preview";
                img.className = "pdf-thumb";
                img.title = "Click to preview";
                img.tabIndex = 0;
                img.addEventListener('click', () => showPreviewModal(pdf));
                thumbTd.appendChild(img);
            }
            tr.appendChild(thumbTd);

            // Name
            const nameTd = document.createElement('td');
            nameTd.textContent = pdf.name;
            tr.appendChild(nameTd);

            // Size
            const sizeTd = document.createElement('td');
            sizeTd.textContent = humanFileSize(pdf.size);
            tr.appendChild(sizeTd);

            // Pages
            const pagesTd = document.createElement('td');
            pagesTd.textContent = pdf.pages;
            tr.appendChild(pagesTd);

            // Remove button
            const remTd = document.createElement('td');
            const remBtn = document.createElement('button');
            remBtn.className = "btn btn-sm btn-danger";
            remBtn.innerHTML = "&times;";
            remBtn.title = "Remove";
            remBtn.addEventListener('click', () => {
                if (pdf.url) URL.revokeObjectURL(pdf.url);
                pdfList.splice(idx,1);
                renderTable();
            });
            remTd.appendChild(remBtn);
            tr.appendChild(remTd);

            // DRAG EVENTS
            tr.addEventListener('dragstart', function(e) {
                tr.classList.add('table-active');
                e.dataTransfer.effectAllowed = "move";
                e.dataTransfer.setData("text/plain", idx);
            });
            tr.addEventListener('dragend', function() {
                tr.classList.remove('table-active');
                const trs = pdfTbody.querySelectorAll("tr");
                trs.forEach(row => row.classList.remove("drag-over-row"));
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
                const from = parseInt(e.dataTransfer.getData("text/plain"));
                const to = idx;
                if (from !== to) {
                    const moved = pdfList.splice(from,1)[0];
                    pdfList.splice(to,0,moved);
                    renderTable();
                }
            });

            pdfTbody.appendChild(tr);
        });
    }

    function showPreviewModal(pdf) {
        // Always read a fresh ArrayBuffer from the File for preview
        pdf.file.arrayBuffer().then(arrayBuffer => {
            return pdfjsLib.getDocument({data: new Uint8Array(arrayBuffer)}).promise;
        }).then(doc => {
            return doc.getPage(1).then(page => {
                const viewport = page.getViewport({scale:1.5});
                pdfPreviewCanvas.width = viewport.width;
                pdfPreviewCanvas.height = viewport.height;
                return page.render({canvasContext: pdfPreviewCanvas.getContext('2d'), viewport}).promise;
            });
        });
        pdfPreviewFileName.textContent = pdf.name;
        const modal = bootstrap.Modal.getOrCreateInstance(pdfPreviewModal);
        modal.show();
    }

    mergePDFsBtn.addEventListener('click', mergePDFs);

    async function mergePDFs() {
        if (!pdfList.length) {
            alert('Please select at least one PDF file.');
            return;
        }
        progressBarWrap.classList.remove('d-none');
        progressBar.style.width = "0%";
        progressBar.textContent = "Starting...";
        mergePDFsBtn.disabled = true;
        resetBtn.disabled = true;

        const mergedPdf = await PDFLib.PDFDocument.create();
        for (let i = 0; i < pdfList.length; i++) {
            const pdf = pdfList[i];
            progressBar.style.width = Math.round((i/pdfList.length)*100) + "%";
            progressBar.textContent = `Merging: ${pdf.name}`;
            // Always read a fresh ArrayBuffer from the File for pdf-lib
            const arrayBuffer = await pdf.file.arrayBuffer();
            const libDoc = await PDFLib.PDFDocument.load(new Uint8Array(arrayBuffer));
            const copiedPages = await mergedPdf.copyPages(libDoc, libDoc.getPageIndices());
            copiedPages.forEach((p) => mergedPdf.addPage(p));
        }
        progressBar.style.width = "100%";
        progressBar.textContent = "Saving merged PDF...";

        const mergedPdfBytes = await mergedPdf.save();
        const blob = new Blob([mergedPdfBytes], { type: 'application/pdf' });
        const url = URL.createObjectURL(blob);

        const downloadName = outputName.value?.trim() || "merged.pdf";
        mergedPdfLink.href = url;
        mergedPdfLink.download = downloadName;
        mergedPdfLink.textContent = `Download ${downloadName}`;

        downloadLink.classList.remove('d-none');
        progressBar.textContent = "Done!";
        setTimeout(()=>progressBarWrap.classList.add('d-none'), 1200);

        mergePDFsBtn.disabled = false;
        resetBtn.disabled = false;
    }

    resetBtn.addEventListener('click', function() {
        pdfList.forEach(pdf => { if (pdf.url) URL.revokeObjectURL(pdf.url); });
        pdfList = [];
        pdfTbody.innerHTML = '';
        pdfFiles.value = '';
        outputName.value = 'merged.pdf';
        downloadLink.classList.add('d-none');
        progressBarWrap.classList.add('d-none');
    });

    function humanFileSize(size) {
        if (size < 1024) return size + ' B';
        if (size < 1024*1024) return (size/1024).toFixed(1) + ' KB';
        return (size/1024/1024).toFixed(2) + ' MB';
    }
});
</script>
@endpush