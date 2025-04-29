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
            Characters (with spaces): <span id="charCountWithSpaces">0</span>
        </div>
        <div class="badge bg-success text-white p-2">
            Characters (without spaces): <span id="charCountWithoutSpaces">0</span>
        </div>
        <div class="badge bg-warning text-dark p-2">
            Sentences: <span id="sentenceCount">0</span>
        </div>
        <div class="badge bg-info text-dark p-2">
            Paragraphs: <span id="paragraphCount">0</span>
        </div>
        <div class="badge bg-secondary text-white p-2">
            Reading Time: <span id="readingTime">0</span> min
        </div>
    </div>
    <section class="my-5">
            @include('components.what-is')
        </section>
        <section class="my-5">
            @include('components.faq')
        </section>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textInput = document.getElementById('textInput');
    const wordCount = document.getElementById('wordCount');
    const charCountWithSpaces = document.getElementById('charCountWithSpaces');
    const charCountWithoutSpaces = document.getElementById('charCountWithoutSpaces');
    const sentenceCount = document.getElementById('sentenceCount');
    const paragraphCount = document.getElementById('paragraphCount');
    const readingTime = document.getElementById('readingTime');

    let debounceTimer;

    textInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {
            const text = textInput.value;

            const words = text.trim().split(/\s+/).filter(word => word.length > 0);
            const charactersWithSpaces = text.length;
            const charactersWithoutSpaces = text.replace(/\s/g, '').length;
            const sentences = text.split(/[.!?](\s|$)/).filter(sentence => sentence.trim().length > 0);
            const paragraphs = text.split(/\n+/).filter(para => para.trim().length > 0);

            const wordsPerMinute = 200; // Standard reading speed
            const estimatedReadingTime = Math.ceil(words.length / wordsPerMinute);

            wordCount.textContent = words.length;
            charCountWithSpaces.textContent = charactersWithSpaces;
            charCountWithoutSpaces.textContent = charactersWithoutSpaces;
            sentenceCount.textContent = sentences.length;
            paragraphCount.textContent = paragraphs.length;
            readingTime.textContent = estimatedReadingTime;
        }, 300); // Debounce time (milliseconds)
    });
});
</script>
@endpush