@extends('layouts.app')

@section('content')
<section class="py-5">
    <div class="container">
        <h1 class="mb-4 text-center">Remove Duplicate Lines</h1>
        <p class="text-center mb-5">Easily remove repeated lines from your text with our simple tool. Just paste your text below and clean it instantly!</p>

        <div class="input-group input-group-lg mx-auto" style="max-width: 800px;">
            <textarea id="inputText" class="form-control" rows="10" placeholder="Paste your text here..."></textarea>
        </div>

        <div class="text-center my-4">
            <button onclick="removeDuplicates()" class="btn btn-primary btn-lg">Remove Duplicate Lines</button>
        </div>

        <div class="input-group input-group-lg mx-auto" style="max-width: 800px;">
            <textarea id="outputText" class="form-control" rows="10" placeholder="Result will appear here..." readonly></textarea>
        </div>

        <div class="mt-5">
            <h2>What is Remove Duplicate Lines Tool?</h2>
            <p>
                The Remove Duplicate Lines tool allows you to quickly clean up any repeated lines from a block of text. Whether you're working with large data, code snippets, lists, or any repetitive text, this tool makes it effortless to get clean, unique lines. No downloads or software installation needed — everything works online instantly.
            </p>
        </div>

        <div class="mt-5">
            <h2>FAQs about Removing Duplicate Lines</h2>

            <h5 class="mt-4">How does this tool remove duplicate lines?</h5>
            <p>It automatically compares each line of text and removes any line that appears more than once.</p>

            <h5 class="mt-4">Is my data safe?</h5>
            <p>Yes, everything happens directly in your browser. We do not store or see your text.</p>

            <h5 class="mt-4">Can I use this tool for large text files?</h5>
            <p>Yes, you can paste large blocks of text. However, very huge files might slow down your browser slightly.</p>

            <h5 class="mt-4">Does it change the order of lines?</h5>
            <p>No, it keeps the first occurrence and removes later duplicates, maintaining your original order.</p>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function removeDuplicates() {
    const inputText = document.getElementById('inputText').value;
    const lines = inputText.split('\n');
    const uniqueLines = [...new Set(lines)];
    document.getElementById('outputText').value = uniqueLines.join('\n');
}
</script>
@endpush