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
                <button class="btn btn-primary" onclick="convertText('uppercase')">UPPERCASE</button>
                <button class="btn btn-primary" onclick="convertText('lowercase')">lowercase</button>
                <button class="btn btn-primary" onclick="convertText('sentencecase')">Sentence case</button>
                <button class="btn btn-primary" onclick="convertText('capitalizedcase')">Capitalized Case</button>
                <button class="btn btn-primary" onclick="convertText('togglecase')">tOGGLE cASE</button>
            </div>

            <div class="mb-5">
                <button class="btn btn-success w-100" onclick="copyText()">Copy Converted Text</button>
            </div>

            <div class="mb-5">
                <h2>What is Text Case Converter?</h2>
                <p>A Text Case Converter is an online tool that helps you quickly change the casing of your text. Whether you need your content in uppercase, lowercase, sentence case, capitalized case, or toggle case, this tool makes it effortless. It’s extremely helpful for writers, editors, students, and anyone dealing with large amounts of text formatting. Save time and ensure consistent text styles with Toolzy’s free and easy-to-use converter.</p>
            </div>

            <div class="mb-5">
                <h2>Frequently Asked Questions</h2>

                <h3>How does the Text Case Converter work?</h3>
                <p>Simply paste your text into the box, click the button for the case you want, and your text will be instantly transformed.</p>

                <h3>Is Toolzy’s Text Case Converter free?</h3>
                <p>Yes, it's 100% free to use without any signup or hidden charges.</p>

                <h3>Can I use it on mobile devices?</h3>
                <p>Absolutely! Our tool is fully responsive and works perfectly on phones, tablets, and desktops.</p>

                <h3>Will it change any special characters in my text?</h3>
                <p>No, only the alphabet letters will change their case. Special characters, numbers, and punctuation will remain untouched.</p>

                <h3>Is my text saved on your servers?</h3>
                <p>No, your text is processed only in your browser for complete privacy.</p>
            </div>
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