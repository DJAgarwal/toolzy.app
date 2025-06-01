@extends('layouts.app')

@section('content')
<div class="mb-4">
  <label id="dragDropArea" for="imageInput" class="border fw-semibold rounded p-3 mt-2 text-center text-muted drag-drop-area position-relative w-100">
      <span>Select Images or Drag &amp; Drop Here</span>
      <input type="file" id="imageInput" accept="image/*,.heic,.avif,.svg,.bmp,.tiff,.gif" class="form-control d-inline-block w-auto file-input-cover" multiple tabindex="-1" />
      <div class="form-text text-success mt-2">
          <strong>All processing is done on your device. No files are uploaded on server.</strong>
      </div>
      <div class="form-text text-secondary mt-1">
          <strong>Allowed extensions: jpg, jpeg, png, webp, avif, heic, avif, svg, bmp, tiff, gif</strong>
      </div>
  </label>
</div>
<div class="mb-4 row g-2 align-items-center">
    <div class="col-12 col-md-auto mb-2 mb-md-0">
        <label for="formatSelect" class="me-2">Convert to:</label>
        <select id="formatSelect" class="form-select d-inline-block w-auto">
            <option value="jpeg">JPG</option>
            <option value="png">PNG</option>
            <option value="webp">WebP</option>
            <option value="avif">AVIF</option>
        </select>
    </div>
    <div class="col-12 col-md-auto mb-2 mb-md-0 quality-control">
        <label for="quality" class="me-2">Quality:</label>
        <input type="range" id="quality" min="10" max="100" value="100" class="form-range quality-range" />
        <span id="qualityValue">100</span>
    </div>
    <div class="col-12 col-md-auto mb-2 mb-md-0">
        <label class="me-2">Resize:</label>
        <input type="number" id="resizeWidth" placeholder="Width" class="form-control d-inline-block resize-input" min="1">
        x
        <input type="number" id="resizeHeight" placeholder="Height" class="form-control d-inline-block resize-input" min="1">
        <small class="d-block text-muted">Leave blank for original size</small>
    </div>
    <div class="col-12 col-md-auto mb-2 mb-md-0 align-self-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="removeBg">
            <label class="form-check-label" for="removeBg">Remove Background(PNG)</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="addWatermark">
            <label class="form-check-label" for="addWatermark">Add Watermark</label>
        </div>
    </div>
    <div class="col-12 col-md-auto mb-2 mb-md-0 align-self-center watermark-controls d-none">
        <div class="mb-1">
            <input type="text" id="watermarkText" class="form-control" placeholder="Text for watermark (will appear on image)">
            <small class="form-text text-muted">Enter watermark text, e.g., your brand or copyright notice.</small>
        </div>
        <div class="mb-1">
            <label class="form-label d-block mb-1">Watermark Color:</label>
            <input type="color" id="watermarkColor" class="form-control form-control-color d-inline-block watermark-color-input" value="#FFFFFF" title="Watermark color">
            <small class="form-text text-muted">Choose color for watermark text.</small>
        </div>
        <div class="mb-1">
            <label class="form-label d-block mb-1">Watermark Font Size:</label>
            <input type="number" id="watermarkFontSize" class="form-control d-inline-block watermark-fontsize-input" placeholder="Font size" value="32" min="8" max="200">
            <small class="form-text text-muted">Font size in pixels for watermark text.</small>
        </div>
        <div class="mb-1">
            <label class="form-label d-block mb-1">Or add a watermark image:</label>
            <input type="file" id="watermarkImage" accept="image/*" class="form-control">
            <small class="form-text text-muted">Upload a small PNG or logo to use as an image watermark.</small>
        </div>
        <div class="mb-2">
            <small class="form-text text-muted">
                <strong>Note:</strong> If both text and image are provided, both will appear on the output image.
            </small>
        </div>
    </div>
</div>
<div class="mb-4 d-flex flex-wrap gap-2 align-items-center">
    <button class="btn btn-primary" id="convertImageBtn">Convert Image(s)</button>
    <button class="btn btn-success d-none" id="bulkDownloadBtn">Download All as ZIP</button>
    <div class="progress flex-grow-1 mx-2 d-none conversion-progress-wrapper" id="conversionProgressBarWrapper">
        <div class="progress-bar" id="conversionProgressBar" role="progressbar"></div>
    </div>
</div>
<div id="resultContainer" class="mt-4 d-none">
    <h5 class="mb-3">Converted Images:</h5>
    <div id="convertedImages" class="d-flex flex-wrap gap-3 justify-content-center"></div>
</div>
@endsection
@push('scripts')
<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function() {
    // Drag and drop
    const dragDropArea = document.getElementById('dragDropArea');
    const imageInput = document.getElementById('imageInput');
    const formatSelect = document.getElementById('formatSelect');
    const qualityInput = document.getElementById('quality');
    const qualityValueSpan = document.getElementById('qualityValue');
    const removeBg = document.getElementById('removeBg');
    const addWatermark = document.getElementById('addWatermark');
    const watermarkControls = document.querySelector('.watermark-controls');
    const watermarkText = document.getElementById('watermarkText');
    const watermarkColor = document.getElementById('watermarkColor');
    const watermarkFontSize = document.getElementById('watermarkFontSize');
    const watermarkImageInput = document.getElementById('watermarkImage');
    const resizeWidth = document.getElementById('resizeWidth');
    const resizeHeight = document.getElementById('resizeHeight');
    const bulkDownloadBtn = document.getElementById('bulkDownloadBtn');
    const convertImageBtn = document.getElementById('convertImageBtn');
    const convertedImages = document.getElementById('convertedImages');

    // Modal preview
    convertedImages.addEventListener('click', function(e) {
        if (e.target.tagName === 'IMG') {
            showModalPreview(e.target.src);
        }
    });

    function showModalPreview(src) {
        document.getElementById('imagePreviewModalImg').src = src;
        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('imagePreviewModal'));
        modal.show();
    }

    // Show/hide watermark controls
    addWatermark.addEventListener('change', function() {
        watermarkControls.classList.toggle('d-none', !this.checked);
    });

    // Quality value display
    qualityInput.addEventListener('input', function() {
        qualityValueSpan.innerText = this.value;
    });

    // Drag & drop behavior
    dragDropArea.addEventListener('click', () => imageInput.click());
    dragDropArea.addEventListener('dragover', e => {
        e.preventDefault();
        dragDropArea.classList.add('bg-info', 'bg-opacity-10');
    });
    dragDropArea.addEventListener('dragleave', e => {
        e.preventDefault();
        dragDropArea.classList.remove('bg-info', 'bg-opacity-10');
    });
    dragDropArea.addEventListener('drop', e => {
        e.preventDefault();
        dragDropArea.classList.remove('bg-info', 'bg-opacity-10');
        const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/') || /\.(heic|avif|svg|bmp|tiff|gif)$/i.test(f.name));
        if (files.length) {
            imageInput.files = new FileListShim(files);
            batchConvertImages();
        }
    });

    imageInput.addEventListener('change', function() {
        if (imageInput.files.length) {
            batchConvertImages();
        }
    });

    convertImageBtn.addEventListener('click', function() {
        batchConvertImages();
    });

    // FileList shim for drag & drop
    window.FileListShim = function(files) {
        const dataTransfer = new DataTransfer();
        files.forEach(f => dataTransfer.items.add(f));
        return dataTransfer.files;
    };

    // Bulk download
    bulkDownloadBtn.addEventListener('click', downloadAllAsZip);

    function clearResults() {
        document.getElementById('convertedImages').innerHTML = '';
        document.getElementById('resultContainer').classList.add('d-none');
        bulkDownloadBtn.classList.add('d-none');
    }
});

async function batchConvertImages() {
    const input = document.getElementById('imageInput');
    const format = document.getElementById('formatSelect').value;
    const quality = parseInt(document.getElementById('quality').value, 10) / 100;
    const width = parseInt(document.getElementById('resizeWidth').value, 10);
    const height = parseInt(document.getElementById('resizeHeight').value, 10);
    const removeBg = document.getElementById('removeBg').checked;
    const addWatermark = document.getElementById('addWatermark').checked;

    let watermarkText = '';
    let watermarkColor = '#FFFFFF';
    let watermarkFontSize = 32;
    let watermarkImage = null;

    if (addWatermark) {
        watermarkText = document.getElementById('watermarkText').value;
        watermarkColor = document.getElementById('watermarkColor').value;
        watermarkFontSize = parseInt(document.getElementById('watermarkFontSize').value, 10) || 32;
        const wmImgInput = document.getElementById('watermarkImage');
        if (wmImgInput.files && wmImgInput.files.length) {
            watermarkImage = await loadImageAsync(wmImgInput.files[0]);
        }
    }

    if (!input.files.length) {
        showToast('Please upload at least one image.', 'danger');
        return;
    }

    // Progress bar
    const progressWrapper = document.getElementById('conversionProgressBarWrapper');
    const progressBar = document.getElementById('conversionProgressBar');
    progressWrapper.classList.remove('d-none');
    progressBar.style.width = '0%';
    progressBar.textContent = '0%';

    const results = [];
    const files = Array.from(input.files);
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const converted = await convertSingleImage(file, {format, quality, width, height, removeBg, addWatermark, watermarkText, watermarkColor, watermarkFontSize, watermarkImage});
        results.push(converted);
        // Update progress
        const percent = Math.round(((i+1)/files.length)*100);
        progressBar.style.width = percent + '%';
        progressBar.textContent = percent + '%';
    }

    setTimeout(() => progressWrapper.classList.add('d-none'), 500);

    displayResults(results, format);
}

function loadImageAsync(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = new Image();
            img.onload = () => resolve(img);
            img.onerror = reject;
            img.src = e.target.result;
        };
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });
}

// Uses browser-image-compression for final export!
async function convertSingleImage(file, options) {
    // Step 1: Prepare canvas with resize, watermark, background removal
    return new Promise(resolve => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = async function() {
                let drawWidth = options.width && options.width > 0 ? options.width : img.width;
                let drawHeight = options.height && options.height > 0 ? options.height : img.height;

                const canvas = document.createElement('canvas');
                canvas.width = drawWidth;
                canvas.height = drawHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, drawWidth, drawHeight);

                if (options.removeBg && options.format === 'png') {
                    const imgData = ctx.getImageData(0, 0, drawWidth, drawHeight);
                    for (let i = 0; i < imgData.data.length; i += 4) {
                        if (imgData.data[i] > 240 && imgData.data[i+1] > 240 && imgData.data[i+2] > 240) {
                            imgData.data[i+3] = 0;
                        }
                    }
                    ctx.putImageData(imgData, 0, 0);
                }

                if (options.addWatermark) {
                    ctx.save();
                    if (options.watermarkImage) {
                        const wmImg = options.watermarkImage;
                        const w = wmImg.width * 0.25;
                        const h = wmImg.height * 0.25;
                        ctx.globalAlpha = 0.5;
                        ctx.drawImage(wmImg, canvas.width-w-10, canvas.height-h-10, w, h);
                        ctx.globalAlpha = 1;
                    }
                    if (options.watermarkText) {
                        ctx.font = `bold ${options.watermarkFontSize}px sans-serif`;
                        ctx.fillStyle = options.watermarkColor || '#FFFFFF';
                        ctx.textAlign = 'right';
                        ctx.textBaseline = 'bottom';
                        ctx.shadowColor = "#000";
                        ctx.shadowBlur = 4;
                        ctx.globalAlpha = 0.7;
                        ctx.fillText(options.watermarkText, canvas.width-10, canvas.height-10);
                        ctx.globalAlpha = 1;
                    }
                    ctx.restore();
                }

                // Step 2: Convert canvas to blob
                canvas.toBlob(async function(baseBlob) {
                    // Step 3: Use browser-image-compression for final export
                    const outType = getMimeType(options.format);
                    const compressedBlob = await imageCompression(baseBlob, {
                        fileType: outType,
                        initialQuality: options.quality,
                        useWebWorker: true,
                    });
                    const reader2 = new FileReader();
                    reader2.onload = function(e2) {
                        const dataURL = e2.target.result;
                        const ext = options.format;
                        const baseName = file.name.replace(/\.[^.]+$/, '');
                        const downloadName = `${baseName}_converted.${ext}`;
                        resolve({dataURL, downloadName, base64: dataURL});
                    };
                    reader2.readAsDataURL(compressedBlob);
                }, getMimeType(options.format), options.quality);

            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
}

function getMimeType(format) {
    switch (format) {
        case 'jpeg': return 'image/jpeg';
        case 'png': return 'image/png';
        case 'webp': return 'image/webp';
        case 'bmp': return 'image/bmp';
        case 'gif': return 'image/gif';
        case 'tiff': return 'image/tiff';
        case 'svg': return 'image/svg+xml';
        case 'heic': return 'image/heic';
        case 'avif': return 'image/avif';
        default: return 'image/png';
    }
}
function displayResults(results, format) {
    const container = document.getElementById('convertedImages');
    container.innerHTML = '';
    results.forEach((result, idx) => {
        const col = document.createElement('div');
        col.className = 'converted-image text-center';

        const img = document.createElement('img');
        img.src = result.dataURL;
        img.alt = result.downloadName;
        img.className = 'mb-2 rounded shadow-sm';
        img.setAttribute('tabindex', 0);
        img.style.outline = "none";

        const dlBtn = document.createElement('a');
        dlBtn.href = result.dataURL;
        dlBtn.download = result.downloadName;
        dlBtn.className = 'btn btn-success btn-sm mb-1 me-1';
        dlBtn.innerText = 'Download';

        const base64Btn = document.createElement('button');
        base64Btn.className = 'btn btn-outline-secondary btn-sm mb-1 me-1';
        base64Btn.innerText = 'Copy Base64';
        base64Btn.onclick = () => {
            navigator.clipboard.writeText(result.base64);
            showToast('Base64 copied to clipboard!', 'success');
        };

        col.appendChild(img);
        col.appendChild(document.createElement('br'));
        col.appendChild(dlBtn);
        col.appendChild(base64Btn);

        container.appendChild(col);
    });

    document.getElementById('resultContainer').classList.remove('d-none');
    document.getElementById('bulkDownloadBtn').classList.toggle('d-none', results.length < 2);
}

function dataURLtoBlob(dataurl) {
    let arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
    for (let i = 0; i < n; i++) {
        u8arr[i] = bstr.charCodeAt(i);
    }
    return new Blob([u8arr], {type: mime});
}

function downloadAllAsZip() {
    if (typeof JSZip === 'undefined') {
        showToast('JSZip library not loaded. Bulk download not available.', 'danger');
        return;
    }
    const container = document.getElementById('convertedImages');
    const imgs = container.querySelectorAll('a[download]');
    if (!imgs.length) return;
    const zip = new JSZip();
    let added = 0;
    imgs.forEach(a => {
        const blob = dataURLtoBlob(a.href);
        zip.file(a.download, blob);
        added++;
        if (added === imgs.length) {
            zip.generateAsync({ type: 'blob' }).then(function(content) {
                const link = document.createElement('a');
                link.href = URL.createObjectURL(content);
                link.download = 'converted-images.zip';
                link.click();
            });
        }
    });
}
</script>
<script src="{{ asset('js/browser-image-compression.js') }}" nonce="{{ $cspNonce }}"></script>
<script src="{{ asset('js/jszip.min.js') }}" nonce="{{ $cspNonce }}"></script>
@endpush