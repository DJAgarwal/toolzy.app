@extends('layouts.app')

@section('content')
<div class="mb-4">
    <textarea id="notepad" class="form-control" rows="15" placeholder="Start typing your notes here..."></textarea>
</div>
<div class="d-flex gap-3 mt-3 flex-wrap">
    <button class="btn btn-outline-primary" onclick="downloadNote()">Download Note</button>
    <button class="btn btn-outline-danger" onclick="clearNote()">Clear Note</button>
</div>
@endsection

@push('scripts')
<script>
    const notepad = document.getElementById('notepad');

    // Load from localStorage
    window.onload = () => {
        const savedNote = localStorage.getItem('note');
        if (savedNote) {
            notepad.value = savedNote;
        }
    };

    // Autosave on input
    notepad.addEventListener('input', () => {
        localStorage.setItem('note', notepad.value);
    });

    // Download as .txt
    function downloadNote() {
        const text = notepad.value;
        if (!text.trim()) {
        showToast('Note is empty!', 'danger');
            return;
        }

        const blob = new Blob([text], { type: 'text/plain' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'my-note.txt';
        link.click();
    }

    function clearNote() {
        if (confirm('Are you sure you want to clear your note?')) {
            notepad.value = '';
            localStorage.removeItem('note');
        }
    }
</script>
@endpush