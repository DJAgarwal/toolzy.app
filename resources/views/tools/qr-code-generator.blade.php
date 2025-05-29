@extends('layouts.app')

@section('content')
<div class="mb-4">
    <label for="qrContent" class="form-label fw-semibold">Enter Text or URL:</label>
    <input type="text" class="form-control" id="qrContent" placeholder="Enter text or URL">
</div>
<div class="mb-4">
    <label class="form-label fw-semibold">QR Customization:</label>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Size (px)</label>
            <select class="form-select" id="qrSize">
                <option value="150">150 × 150</option>
                <option value="200">200 × 200</option>
                <option value="250">250 × 250</option>
                <option value="300" selected>300 × 300 (Recommended)</option>
                <option value="400">400 × 400</option>
                <option value="500">500 × 500</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Foreground Color</label>
            <input type="color" class="form-control form-control-color" id="colorDark" value="#000000">
        </div>
        <div class="col-md-4">
            <label class="form-label">Background Color</label>
            <input type="color" class="form-control form-control-color" id="colorLight" value="#ffffff">
        </div>
    </div>
</div>
<div class="mb-4 text-center" id="qrCodePreview">
    <!-- QR Code will appear here -->
</div>
<div class="d-flex gap-2 flex-wrap">
    <button class="btn btn-primary btn-lg" id="generateQRCodeBtn">Generate QR Code</button>
    <button class="btn btn-outline-success" id="copyQRCodeBtn">Copy to Clipboard</button>
    <button class="btn btn-outline-dark" id="downloadQRCodePngBtn">Download PNG</button>
    <button class="btn btn-outline-secondary" id="downloadQRCodeSvgBtn">Download SVG</button>
</div>
<div class="d-grid">
</div>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}" src="https://cdn.jsdelivr.net/npm/qr-code-styling@1.5.0/lib/qr-code-styling.js"></script>
<script nonce="{{ $cspNonce }}">
let qrCode = null;

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('generateQRCodeBtn').addEventListener('click', generateQRCode);
    document.getElementById('downloadQRCodePngBtn').addEventListener('click', function() { downloadQRCode('png'); });
    document.getElementById('downloadQRCodeSvgBtn').addEventListener('click', function() { downloadQRCode('svg'); });
    document.getElementById('copyQRCodeBtn').addEventListener('click', copyQRCode);
});

function generateQRCode() {
    const text = document.getElementById('qrContent').value.trim();
    if (!text) {
        showToast('Please enter some text or URL.', 'danger');
        return;
    }

    const size = parseInt(document.getElementById('qrSize').value);
    const colorDark = document.getElementById('colorDark').value;
    const colorLight = document.getElementById('colorLight').value;

    qrCode = new QRCodeStyling({
        width: size,
        height: size,
        data: text,
        image: "",
        dotsOptions: {
            color: colorDark,
            type: "square"
        },
        backgroundOptions: {
            color: colorLight,
        },
        qrOptions: {
            errorCorrectionLevel: 'H'
        }
    });

    const container = document.getElementById("qrCodePreview");
    container.innerHTML = '';
    qrCode.append(container);
}

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
</script>
@endpush