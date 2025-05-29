@extends('layouts.app')

@section('content')
<div class="mb-4">
    <input type="file" id="imageInput" accept="image/*" class="form-control d-inline-block w-auto" />
</div>
<div class="mb-4">
    <label for="formatSelect" class="me-2">Convert to:</label>
    <select id="formatSelect" class="form-select d-inline-block w-auto">
        <option value="png">PNG</option>
        <option value="jpeg">JPG</option>
        <option value="webp">WebP</option>
    </select>
</div>
<div class="mb-4">
    <button class="btn btn-primary" id="convertImageBtn">Convert Image</button>
</div>
<div id="resultContainer" class="text-center d-none">
    <h5 class="mb-3">Converted Image:</h5>
    <div class="d-flex justify-content-center">
        <canvas id="imageCanvas" class="mb-3 w-100 d-none"></canvas>
    </div>
    <a id="downloadLink" class="btn btn-success" download>Download Image</a>
</div>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('convertImageBtn').addEventListener('click', convertImage);
});

function convertImage() {
    const input = document.getElementById('imageInput');
    const format = document.getElementById('formatSelect').value;

    if (!input.files.length) {
        showToast('Please upload an image first.', 'danger');
        return;
    }

    const file = input.files[0];
    const reader = new FileReader();

    reader.onload = function (e) {
        const img = new Image();
        img.onload = function () {
            const canvas = document.getElementById('imageCanvas');
            const ctx = canvas.getContext('2d');

            canvas.width = img.width;
            canvas.height = img.height;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0);

            const dataURL = canvas.toDataURL(`image/${format}`);
            const link = document.getElementById('downloadLink');
            link.href = dataURL;
            link.download = `converted-image.${format}`;

            canvas.style.display = 'block';
            document.getElementById('resultContainer').classList.remove('d-none');
        };
        img.src = e.target.result;
    };

    reader.readAsDataURL(file);
}
</script>
@endpush