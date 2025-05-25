@extends('layouts.app')

@section('content')
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
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="avoidAmbiguous">
        <label class="form-check-label" for="avoidAmbiguous">Avoid Ambiguous Characters (e.g. l, I, 1, 0, O)</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="usePassphrase">
        <label class="form-check-label" for="usePassphrase">Generate Passphrase (e.g., sunny-piano-coffee) (overrides other settings)</label>
    </div>
</div>

<div class="mb-4">
    <label class="form-label fw-semibold">Generated Password:</label>
    <div class="input-group">
        <input type="text" class="form-control" id="generatedPassword" readonly>
        <button class="btn btn-outline-secondary" type="button" onclick="copyPassword()">Copy</button>
    </div>
</div>
<div class="mt-4">
    <label class="form-label fw-semibold">Password Strength:</label>
    <div class="progress rounded-pill h-10">
        <div id="strengthBar" class="progress-bar bg-success progressbar" role="progressbar"></div>
    </div>
</div>
<div class="row mt-4 g-2">
    <div class="col-12 col-md-6 d-grid">
        <button class="btn btn-primary" onclick="generatePassword()">Generate Password</button>
    </div>
    <div class="col-12 col-md-6 d-grid">
        <button class="btn btn-outline-secondary" onclick="downloadPassword()">Download Password(.txt)</button>
    </div>
</div>            
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    generatePassword();
});
function updateLength() {
    document.getElementById('lengthValue').innerText = document.getElementById('passwordLength').value;
}
function generatePassword() {
    const length = parseInt(document.getElementById('passwordLength').value);
    const avoidAmbiguous = document.getElementById('avoidAmbiguous').checked;
    const usePassphrase = document.getElementById('usePassphrase').checked;
    const wordList = [
        "apple", "banana", "coffee", "dragon", "ember", "forest", "galaxy", "honey",
        "ice", "jungle", "kitten", "lemon", "moon", "ninja", "ocean", "piano",
        "quartz", "river", "sunny", "tiger", "umbrella", "velvet", "whale", "xenon",
        "yoga", "zebra", "anchor", "blossom", "cloud", "daisy", "echo", "flame",
        "glow", "harvest", "island", "jewel", "karma", "lantern", "mirage", "nebula",
        "opal", "parrot", "quest", "ripple", "shadow", "twilight", "unicorn", "vortex",
        "wander", "xylophone", "yonder", "zephyr", "acorn", "breeze", "candle", "dolphin",
        "eagle", "feather", "giggle", "hazel", "ink", "jigsaw", "kettle", "lighthouse",
        "maple", "nectar", "owl", "petal", "quokka", "rocket", "sapphire", "thunder",
        "utopia", "volcano", "willow", "xray", "yeti", "zenith", "avocado", "butter",
        "cactus", "dew", "elm", "frost", "glacier", "harbor", "ivy", "jasper", "koala",
        "lunar", "meadow", "noodle", "orbit", "puddle", "quill", "rainbow", "silk"
        ];
    const ambiguous = 'Il1O0';

    let chars = '';
    if (usePassphrase) {
    const numWords = 4;
    let passphrase = [];
    for (let i = 0; i < numWords; i++) {
        const word = wordList[Math.floor(Math.random() * wordList.length)];
        passphrase.push(word);
    }
    const result = passphrase.join('-');
    document.getElementById('generatedPassword').value = result;
    updateStrength(result.length, result);
    return;
}


    if (document.getElementById('includeUppercase').checked) chars += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if (document.getElementById('includeLowercase').checked) chars += 'abcdefghijklmnopqrstuvwxyz';
    if (document.getElementById('includeNumbers').checked) chars += '0123456789';
    if (document.getElementById('includeSymbols').checked) chars += '!@#$%^&*()-_=+[]{}|;:,.<>?';

    if (chars === '') {
        showToast('Please select at least one character type.', 'danger');
        return;
    }

    if (avoidAmbiguous) {
        chars = chars.split('').filter(c => !ambiguous.includes(c)).join('');
    }

    let password = '';
    for (let i = 0; i < length; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }

    document.getElementById('generatedPassword').value = password;
    updateStrength(length, password);
}

function generatePronounceablePassword(length) {
    const vowels = 'aeiou';
    const consonants = 'bcdfghjklmnpqrstvwxyz';
    let password = '';
    for (let i = 0; i < length; i++) {
        const useVowel = i % 2 !== 0;
        password += useVowel
            ? vowels.charAt(Math.floor(Math.random() * vowels.length))
            : consonants.charAt(Math.floor(Math.random() * consonants.length));
    }
    return password;
}

function copyPassword() {
    const passwordField = document.getElementById('generatedPassword');
    passwordField.select();
    passwordField.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(passwordField.value).then(() => {
        showToast('Password copied to clipboard!', 'success');
    });
}

function downloadPassword() {
    const password = document.getElementById('generatedPassword').value;
    const blob = new Blob([password], { type: 'text/plain' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'password.txt';
    link.click();
}

function updateStrength(length, password = '') {
    let score = 0;

    if (password.match(/[a-z]/)) score++;
    if (password.match(/[A-Z]/)) score++;
    if (password.match(/[0-9]/)) score++;
    if (password.match(/[^a-zA-Z0-9]/)) score++;
    if (length >= 12) score++;

    const width = (score / 5) * 100;
    const strengthBar = document.getElementById('strengthBar');

    strengthBar.style.width = width + '%';

    if (width < 40) {
        strengthBar.className = 'progress-bar bg-danger';
    } else if (width < 70) {
        strengthBar.className = 'progress-bar bg-warning';
    } else {
        strengthBar.className = 'progress-bar bg-success';
    }
}
</script>
@endpush