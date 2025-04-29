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

        <section class="my-5">
        @include('components.what-is')
        </section>
        <section class="my-5">
            @include('components.faq')
        </section>
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