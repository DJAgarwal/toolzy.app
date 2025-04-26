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
    <div class="mt-5">
        <h2 class="h4 fw-bold mb-3">What is a Word Counter?</h2>
        <p class="text-muted">
            A word counter is a simple yet powerful tool designed to quickly calculate the number of words, characters, sentences, and paragraphs in any given text. Whether you're a student working on an assignment, a content writer optimizing articles for SEO, or someone managing social media posts within character limits, a word counter helps you stay accurate and efficient.
        </p>
        <p class="text-muted">
            By using our free Word and Character Counter tool, you can effortlessly track your writing metrics in real-time. No downloads, no limitations — just paste your text and get instant results. Perfect for essays, blog posts, tweets, reports, and everything in between.
        </p>
    </div>
    <div class="mt-5">
    <h2 class="h4 fw-bold mb-3">Frequently Asked Questions</h2>

    <div class="mb-4">
        <h3 class="h6 fw-semibold">How accurate is this Word Counter?</h3>
        <p class="text-muted">
            Our word counter tool is highly accurate and updates results instantly as you type or paste text. It counts words, characters (with and without spaces), sentences, and paragraphs in real-time.
        </p>
    </div>

    <div class="mb-4">
        <h3 class="h6 fw-semibold">Can I use this tool for academic essays?</h3>
        <p class="text-muted">
            Absolutely! Students, researchers, and teachers can all use our tool to check the length of essays, reports, or any academic writing projects without any limits.
        </p>
    </div>

    <div class="mb-4">
        <h3 class="h6 fw-semibold">Is there any limit on how much text I can paste?</h3>
        <p class="text-muted">
            No, there are no limits! You can paste as much text as you want and get instant word and character counts. Our tool is completely free and unlimited.
        </p>
    </div>

    <div class="mb-4">
        <h3 class="h6 fw-semibold">Does this tool count spaces in characters?</h3>
        <p class="text-muted">
            Yes, we show both counts — characters with spaces and characters without spaces — so you can choose based on your needs.
        </p>
    </div>

    <div class="mb-4">
        <h3 class="h6 fw-semibold">Can I use this word counter on mobile devices?</h3>
        <p class="text-muted">
            Yes, our word counter is fully responsive and works perfectly on smartphones, tablets, and desktop computers.
        </p>
    </div>
</div>

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