@extends('layouts.app')
@section('content')
<div class="container py-5">
    <h1 class="text-center mb-4">Text Case Converter</h1>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-3">
                <textarea id="textInput" class="form-control" rows="8" placeholder="Enter your text here..."></textarea>
            </div>

            <div class="d-flex flex-wrap gap-2 justify-content-center mb-4">
                <button class="btn btn-primary" onclick="convertText('uppercase')">UPPER CASE</button>
                <button class="btn btn-primary" onclick="convertText('lowercase')">lower case</button>
                <button class="btn btn-primary" onclick="convertText('sentencecase')">Sentence case</button>
                <button class="btn btn-primary" onclick="convertText('capitalizedcase')">Capitalized Case</button>
                <button class="btn btn-primary" onclick="convertText('togglecase')">tOGGLE cASE</button>
                <button class="btn btn-primary" onclick="convertText('alternatecase')">aLtErNaTe CaSe</button>
                </div>

            <div class="mb-5">
                <button class="btn btn-success w-100" onclick="copyText()">Copy Converted Text</button>
            </div>

        <section class="my-5">
            @include('components.what-is')
        </section>
        <section class="my-5">
            @include('components.faq')
        </section>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
    alert('Text copied to clipboard!');
}
</script>
@endpush