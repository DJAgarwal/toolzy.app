@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center fw-bold">Random Password Generator</h1>
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label for="passwordLength" class="form-label fw-semibold">Password Length: <span id="lengthValue" class="fw-bold">12</span></label>
                        <input type="range" class="form-range" id="passwordLength" min="4" max="50" value="12" oninput="updateLength()">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Characters to include:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="includeUppercase" checked>
                            <label class="form-check-label" for="includeUppercase">A-Z (Uppercase Letters)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="includeLowercase" checked>
                            <label class="form-check-label" for="includeLowercase">a-z (Lowercase Letters)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="includeNumbers" checked>
                            <label class="form-check-label" for="includeNumbers">0-9 (Numbers)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="includeSymbols">
                            <label class="form-check-label" for="includeSymbols">Special Characters (!@#$...)</label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Generated Password:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="generatedPassword" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyPassword()">Copy</button>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg" onclick="generatePassword()">Generate Password</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <section class="my-5">
        @include('components.what-is')
    </section>

    <!-- FAQs Section -->
    <section class="my-5">
        @include('components.faq')
    </section>
</div>
@endsection
@push('scripts')
<script>
function updateLength() {
    document.getElementById('lengthValue').innerText = document.getElementById('passwordLength').value;
}

function generatePassword() {
    const length = document.getElementById('passwordLength').value;
    let chars = '';
    if (document.getElementById('includeUppercase').checked) {
        chars += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }
    if (document.getElementById('includeLowercase').checked) {
        chars += 'abcdefghijklmnopqrstuvwxyz';
    }
    if (document.getElementById('includeNumbers').checked) {
        chars += '0123456789';
    }
    if (document.getElementById('includeSymbols').checked) {
        chars += '!@#$%^&*()-_=+[]{}|;:,.<>?';
    }
    if (chars === '') {
        showToast('Please select at least one character type.', 'danger');
        return;
    }
    let password = '';
    for (let i = 0; i < length; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }

    document.getElementById('generatedPassword').value = password;
}

function copyPassword() {
    const passwordField = document.getElementById('generatedPassword');
    passwordField.select();
    passwordField.setSelectionRange(0, 99999); // For mobile
    navigator.clipboard.writeText(passwordField.value).then(() => {
        showToast('Password copied to clipboard!', 'success');
    });
}
</script>
@endpush