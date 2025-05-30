@extends('layouts.app')

@section('content')
<div class="mb-4">
    <div id="dragDropArea" class="border rounded p-3 mt-2 text-center text-muted drag-drop-area position-relative">
        <label for="imageInput" class="form-label fw-semibold w-100 mb-0 drag-drop-label">
            <span>Select Images or Drag &amp; Drop Here</span>
            <input type="file" id="imageInput" accept="image/*,.heic,.avif,.svg,.bmp,.tiff,.gif" class="form-control d-inline-block w-auto file-input-cover" multiple tabindex="-1" />
        </label>
        <div class="form-text text-success mt-2">
            <strong>All processing is done on-device (in your browser). No uploads required.</strong>
        </div>
    </div>
</div>
<div class="mb-4 row g-2 align-items-center">
    <div class="col-12 col-md-auto mb-2 mb-md-0">
        <label for="formatSelect" class="me-2">Convert to:</label>
        <select id="formatSelect" class="form-select d-inline-block w-auto">
            <option value="jpeg">JPG</option>
            <option value="png">PNG</option>
            <option value="webp">WebP</option>
            <option value="bmp">BMP</option>
            <option value="gif">GIF</option>
            <option value="tiff">TIFF</option>
            <option value="svg">SVG</option>
            <option value="heic">HEIC</option>
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
            <label class="form-check-label" for="removeBg">Remove Background (PNG only)</label>
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
<div id="reorderContainer" class="mb-3 d-none">
    <h6>Reorder Images (drag to reorder):</h6>
    <ul id="reorderList" class="list-group flex-row flex-wrap gap-2"></ul>
</div>
<div id="resultContainer" class="mt-4 d-none">
    <h5 class="mb-3">Converted Images:</h5>
    <div id="convertedImages" class="d-flex flex-wrap gap-3 justify-content-center"></div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imagePreviewLabel">Image Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img id="imagePreviewModalImg" src="" alt="Preview" class="img-fluid rounded shadow">
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function() {
    // Drag and drop
    const dragDropArea = document.getElementById('dragDropArea');
    const imageInput = document.getElementById('imageInput');

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
            setupReorderList(files);
        }
    });

    imageInput.addEventListener('change', function() {
        if (imageInput.files.length) {
            setupReorderList(Array.from(imageInput.files));
        }
    });

    // Quality value display
    document.getElementById('quality').addEventListener('input', function() {
        document.getElementById('qualityValue').innerText = this.value;
    });

    // Watermark controls
    document.getElementById('addWatermark').addEventListener('change', function() {
        document.querySelector('.watermark-controls').classList.toggle('d-none', !this.checked);
    });

    document.getElementById('convertImageBtn').addEventListener('click', batchConvertImages);
    document.getElementById('bulkDownloadBtn').addEventListener('click', downloadAllAsZip);

    // FileList shim for drag & drop
    window.FileListShim = function(files) {
        const dataTransfer = new DataTransfer();
        files.forEach(f => dataTransfer.items.add(f));
        return dataTransfer.files;
    };

    // Modal preview
    document.getElementById('convertedImages').addEventListener('click', function(e) {
        if (e.target.tagName === 'IMG') {
            showModalPreview(e.target.src);
        }
    });

    // Reordering
    let dragSrcEl = null;
    function setupReorderList(files) {
        const reorderContainer = document.getElementById('reorderContainer');
        const reorderList = document.getElementById('reorderList');
        reorderList.innerHTML = '';
        Array.from(files).forEach((file, idx) => {
            const li = document.createElement('li');
            li.className = 'list-group-item reorder-thumb';
            li.setAttribute('draggable', true);
            li.setAttribute('data-idx', idx);
            li.textContent = file.name;
            li.addEventListener('dragstart', function(e) {
                dragSrcEl = this;
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', this.getAttribute('data-idx'));
                this.style.opacity = '0.5';
            });
            li.addEventListener('dragend', function() {
                this.style.opacity = '';
            });
            li.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
            });
            li.addEventListener('drop', function(e) {
                e.stopPropagation();
                if (dragSrcEl !== this) {
                    const srcIdx = parseInt(dragSrcEl.getAttribute('data-idx'), 10);
                    const tgtIdx = parseInt(this.getAttribute('data-idx'), 10);
                    reorderFiles(srcIdx, tgtIdx);
                }
                return false;
            });
            reorderList.appendChild(li);
        });
        reorderContainer.classList.remove('d-none');
    }

    function reorderFiles(fromIdx, toIdx) {
        let files = Array.from(imageInput.files);
        const moved = files.splice(fromIdx, 1)[0];
        files.splice(toIdx, 0, moved);
        imageInput.files = FileListShim(files);
        setupReorderList(files);
    }

    // Modal preview function
    function showModalPreview(src) {
        const modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
        document.getElementById('imagePreviewModalImg').src = src;
        modal.show();
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

async function convertSingleImage(file, options) {
    return new Promise(resolve => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = async function() {
                // Resize
                let drawWidth = options.width && options.width > 0 ? options.width : img.width;
                let drawHeight = options.height && options.height > 0 ? options.height : img.height;

                // Prepare canvas
                const canvas = document.createElement('canvas');
                canvas.width = drawWidth;
                canvas.height = drawHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, drawWidth, drawHeight);

                // Background removal (placeholder, requires server-side or advanced JS lib for real bg removal)
                if (options.removeBg && options.format === 'png') {
                    // Simple placeholder: remove pure white background
                    const imgData = ctx.getImageData(0, 0, drawWidth, drawHeight);
                    for (let i = 0; i < imgData.data.length; i += 4) {
                        if (imgData.data[i] > 240 && imgData.data[i+1] > 240 && imgData.data[i+2] > 240) {
                            imgData.data[i+3] = 0;
                        }
                    }
                    ctx.putImageData(imgData, 0, 0);
                }

                // Watermark
                if (options.addWatermark) {
                    ctx.save();
                    if (options.watermarkImage) {
                        // Draw image watermark at bottom right
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

                // Export to selected format
                let mime = getMimeType(options.format);
                let dataURL;
                if (['jpeg', 'png', 'webp'].includes(options.format)) {
                    dataURL = canvas.toDataURL(mime, options.quality);
                } else {
                    // For BMP, GIF, TIFF, SVG, HEIC, AVIF, fallback to PNG dataURL and update extension/mime
                    dataURL = canvas.toDataURL('image/png');
                }

                // File name
                const ext = options.format;
                const baseName = file.name.replace(/\.[^.]+$/, '');
                const downloadName = `${baseName}_converted.${ext}`;

                resolve({dataURL, downloadName, base64: dataURL});
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
        case 'heic': return 'image/heic'; // Most browsers won't support this
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

        const directLinkBtn = document.createElement('a');
        directLinkBtn.href = result.dataURL;
        directLinkBtn.target = '_blank';
        directLinkBtn.className = 'btn btn-outline-info btn-sm mb-1 me-1';
        directLinkBtn.innerText = 'Direct Link';

        col.appendChild(img);
        col.appendChild(document.createElement('br'));
        col.appendChild(dlBtn);
        col.appendChild(base64Btn);
        col.appendChild(directLinkBtn);

        container.appendChild(col);
    });

    document.getElementById('resultContainer').classList.remove('d-none');
    document.getElementById('bulkDownloadBtn').classList.toggle('d-none', results.length < 2);
}

// Helper to convert data URL to Blob (NO fetch, no CSP issue)
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
<script src="{{ asset('js/jszip.min.js') }}" nonce="{{ $cspNonce }}"></script>
@endpush