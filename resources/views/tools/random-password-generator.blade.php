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
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()-_=+[]{}|;:,.<>?';
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
        alert('Password copied to clipboard!');
    });
}
</script>
@endpush