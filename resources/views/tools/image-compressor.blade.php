@extends('layouts.app')

@section('content')
<form action="{{ route('tools.image-compressor') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="images" class="form-label fw-semibold">Select Images (JPG/PNG/WebP):</label>
        <input type="file" name="images[]" id="images" class="form-control" multiple required accept="image/*">
    </div>

    <div class="mb-3">
        <label for="targetSize" class="form-label fw-semibold">Target Max File Size (KB):</label>
        <select name="targetSize" id="targetSize" class="form-select" required>
            <option value="50">50 KB</option>
            <option value="100">100 KB</option>
            <option value="200">200 KB</option>
            <option value="500">500 KB</option>
            <option value="1000">1 MB</option>
            <option value="2000">2 MB</option>
            <option value="5000">5 MB</option>
        </select>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg">Compress Images</button>
    </div>
</form>

@if(session('compressed_files'))
<div class="mt-5">
    <h4 class="fw-semibold">Compressed Images:</h4>
    <ul class="list-group">
        @foreach(session('compressed_files') as $file)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ basename($file) }}
                <a href="{{ asset($file) }}" class="btn btn-sm btn-success" download>Download</a>
            </li>
        @endforeach
    </ul>
</div>
@endif
@endsection