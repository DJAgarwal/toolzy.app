@extends('layouts.app')

@section('content')
            <div class="mb-3">
                <label for="pdfFiles" class="form-label fw-semibold">Select PDF Files</label>
                <input type="file" id="pdfFiles" class="form-control" multiple accept="application/pdf">
                <div class="form-text">You can upload and merge multiple PDF files.</div>
            </div>
            <div id="fileList" class="mb-3"></div>
            <div class="mb-3">
                <button class="btn btn-primary" onclick="mergePDFs()">Merge PDFs</button>
            </div>
            <div class="mt-4 d-none" id="downloadLink">
                <a href="#" class="btn btn-success" id="mergedPdfLink" download="merged.pdf">Download Merged PDF</a>
            </div>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}" src="https://unpkg.com/pdf-lib/dist/pdf-lib.min.js"></script>

<script nonce="{{ $cspNonce }}">
    const fileInput = document.getElementById('pdfFiles');
    const fileList = document.getElementById('fileList');

    fileInput.addEventListener('change', () => {
        fileList.innerHTML = '';
        Array.from(fileInput.files).forEach((file, i) => {
            fileList.innerHTML += `<div>${i + 1}. ${file.name}</div>`;
        });
    });

    async function mergePDFs() {
        const files = fileInput.files;
        if (!files.length) {
            alert('Please select at least one PDF file.');
            return;
        }

        const mergedPdf = await PDFLib.PDFDocument.create();

        for (let file of files) {
            const arrayBuffer = await file.arrayBuffer();
            const pdf = await PDFLib.PDFDocument.load(arrayBuffer);
            const copiedPages = await mergedPdf.copyPages(pdf, pdf.getPageIndices());
            copiedPages.forEach((page) => mergedPdf.addPage(page));
        }

        const mergedPdfBytes = await mergedPdf.save();
        const blob = new Blob([mergedPdfBytes], { type: 'application/pdf' });
        const url = URL.createObjectURL(blob);

        const downloadLink = document.getElementById('mergedPdfLink');
        downloadLink.href = url;
        document.getElementById('downloadLink').classList.remove('d-none');
    }
</script>
@endpush