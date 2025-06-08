@extends('layouts.app')

@section('content')
<div class="mb-4">
    <label for="qrContent" class="form-label fw-semibold">Enter Text or URL:</label>
    <input type="text" class="form-control" id="qrContent" placeholder="Enter text or URL" aria-label="QR Code Content" autocomplete="off">
</div>
<div class="mb-4">
    <label class="form-label fw-semibold">QR Customization:</label>
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label" for="qrSize">Size (px)</label>
            <select class="form-select" id="qrSize" aria-label="QR Code Size">
                <option value="150">150 × 150</option>
                <option value="200">200 × 200</option>
                <option value="250">250 × 250</option>
                <option value="300" selected>300 × 300 (Recommended)</option>
                <option value="400">400 × 400</option>
                <option value="500">500 × 500</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label mb-2" for="colorDark">Colors</label>
            <div class="d-flex gap-2">
                <input type="color" class="form-control form-control-color color-input-round" id="colorDark" value="#000000" aria-label="Color 1" title="Select foreground color">
                <input type="color" class="form-control form-control-color color-input-round" id="colorLight" value="#ffffff" aria-label="Color 2"title="Select background color">
            </div>
        </div>
        <div class="col-md-2">
            <label class="form-label" for="dotType">Dot Shape</label>
            <select class="form-select" id="dotType" aria-label="Dot Shape">
                <option value="square" selected>Square</option>
                <option value="rounded">Rounded</option>
                <option value="dots">Dots</option>
                <option value="classy">Classy</option>
                <option value="classy-rounded">Classy Rounded</option>
                <option value="extra-rounded">Extra Rounded</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label" for="eyeType">Eye Shape</label>
            <select class="form-select" id="eyeType" aria-label="Eye Shape">
                <option value="square" selected>Square</option>
                <option value="circle">Circle</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label" for="qrLogo">Logo (optional)</label>
            <input type="file" class="form-control" id="qrLogo" accept="image/*" aria-label="QR Code Logo">
        </div>
    </div>
</div>
<div class="mb-4">
    <label class="form-label fw-semibold" for="batchInput">Batch Generate (one QR per line):</label>
    <textarea id="batchInput" class="form-control" rows="4" placeholder="Paste multiple URLs/texts here for batch QR code generation..." aria-label="Batch QR Code Input"></textarea>
    <button class="btn btn-outline-primary mt-2" id="batchGenerateBtn" aria-label="Generate QRs for batch input">Batch Generate</button>
</div>
<div class="mb-4 text-center" id="qrCodePreview" aria-label="QR Code Preview" tabindex="0">
    <!-- QR Code will appear here -->
</div>
<div class="d-flex gap-2 flex-wrap mb-3 justify-content-center">
    <button class="btn btn-outline-success" id="copyQRCodeBtn" aria-label="Copy QR Code to Clipboard">Copy to Clipboard</button>
    <button class="btn btn-outline-dark" id="downloadQRCodePngBtn" aria-label="Download QR Code PNG">Download PNG</button>
    <button class="btn btn-outline-secondary" id="downloadQRCodeSvgBtn" aria-label="Download QR Code SVG">Download SVG</button>
    <button class="btn btn-outline-info" id="printQRCodeBtn" aria-label="Print QR Code">Print</button>
</div>
<div class="mb-4" id="batchResults" style="display:none">
    <h5>Batch Results</h5>
    <div class="d-flex flex-wrap gap-3" id="batchResultsGrid" aria-label="Batch QR Results"></div>
</div>
<div class="mb-4" id="qrHistorySection">
    <h5>History</h5>
    <div class="d-flex flex-wrap gap-3" id="qrHistoryGrid" aria-label="QR Code History"></div>
</div>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}" src="https://cdn.jsdelivr.net/npm/qr-code-styling@1.5.0/lib/qr-code-styling.js"></script>
<script nonce="{{ $cspNonce }}">
let qrCode = null;
let qrLogoImage = null;
const qrHistoryKey = 'qrCodeHistory-v2';

// --- CARD BUILDER FOR BATCH & HISTORY ---
function buildQrCard({qrcode, text, size, extraBtn=null}) {
    const card = document.createElement('div');
    card.className = 'p-2 border rounded bg-white text-center d-flex flex-column align-items-center shadow-sm';
    card.style.width = (size + 20) + 'px';
    card.style.minWidth = '180px';
    card.style.maxWidth = '100%';

    const qrDiv = document.createElement('div');
    qrDiv.style.width = size + 'px';
    qrDiv.style.height = size + 'px';
    qrDiv.style.margin = '0 auto';
    qrcode.append(qrDiv);
    card.appendChild(qrDiv);

    const small = document.createElement('div');
    small.className = 'small mt-2 text-break';
    small.style.whiteSpace = 'pre-wrap';
    small.style.wordBreak = 'break-all';
    small.innerText = text;
    card.appendChild(small);

    const btnGroup = document.createElement('div');
    btnGroup.className = 'btn-group mt-2 flex-wrap justify-content-center';
    btnGroup.style.flexWrap = 'wrap';

    // Download PNG
    const dlBtn = document.createElement('button');
    dlBtn.className = 'btn btn-outline-dark btn-sm';
    dlBtn.innerText = 'PNG';
    dlBtn.setAttribute('aria-label', 'Download PNG');
    dlBtn.addEventListener('click', () => qrcode.download({name: 'qr-batch', extension: 'png'}));
    btnGroup.appendChild(dlBtn);

    // Download SVG
    const svgBtn = document.createElement('button');
    svgBtn.className = 'btn btn-outline-secondary btn-sm';
    svgBtn.innerText = 'SVG';
    svgBtn.setAttribute('aria-label', 'Download SVG');
    svgBtn.addEventListener('click', () => qrcode.download({name: 'qr-batch', extension: 'svg'}));
    btnGroup.appendChild(svgBtn);

    // Optional extra button (e.g., Edit for history)
    if (extraBtn) btnGroup.appendChild(extraBtn);

    card.appendChild(btnGroup);

    return card;
}

// --- CORE UTILS ---
function getQrOptions(extra={}) {
    const text = document.getElementById('qrContent').value.trim();
    const size = parseInt(document.getElementById('qrSize').value);
    const colorDark = document.getElementById('colorDark').value;
    const colorLight = document.getElementById('colorLight').value;
    const dotType = document.getElementById('dotType').value;
    const eyeType = document.getElementById('eyeType').value;
    let logo = qrLogoImage;

    return {
        width: size,
        height: size,
        data: text,
        image: logo,
        dotsOptions: {
            color: colorDark,
            type: dotType
        },
        cornersSquareOptions: {
            type: eyeType
        },
        backgroundOptions: {
            color: colorLight,
        },
        qrOptions: {
            errorCorrectionLevel: 'H'
        },
        ...extra
    };
}

// --- QR PREVIEW ---
function renderQRCodePreview(options=null) {
    const text = document.getElementById('qrContent').value.trim();
    const container = document.getElementById("qrCodePreview");
    if (!text) {
        container.innerHTML = '<div class="text-muted">QR Code will appear here…</div>';
        qrCode = null;
        return;
    }
    const opts = options || getQrOptions();
    qrCode = new QRCodeStyling(opts);
    container.innerHTML = '';
    qrCode.append(container);
}

// --- HISTORY ---
function addToHistory() {
    const text = document.getElementById('qrContent').value.trim();
    if (!text) return;
    const size = parseInt(document.getElementById('qrSize').value);
    const colorDark = document.getElementById('colorDark').value;
    const colorLight = document.getElementById('colorLight').value;
    const dotType = document.getElementById('dotType').value;
    const eyeType = document.getElementById('eyeType').value;
    const logo = qrLogoImage;
    let history = [];
    try {
        history = JSON.parse(localStorage.getItem(qrHistoryKey)) || [];
    } catch {}
    // Avoid duplicates by text and settings
    history = history.filter(
        h => !(h.text === text && h.size === size && h.colorDark === colorDark && h.colorLight === colorLight && h.dotType === dotType && h.eyeType === eyeType)
    );
    history.unshift({
        text, size, colorDark, colorLight, dotType, eyeType, logo, ts: Date.now()
    });
    if (history.length > 12) history = history.slice(0,12);
    localStorage.setItem(qrHistoryKey, JSON.stringify(history));
    renderHistory();
}

function renderHistory() {
    let history = [];
    try {
        history = JSON.parse(localStorage.getItem(qrHistoryKey)) || [];
    } catch {}
    const grid = document.getElementById('qrHistoryGrid');
    grid.innerHTML = '';
    if (!history.length) {
        grid.innerHTML = '<div class="text-muted">No QR codes in history.</div>';
        return;
    }
    history.forEach((item) => {
        const options = {
            width: item.size,
            height: item.size,
            data: item.text,
            image: item.logo,
            dotsOptions: { color: item.colorDark, type: item.dotType },
            cornersSquareOptions: { type: item.eyeType },
            backgroundOptions: { color: item.colorLight },
            qrOptions: { errorCorrectionLevel: 'H' }
        };
        const qrcode = new QRCodeStyling(options);
        const editBtn = document.createElement('button');
        editBtn.className = 'btn btn-outline-primary btn-sm';
        editBtn.innerText = 'Edit';
        editBtn.setAttribute('aria-label', 'Load QR to edit');
        editBtn.addEventListener('click', () => {
            document.getElementById('qrContent').value = item.text;
            document.getElementById('qrSize').value = item.size;
            document.getElementById('colorDark').value = item.colorDark;
            document.getElementById('colorLight').value = item.colorLight;
            document.getElementById('dotType').value = item.dotType;
            document.getElementById('eyeType').value = item.eyeType;
            qrLogoImage = item.logo;
            livePreviewAndHistory();
            document.getElementById('qrContent').focus();
        });

        const card = buildQrCard({qrcode, text: item.text, size: item.size, extraBtn: editBtn});
        grid.appendChild(card);
    });
}

// --- BATCH GENERATE ---
function batchGenerate() {
    const lines = document.getElementById('batchInput').value.split('\n').map(l=>l.trim()).filter(Boolean);
    const batchGrid = document.getElementById('batchResultsGrid');
    batchGrid.innerHTML = '';
    if (!lines.length) {
        showToast('No input for batch generation.', 'danger');
        return;
    }
    const baseOptions = getQrOptions({data:''});
    lines.forEach((line, idx) => {
        const options = {...baseOptions, data: line};
        const qrcode = new QRCodeStyling(options);
        const card = buildQrCard({qrcode, text: line, size: baseOptions.width});
        batchGrid.appendChild(card);
    });
    document.getElementById('batchResults').style.display = '';
}

// --- ACTION BUTTONS ---
function downloadQRCode(format = 'png') {
    if (!qrCode) {
        showToast('Generate a QR Code first.', 'danger');
        return;
    }
    qrCode.download({ name: "qr-code", extension: format });
}
function copyQRCode() {
    const container = document.getElementById("qrCodePreview");
    const svgEl = container.querySelector("svg");
    const canvasEl = container.querySelector("canvas");

    if (svgEl) {
        const svgBlob = new Blob([svgEl.outerHTML], { type: "image/svg+xml" });
        navigator.clipboard.write([
            new ClipboardItem({ [svgBlob.type]: svgBlob })
        ]).then(() => {
            showToast('QR Code copied to clipboard (SVG).', 'success');
        }).catch(() => {
            showToast('Copy failed.', 'danger');
        });
    } else if (canvasEl) {
        canvasEl.toBlob(blob => {
            navigator.clipboard.write([
                new ClipboardItem({ [blob.type]: blob })
            ]).then(() => {
                showToast('QR Code copied to clipboard (PNG).', 'success');
            }).catch(() => {
                showToast('Copy failed.', 'danger');
            });
        });
    } else {
        showToast('Generate a QR Code first.', 'danger');
    }
}
function printQRCode() {
    const container = document.getElementById("qrCodePreview");
    const canvasEl = container.querySelector("canvas");
    const svgEl = container.querySelector("svg");

    if (canvasEl) {
        // Open a new window and write the image
        const dataUrl = canvasEl.toDataURL();
        const win = window.open('', '', 'width=600,height=700');
        win.document.write('<html><head><title>Print QR</title></head><body style="text-align:center; margin:0;">');
        win.document.write('<img src="' + dataUrl + '" style="display:block; margin:auto; max-width:100%;"/>');
        win.document.write('</body></html>');
        win.document.close();
        win.focus();
        win.onload = function() {
            win.print();
        };
    } else if (svgEl) {
        const svgData = new XMLSerializer().serializeToString(svgEl);
        const svgBlob = new Blob([svgData], {type: 'image/svg+xml'});
        const url = URL.createObjectURL(svgBlob);
        const win = window.open('', '', 'width=600,height=700');
        win.document.write('<html><head><title>Print QR</title></head><body style="text-align:center; margin:0;">');
        win.document.write('<img src="' + url + '" style="display:block; margin:auto; max-width:100%;"/>');
        win.document.write('</body></html>');
        win.document.close();
        win.focus();
        win.onload = function() {
            win.print();
            URL.revokeObjectURL(url);
        };
    } else {
        showToast('Generate a QR Code first.', 'danger');
    }
}

// --- LIVE PREVIEW & HISTORY ---
function livePreviewAndHistory() {
    renderQRCodePreview();
    addToHistory();
}

// --- INIT ---
document.addEventListener('DOMContentLoaded', function() {
    // Live preview & history update on all changes
    ['input', 'change'].forEach(evt => {
        document.getElementById('qrContent').addEventListener(evt, livePreviewAndHistory);
        document.getElementById('qrSize').addEventListener(evt, livePreviewAndHistory);
        document.getElementById('colorDark').addEventListener(evt, livePreviewAndHistory);
        document.getElementById('colorLight').addEventListener(evt, livePreviewAndHistory);
        document.getElementById('dotType').addEventListener(evt, livePreviewAndHistory);
        document.getElementById('eyeType').addEventListener(evt, livePreviewAndHistory);
    });

    document.getElementById('qrLogo').addEventListener('change', function() {
        const file = this.files[0];
        if (!file) { qrLogoImage = null; livePreviewAndHistory(); return; }
        const reader = new FileReader();
        reader.onload = function(e) {
            qrLogoImage = e.target.result;
            livePreviewAndHistory();
        };
        reader.readAsDataURL(file);
    });

    document.getElementById('downloadQRCodePngBtn').addEventListener('click', function() { downloadQRCode('png'); });
    document.getElementById('downloadQRCodeSvgBtn').addEventListener('click', function() { downloadQRCode('svg'); });
    document.getElementById('copyQRCodeBtn').addEventListener('click', copyQRCode);
    document.getElementById('printQRCodeBtn').addEventListener('click', printQRCode);

    document.getElementById('batchGenerateBtn').addEventListener('click', batchGenerate);

    // Autofocus input
    document.getElementById('qrContent').focus();

    // Initial preview & history
    livePreviewAndHistory();
});
</script>
@endpush