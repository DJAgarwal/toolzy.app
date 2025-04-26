@extends('layouts.app')
@section('content')
<div class="container py-5">
    <h1 class="mb-4">Word and Character Counter</h1>

    <div class="mb-3">
        <textarea id="textInput" class="form-control" rows="8" placeholder="Start typing or paste your text here..."></textarea>
    </div>

    <div class="d-flex flex-wrap gap-3 mt-3">
        <div class="badge bg-primary text-white p-2">
            Words: <span id="wordCount">0</span>
        </div>
        <div class="badge bg-success text-white p-2">
            Characters: <span id="charCount">0</span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function countText() {
        const text = document.getElementById('textInput').value;
        if (text.trim() === "") {
            document.getElementById('wordCount').textContent = 0;
            document.getElementById('charCount').textContent = 0;
            return;
        }

        const words = text.trim().split(/\s+/).filter(word => word.length > 0);
        const characters = text.replace(/\s/g, '');
        document.getElementById('wordCount').textContent = words.length;
        document.getElementById('charCount').textContent = characters.length;
    }

    document.getElementById('textInput').addEventListener('input', countText);
</script>
@endpush