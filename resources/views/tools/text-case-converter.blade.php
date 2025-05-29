@extends('layouts.app')
@section('content')
<div class="mb-3">
    <textarea id="textInput" class="form-control" rows="8" placeholder="Enter your text here..."></textarea>
</div>
<div class="d-flex flex-wrap gap-2 mb-4">
    <button class="btn btn-primary" id="uppercaseBtn">UPPER CASE</button>
    <button class="btn btn-primary" id="lowercaseBtn">lower case</button>
    <button class="btn btn-primary" id="sentencecaseBtn">Sentence case</button>
    <button class="btn btn-primary" id="capitalizedcaseBtn">Capitalized Case</button>
    <button class="btn btn-primary" id="togglecaseBtn">tOGGLE cASE</button>
    <button class="btn btn-primary" id="alternatecaseBtn">aLtErNaTe CaSe</button>
</div>
<div class="mb-5">
    <button class="btn btn-success" id="copyTextBtn">Copy Converted Text</button>
    <button id="downloadResultBtn" class="btn btn-success">Download Result</button>
</div>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('uppercaseBtn').addEventListener('click', function() { convertText('uppercase'); });
    document.getElementById('lowercaseBtn').addEventListener('click', function() { convertText('lowercase'); });
    document.getElementById('sentencecaseBtn').addEventListener('click', function() { convertText('sentencecase'); });
    document.getElementById('capitalizedcaseBtn').addEventListener('click', function() { convertText('capitalizedcase'); });
    document.getElementById('togglecaseBtn').addEventListener('click', function() { convertText('togglecase'); });
    document.getElementById('alternatecaseBtn').addEventListener('click', function() { convertText('alternatecase'); });
    document.getElementById('copyTextBtn').addEventListener('click', copyText);
    document.getElementById('downloadResultBtn').addEventListener('click', downloadResult);
});

function convertText(type) {
    let text = document.getElementById('textInput').value;

    switch(type) {
        case 'uppercase':
            text = text.toUpperCase();
            break;
        case 'lowercase':
            text = text.toLowerCase();
            break;
        case 'sentencecase':
            text = text.toLowerCase().replace(/(^\s*\w|[\.\!\?]\s*\w)/g, c => c.toUpperCase());
            break;
        case 'capitalizedcase':
            text = text.toLowerCase().replace(/\b\w/g, c => c.toUpperCase());
            break;
        case 'togglecase':
            text = text.split('').map(c => c === c.toUpperCase() ? c.toLowerCase() : c.toUpperCase()).join('');
            break;
        case 'alternatecase':
            text = text.split('').map((c, i) => i % 2 === 0 ? c.toLowerCase() : c.toUpperCase()).join('');
            break;
    }

    document.getElementById('textInput').value = text;
}

function copyText() {
    let textArea = document.getElementById('textInput');
    textArea.select();
    textArea.setSelectionRange(0, 99999); // For mobile devices
    document.execCommand('copy');
    showToast('Text copied to clipboard!', 'success');
}

function downloadResult() {
    const blob = new Blob([document.getElementById('textInput').value], { type: 'text/plain' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'cleaned-text.txt';
    link.click();
}
</script>
@endpush