@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center fw-bold">QR Code Generator - Toolzy</h1>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">

                    <!-- QR Code Content Input -->
                    <div class="mb-4">
                        <label for="qrContent" class="form-label fw-semibold">Enter Text or URL:</label>
                        <input type="text" class="form-control" id="qrContent" placeholder="Enter text or URL">
                    </div>

                    <!-- QR Code Preview -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Preview QR Code:</label>
                        <div id="qrCodePreview" class="text-center">
                            <!-- Placeholder for QR Code Image -->
                        </div>
                    </div>

                    <!-- Generate QR Code Button -->
                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg" onclick="generateQRCode()">Generate QR Code</button>
                    </div>

                    <!-- Copy QR Code Button -->
                    <div class="mt-3 text-center">
                        <button class="btn btn-outline-secondary" onclick="downloadQRCode()">Download QR Code</button>
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
<script src="{{ asset('js/qrcode.min.js') }}"></script>
<script>
    function generateQRCode() {
        var qrContent = document.getElementById('qrContent').value;
        if (qrContent.trim() === '') {
            alert('Please enter some text or URL.');
            return;
        }

        // Clear previous QR Code
        document.getElementById('qrCodePreview').innerHTML = '';

        // Create new QR Code
        var qrcode = new QRCode(document.getElementById('qrCodePreview'), {
            text: qrContent,
            width: 200,
            height: 200,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    }

    function downloadQRCode() {
        var qrCodeImage = document.querySelector('#qrCodePreview img');
        if (qrCodeImage) {
            var link = document.createElement('a');
            link.href = qrCodeImage.src;
            link.download = 'qr-code.png';
            link.click();
        } else {
            alert('Please generate a QR Code first.');
        }
    }
</script>
@endpush