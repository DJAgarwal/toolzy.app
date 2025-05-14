@extends('layouts.app')

@section('content')
<div class="mb-3">
    <label for="conversionType" class="form-label">Select Conversion Type:</label>
    <select class="form-select" id="conversionType">
        <option value="pdf-to-jpg">PDF to JPG</option>
        <option value="jpg-to-pdf">JPG to PDF</option>
        <option value="pdf-to-word">PDF to Word</option>
        <option value="word-to-pdf">Word to PDF</option>
    </select>
</div>
<div class="mb-3">
    <label for="fileInput" class="form-label">Upload File:</label>
    <input type="file" class="form-control" id="fileInput" accept=".pdf,.jpg,.jpeg,.doc,.docx">
</div>
<button class="btn btn-primary" onclick="convertFile()">Convert</button>
<div id="downloadSection" class="mt-4 d-none">
    <h5>Conversion complete!</h5>
    <a id="downloadLink" href="#" class="btn btn-success" download>Download Converted File</a>
</div>   
@endsection

@push('scripts')
<script>
    function convertFile() {
        const type = document.getElementById('conversionType').value;
        const fileInput = document.getElementById('fileInput');
        const file = fileInput.files[0];

        if (!file) {
            showToast('Please upload a file first.', 'danger');
            return;
        }

        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', type);

        fetch("{{ route('tools.pdf-converter.convert') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: formData
        })
        .then(res => res.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const link = document.getElementById('downloadLink');
            link.href = url;
            document.getElementById('downloadSection').classList.remove('d-none');
        })
        .catch(() => {
            showToast('Conversion failed. Please try again.', 'danger');
        });
    }
</script>
@endpush
