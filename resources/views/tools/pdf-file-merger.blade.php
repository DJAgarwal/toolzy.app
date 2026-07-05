@extends('layouts.app')

@section('content')
<x-ui-trust-indicator />
<div class="mb-3">
    <label id="dragDropArea" for="pdfFiles" class="border fw-semibold rounded p-3 mt-2 text-center text-muted drag-drop-area position-relative w-100">
        <span>Select or Drag &amp; Drop PDF Files Here</span>
        <input type="file" id="pdfFiles" class="form-control d-inline-block w-auto file-input-cover" multiple accept="application/pdf" hidden />
        <div class="form-text">You can upload and merge multiple PDF files.</div>
    </label>
</div>
<div class="mb-3 table-responsive">
    <table class="table align-middle mb-0" id="pdfTable">
        <tbody id="pdfTbody" class="sortable-table"></tbody>
    </table>
</div>
<div class="mb-3 d-flex align-items-center gap-2 flex-wrap d-none" id="outputControls">
    <div class="input-group w-auto">
        <span class="input-group-text">Output Name</span>
        <input type="text" id="outputName" class="form-control" value="merged.pdf" />
    </div>
    <button class="btn btn-secondary ms-2" id="resetBtn">Reset</button>
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

<hr class="my-5 opacity-25">

<div class="row g-4 mt-2">
    <div class="col-lg-12">
        <h3 class="fw-bold mb-4 text-dark text-center">How to Merge PDF Files Online in 4 Simple Steps</h3>
        <div class="row g-3 justify-content-center">
            <div class="col-md-3">
                <div class="card h-100 border-0 bg-light p-3 text-center">
                    <div class="d-flex justify-content-center mb-3">
                        <span class="badge bg-primary rounded-circle fs-5 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">1</span>
                    </div>
                    <h5 class="fw-semibold text-dark">Upload Files</h5>
                    <p class="text-muted small mb-0">Click the drag-and-drop area to select files from your computer or mobile device, or simply drag them directly into the browser window.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-0 bg-light p-3 text-center">
                    <div class="d-flex justify-content-center mb-3">
                        <span class="badge bg-primary rounded-circle fs-5 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">2</span>
                    </div>
                    <h5 class="fw-semibold text-dark">Arrange Order</h5>
                    <p class="text-muted small mb-0">Rearrange the files into the desired sequence by dragging and dropping them up or down. Preview documents using their thumbnails if needed.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-0 bg-light p-3 text-center">
                    <div class="d-flex justify-content-center mb-3">
                        <span class="badge bg-primary rounded-circle fs-5 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">3</span>
                    </div>
                    <h5 class="fw-semibold text-dark">Merge Documents</h5>
                    <p class="text-muted small mb-0">Give your final file a name (optional) and click the <strong>"Merge PDFs"</strong> button. The merging process runs locally and completes in seconds.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-0 bg-light p-3 text-center">
                    <div class="d-flex justify-content-center mb-3">
                        <span class="badge bg-primary rounded-circle fs-5 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">4</span>
                    </div>
                    <h5 class="fw-semibold text-dark">Download Instantly</h5>
                    <p class="text-muted small mb-0">Once the process is complete, click <strong>"Download Merged PDF"</strong> to save the combined document directly to your device.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<hr class="my-5 opacity-25">

<div class="row g-4 mt-2">
    <div class="col-lg-6">
        <h3 class="fw-bold mb-3 text-dark">Why Use Toolzy to Combine Your PDFs?</h3>
        <p class="text-muted">
            Managing multiple individual PDF documents can be frustrating and inefficient. Whether you are dealing with business records, academic journals, personal tax documents, or eBook drafts, combining them simplifies your digital filing.
        </p>
        <p class="text-muted">
            Toolzy’s PDF File Merger eliminates the complications of traditional conversion tools. Unlike cloud utilities that require uploading files, our tool guarantees <strong>maximum security</strong> because your documents never leave your computer. Additionally, we don't stamp watermarks on your professional files, nor do we restrict usage with arbitrary caps or paywalls.
        </p>
        <p class="text-muted">
            Use cases where merging PDFs is particularly beneficial:
        </p>
        <ul class="text-muted list-unstyled ps-0">
            <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i><strong>Academic Research:</strong> Keep research papers, reference sheets, and study guides in a single convenient file.</li>
            <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i><strong>Financial Statements:</strong> Group monthly banking records, invoice history, and receipts for tax season.</li>
            <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i><strong>Legal Documents:</strong> Assemble multiple clauses, contracts, and addendums in a sequential order for signature.</li>
            <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i><strong>eBooks & Presentations:</strong> Combine chapters and reports before distributing or publishing.</li>
        </ul>
    </div>
    <div class="col-lg-6">
        <h3 class="fw-bold mb-3 text-dark">Features & Key Advantages</h3>
        <div class="row g-3">
            <div class="col-sm-6">
                <div class="d-flex align-items-start">
                    <div class="bg-primary bg-opacity-10 text-primary rounded p-2 me-3">
                        <i class="bi bi-shield-lock-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1 text-dark">100% Client-Side Privacy</h6>
                        <p class="text-muted small mb-0">Files are processed in memory inside your browser. No files are uploaded to our web servers.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="d-flex align-items-start">
                    <div class="bg-primary bg-opacity-10 text-primary rounded p-2 me-3">
                        <i class="bi bi-x-circle-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1 text-dark">Zero Watermarks Added</h6>
                        <p class="text-muted small mb-0">We never compromise your documents with ugly watermarks or logos. Completely clean files.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="d-flex align-items-start">
                    <div class="bg-primary bg-opacity-10 text-primary rounded p-2 me-3">
                        <i class="bi bi-lightning-charge-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1 text-dark">Ultra-Fast Merging</h6>
                        <p class="text-muted small mb-0">Leverages the speed of your device memory to compile page streams in a matter of seconds.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="d-flex align-items-start">
                    <div class="bg-primary bg-opacity-10 text-primary rounded p-2 me-3">
                        <i class="bi bi-sort-down fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1 text-dark">Intuitive Drag & Reorder</h6>
                        <p class="text-muted small mb-0">Drag rows easily to rearrange sequence before joining files together.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="d-flex align-items-start">
                    <div class="bg-primary bg-opacity-10 text-primary rounded p-2 me-3">
                        <i class="bi bi-eye-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1 text-dark">Cover Page Preview</h6>
                        <p class="text-muted small mb-0">Inspect cover page thumbnails and preview PDFs in full width to avoid mistakes.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="d-flex align-items-start">
                    <div class="bg-primary bg-opacity-10 text-primary rounded p-2 me-3">
                        <i class="bi bi-infinity fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1 text-dark">No Usage Restraints</h6>
                        <p class="text-muted small mb-0">No registrations, no logins, no limits on number of PDF files or total file size.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<hr class="my-5 opacity-25">

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
    // DOM lookups
    const dragDropArea = document.getElementById('dragDropArea');
    const pdfFiles = document.getElementById('pdfFiles');
    const pdfTbody = document.getElementById('pdfTbody');
    const mergePDFsBtn = document.getElementById('mergePDFsBtn');
    const resetBtn = document.getElementById('resetBtn');
    const progressBarWrap = document.getElementById('progressBarWrap');
    const progressBar = document.getElementById('progressBar');
    const downloadLink = document.getElementById('downloadLink');
    const mergedPdfLink = document.getElementById('mergedPdfLink');
    const outputName = document.getElementById('outputName');
    const pdfPreviewModal = document.getElementById('pdfPreviewModal');
    const pdfPreviewCanvas = document.getElementById('pdfPreviewCanvas');
    const pdfPreviewFileName = document.getElementById('pdfPreviewFileName');
    const outputControls = document.getElementById('outputControls');
    let pdfList = [];

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
        let added = false;
        for (const file of files) {
            if (!file.type.match(/pdf/)) continue;
            if (pdfList.some(p=>p.name===file.name && p.size===file.size)) continue;
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
            added = true;
        }
        if (pdfList.length > 0) {
            outputControls.classList.remove('d-none');
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

            // // Size
            // const sizeTd = document.createElement('td');
            // sizeTd.textContent = "Size: ".humanFileSize(pdf.size);
            // tr.appendChild(sizeTd);

            // Pages
            const pagesTd = document.createElement('td');
            pagesTd.textContent = "Pages: "+pdf.pages;
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
                if (pdfList.length === 0) {
                    outputControls.classList.add('d-none');
                }
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
            showToast('Please select at least one PDF file.', 'danger');
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
        outputControls.classList.add('d-none');
    });

    function humanFileSize(size) {
        if (size < 1024) return size + ' B';
        if (size < 1024*1024) return (size/1024).toFixed(1) + ' KB';
        return (size/1024/1024).toFixed(2) + ' MB';
    }
});
</script>
@endpush