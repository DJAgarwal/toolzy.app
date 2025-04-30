@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center">Image Converter - Toolzy</h1>

    <div class="mb-4 text-center">
        <input type="file" id="imageInput" accept="image/*" class="form-control d-inline-block w-auto" />
    </div>

    <div class="mb-4 text-center">
        <label for="formatSelect" class="me-2">Convert to:</label>
        <select id="formatSelect" class="form-select d-inline-block w-auto">
            <option value="png">PNG</option>
            <option value="jpeg">JPG</option>
            <option value="webp">WebP</option>
        </select>
    </div>

    <div class="text-center mb-4">
        <button class="btn btn-primary" onclick="convertImage()">Convert Image</button>
    </div>

    <div id="resultContainer" class="text-center d-none">
        <h5 class="mb-3">Converted Image:</h5>
        <canvas id="imageCanvas" class="mb-3" style="max-width: 100%; display: none;"></canvas>
        <br>
        <a id="downloadLink" class="btn btn-success" download>Download Image</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
function convertImage() {
    const input = document.getElementById('imageInput');
    const format = document.getElementById('formatSelect').value;

    if (!input.files.length) {
        alert('Please upload an image first.');
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