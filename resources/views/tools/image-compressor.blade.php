@extends('layouts.app')

@section('content')
<div class="mb-4">
  <label id="dragDropArea" for="fileInput" class="border fw-semibold rounded p-3 mt-2 text-center text-muted drag-drop-area position-relative w-100">
      <span>Select Images or Drag &amp; Drop Here</span>
      <input type="file" id="fileInput" accept="image/jpeg,image/png,image/webp,image/avif"
             class="form-control d-inline-block w-auto file-input-cover" multiple tabindex="-1" hidden />
      <div class="form-text text-success mt-2">
          <strong>All compression is done on your device. No files are uploaded to the server.</strong>
      </div>
      <div class="form-text text-secondary mt-1">
          <strong>Allowed: jpg, jpeg, png, webp, avif.</strong>
      </div>
  </label>
</div>

<!-- Convert Images Checkbox & Format Selection -->
<div class="mb-3 d-flex flex-wrap align-items-center gap-3" id="formatCheckboxesRow">
    <div class="form-check me-3">
        <input class="form-check-input" type="checkbox" value="convert" id="convertImagesChk">
        <label class="form-check-label fw-semibold" for="convertImagesChk">Convert Images?</label>
    </div>
    <span class="me-2 fw-semibold d-none" id="toLabel">to:</span>
    <div class="form-check me-2 d-none" id="formatJpegWrap">
        <input class="form-check-input format-checkbox" type="checkbox" value="jpeg" id="formatJpeg" checked>
        <label class="form-check-label" for="formatJpeg">JPG</label>
    </div>
    <div class="form-check me-2 d-none" id="formatPngWrap">
        <input class="form-check-input format-checkbox" type="checkbox" value="png" id="formatPng" checked>
        <label class="form-check-label" for="formatPng">PNG</label>
    </div>
    <div class="form-check me-2 d-none" id="formatWebpWrap">
        <input class="form-check-input format-checkbox" type="checkbox" value="webp" id="formatWebp" checked>
        <label class="form-check-label" for="formatWebp">WEBP</label>
    </div>
    <div class="form-check me-2 d-none" id="formatAvifWrap">
        <input class="form-check-input format-checkbox" type="checkbox" value="avif" id="formatAvif" checked>
        <label class="form-check-label" for="formatAvif">AVIF</label>
    </div>
</div>

<!-- Progress Bar with Message -->
<div id="compressionProgressBarWrapper" class="progress mb-3 d-none progress-bar-custom">
    <div class="progress-bar" id="compressionProgressBar" role="progressbar"></div>
</div>
<div id="compressionMsg" class="compression-message d-none mb-3">
    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
    Your images are being compressed. Please give us a moment to finish getting the best results for you.
</div>

<!-- Image Results Fresh List -->
<div id="imageResultList" class="d-flex flex-column gap-3"></div>

<!-- Bulk Download Buttons -->
<div class="d-flex flex-wrap gap-2 justify-content-end mt-4 mb-2 d-none" id="bulkDownloadRow">
    <button type="button" class="btn btn-outline-info d-none" id="bulkZipJpegBtn">Download All (JPG)</button>
    <button type="button" class="btn btn-outline-success d-none" id="bulkZipPngBtn">Download All (PNG)</button>
    <button type="button" class="btn btn-outline-warning d-none" id="bulkZipWebpBtn">Download All (WEBP)</button>
    <button type="button" class="btn btn-outline-primary d-none" id="bulkZipAvifBtn">Download All (AVIF)</button>
</div>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
const dragDropArea = document.getElementById('dragDropArea');
const fileInput = document.getElementById('fileInput');
const imageResultList = document.getElementById('imageResultList');
const compressionProgressBarWrapper = document.getElementById('compressionProgressBarWrapper');
const compressionProgressBar = document.getElementById('compressionProgressBar');
const compressionMsg = document.getElementById('compressionMsg');
const bulkDownloadRow = document.getElementById('bulkDownloadRow');
const bulkZipJpegBtn = document.getElementById('bulkZipJpegBtn');
const bulkZipPngBtn = document.getElementById('bulkZipPngBtn');
const bulkZipWebpBtn = document.getElementById('bulkZipWebpBtn');
const bulkZipAvifBtn = document.getElementById('bulkZipAvifBtn');

// Format selectors
const convertImagesChk = document.getElementById('convertImagesChk');
const formatCheckboxes = Array.from(document.querySelectorAll('.format-checkbox'));
const toLabel = document.getElementById('toLabel');
const formatJpegWrap = document.getElementById('formatJpegWrap');
const formatPngWrap = document.getElementById('formatPngWrap');
const formatWebpWrap = document.getElementById('formatWebpWrap');
const formatAvifWrap = document.getElementById('formatAvifWrap');

let imageFiles = []; // [{file, name, src, ext, originalSize, outputs:{jpeg,png,webp,avif}}]
let compressingCount = 0; // Track compressing tasks

function humanFileSize(size) {
    if (!size && size !== 0) return '';
    if (size < 1024) return size + ' B';
    if (size < 1024*1024) return (size/1024).toFixed(1) + ' KB';
    return (size/1024/1024).toFixed(2) + ' MB';
}

// NEW: Only hide progress when ALL visible tasks are done/error
function updateProgressBar() {
    const totalTasks = getTotalCompressTasks();
    const doneTasks = getDoneCompressTasks();
    const isActive = totalTasks > 0 && doneTasks < totalTasks;
    if (isActive) {
        compressionProgressBarWrapper.classList.remove('d-none');
        compressionMsg.classList.remove('d-none');
        compressionProgressBar.style.width = (doneTasks / totalTasks * 100) + '%';
    } else {
        compressionProgressBarWrapper.classList.add('d-none');
        compressionMsg.classList.add('d-none');
        compressionProgressBar.style.width = '0%';
    }
}
function getTotalCompressTasks() {
    // Count all outputs for all files that are actively visible (based on convert/checkboxes/ext)
    let count = 0;
    imageFiles.forEach(imgObj => {
        getUsedFormatsForRow(imgObj).forEach(fmt => { count++; });
    });
    return count;
}
function getDoneCompressTasks() {
    let count = 0;
    imageFiles.forEach(imgObj => {
        getUsedFormatsForRow(imgObj).forEach(fmt => {
            const o = imgObj.outputs[fmt];
            if (o.status === 'done' || o.status === 'error') count++;
        });
    });
    return count;
}

dragDropArea.setAttribute('role', 'button');
dragDropArea.setAttribute('tabindex', '0');
dragDropArea.setAttribute('aria-label', 'Select images or drag and drop here');
dragDropArea.addEventListener('keydown', e => {
    if (e.key === 'Enter' || e.key === ' ') fileInput.click();
});
dragDropArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    dragDropArea.classList.add('dragover');
});
dragDropArea.addEventListener('dragleave', () => {
    dragDropArea.classList.remove('dragover');
});
dragDropArea.addEventListener('drop', (e) => {
    e.preventDefault();
    dragDropArea.classList.remove('dragover');
    handleFiles(e.dataTransfer.files);
});
fileInput.addEventListener('change', (e) => {
    handleFiles(e.target.files);
});

// Show/hide format checkboxes based on Convert Images
convertImagesChk.addEventListener('change', () => {
    const show = convertImagesChk.checked;
    toLabel.classList.toggle('d-none', !show);
    formatJpegWrap.classList.toggle('d-none', !show);
    formatPngWrap.classList.toggle('d-none', !show);
    formatWebpWrap.classList.toggle('d-none', !show);
    formatAvifWrap.classList.toggle('d-none', !show);
    autoCompressAll();
    renderBulkButtons();
    renderList();
});
formatCheckboxes.forEach(cb =>
    cb.addEventListener('change', () => {
        autoCompressAll();
        renderBulkButtons();
        renderList();
    })
);

function handleFiles(files) {
    const acceptedTypes = [
        'image/jpeg','image/png','image/webp','image/avif'
    ];
    Array.from(files).forEach(file => {
        if (
            acceptedTypes.includes(file.type) && !imageFiles.some(f => f.name === file.name && f.file.size === file.size)
        ) {
            const ext = getExtFromType(file.type);
            const reader = new FileReader();
            reader.onload = function(ev) {
                imageFiles.push({
                    file,
                    name: file.name,
                    ext,
                    src: ev.target.result,
                    originalSize: file.size,
                    outputs: {
                        jpeg: { blob: null, url: null, size: null, saved: null, status: 'idle' },
                        png:   { blob: null, url: null, size: null, saved: null, status: 'idle' },
                        webp:  { blob: null, url: null, size: null, saved: null, status: 'idle' },
                        avif:  { blob: null, url: null, size: null, saved: null, status: 'idle' }
                    }
                });
                compressImage(imageFiles.length - 1);
            };
            reader.readAsDataURL(file);
        }
    });
    renderList();
    renderBulkButtons();
    updateProgressBar();
}

function getExtFromType(type) {
    if (type === "image/jpeg") return "jpeg";
    if (type === "image/png") return "png";
    if (type === "image/webp") return "webp";
    if (type === "image/avif") return "avif";
    return "";
}

function getUsedFormatsForRow(imgObj) {
    if (convertImagesChk.checked) {
        // Only show checked formats
        const checked = formatCheckboxes.filter(cb => cb.checked).map(cb => cb.value);
        return checked.length ? checked : [];
    } else {
        // Only the original file extension
        return [imgObj.ext];
    }
}

function autoCompressAll() {
    imageFiles.forEach((imgObj, idx) => {
        compressImage(idx);
    });
}

async function compressImage(idx) {
    const imgObj = imageFiles[idx];
    const formats = getUsedFormatsForRow(imgObj);
    let didChange = false;
    for (const fmt of ['jpeg','png','webp','avif']) {
        const o = imgObj.outputs[fmt];
        if (!formats.includes(fmt)) {
            o.status = 'idle';
            continue;
        }
        // If already compressed and file didn't change, skip
        if (o.status === 'done' && o.blob && o.size === o.blob.size) continue;
        o.status = 'pending';
        didChange = true;
        renderList();
        updateProgressBar();
        let options = {
            initialQuality: 0.8,
            useWebWorker: true,
            fileType: getOutputType(fmt),
        };
        if (fmt === 'png') {
            delete options.initialQuality;
            options.lossless = true;
        }
        try {
            const compressedBlob = await imageCompression(imgObj.file, options);
            if (o.url) URL.revokeObjectURL(o.url);
            o.blob = compressedBlob;
            o.url = URL.createObjectURL(compressedBlob);
            o.size = compressedBlob.size;
            o.saved = ((1 - compressedBlob.size / imgObj.originalSize) * 100);
            o.status = 'done';
        } catch (e) {
            o.status = 'error';
            o.blob = null;
            o.url = null;
            o.size = null;
            o.saved = null;
        }
        renderList();
        updateProgressBar();
    }
    // After all compressions for this image, update progress
    updateProgressBar();
    renderBulkButtons();
}

function renderList() {
    imageResultList.innerHTML = '';
    imageFiles.forEach((imgObj, idx) => {
        const row = document.createElement('div');
        row.className = 'fresh-row';

        // Thumbnail
        const img = document.createElement('img');
        img.src = imgObj.src;
        img.alt = imgObj.name;
        img.className = 'fresh-thumb';
        img.loading = 'lazy';
        row.appendChild(img);

        // Info
        const info = document.createElement('div');
        info.className = 'fresh-info';
        const filename = document.createElement('span');
        filename.className = 'fresh-filename';
        filename.title = imgObj.name;
        filename.innerText = imgObj.name;
        info.appendChild(filename);
        const origSize = document.createElement('span');
        origSize.className = 'fresh-size';
        origSize.innerText = humanFileSize(imgObj.originalSize);
        info.appendChild(origSize);
        row.appendChild(info);

        // Actions
        const actions = document.createElement('div');
        actions.className = 'fresh-actions';
        getUsedFormatsForRow(imgObj).forEach(fmt => {
            const o = imgObj.outputs[fmt];
            const btn = document.createElement('a');
            btn.className = getDownloadBtnClass(fmt);
            btn.tabIndex = 0;
            btn.setAttribute('aria-label', `Download ${fmt.toUpperCase()}`);
            if (o.status === 'done' && o.url) {
                btn.href = o.url;
                btn.download = getCompressedName(imgObj.name, fmt);
                btn.innerHTML = `${fmt.toUpperCase()}<div class="fresh-size-badge">${humanFileSize(o.size)}</div><div class="fresh-saved">-${Math.round(o.saved)}%</div>`;
            } else if (o.status === 'error') {
                btn.innerHTML = `Error`;
                btn.classList.add('disabled');
                btn.href = "#";
                btn.tabIndex = -1;
            } else if (o.status === 'pending') {
                btn.innerHTML = `Compressing...`;
                btn.href = "#";
                btn.tabIndex = -1;
                btn.classList.add('disabled');
            } else {
                btn.innerHTML = `${fmt.toUpperCase()}`;
                btn.href = "#";
                btn.tabIndex = -1;
                btn.classList.add('disabled');
            }
            actions.appendChild(btn);
        });
        row.appendChild(actions);

        imageResultList.appendChild(row);
    });
}

function renderBulkButtons() {
    // Only show for convert mode and for checked formats
    const showBulk = imageFiles.length > 0 && convertImagesChk.checked && formatCheckboxes.some(cb => cb.checked);
    bulkDownloadRow.classList.toggle('d-none', !showBulk);
    bulkZipJpegBtn.classList.toggle('d-none', !formatCheckboxes.find(cb => cb.value === 'jpeg').checked);
    bulkZipPngBtn.classList.toggle('d-none', !formatCheckboxes.find(cb => cb.value === 'png').checked);
    bulkZipWebpBtn.classList.toggle('d-none', !formatCheckboxes.find(cb => cb.value === 'webp').checked);
    bulkZipAvifBtn.classList.toggle('d-none', !formatCheckboxes.find(cb => cb.value === 'avif').checked);
}

function getDownloadBtnClass(fmt) {
    if (fmt === 'jpeg') return 'btn btn-outline-info';
    if (fmt === 'png') return 'btn btn-outline-success';
    if (fmt === 'webp') return 'btn btn-outline-warning';
    if (fmt === 'avif') return 'btn btn-outline-primary';
    return 'btn btn-outline-secondary';
}

function getCompressedName(name, fmt) {
    const ext = fmt === 'jpeg' ? 'jpg' : fmt;
    return name.replace(/\.[^.]+$/, '') + '-compressed.' + ext;
}

function getOutputType(fmt) {
    switch (fmt) {
        case 'png': return 'image/png';
        case 'webp': return 'image/webp';
        case 'avif': return 'image/avif';
        case 'jpeg': return 'image/jpeg';
        default: return null;
    }
}

function bulkDownload(fmt) {
    if (typeof JSZip === 'undefined') {
        alert('JSZip library not loaded. Bulk download not available.');
        return;
    }
    const zip = new JSZip();
    imageFiles.forEach(imgObj => {
        const o = imgObj.outputs[fmt];
        if (o && o.blob) {
            zip.file(getCompressedName(imgObj.name, fmt), o.blob);
        }
    });
    zip.generateAsync({ type: 'blob' }).then(function(content) {
        const link = document.createElement('a');
        link.href = URL.createObjectURL(content);
        link.download = `compressed-images-${fmt}.zip`;
        link.click();
    });
}
bulkZipJpegBtn.addEventListener('click', () => bulkDownload('jpeg'));
bulkZipPngBtn.addEventListener('click', () => bulkDownload('png'));
bulkZipWebpBtn.addEventListener('click', () => bulkDownload('webp'));
bulkZipAvifBtn.addEventListener('click', () => bulkDownload('avif'));

// On page load, hide format checkboxes
convertImagesChk.checked = false;
toLabel.classList.add('d-none');
formatJpegWrap.classList.add('d-none');
formatPngWrap.classList.add('d-none');
formatWebpWrap.classList.add('d-none');
formatAvifWrap.classList.add('d-none');
renderBulkButtons();
renderList();
updateProgressBar();
</script>
<script src="{{ asset('js/jszip.min.js') }}" nonce="{{ $cspNonce }}"></script>
<script src="{{ asset('js/browser-image-compression.js') }}" nonce="{{ $cspNonce }}"></script>
@endpush