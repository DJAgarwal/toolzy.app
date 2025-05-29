@extends('layouts.app')

@section('content')
<div class="mb-4">
    <textarea id="notepad" class="form-control" rows="15" placeholder="Start typing your notes here..."></textarea>
</div>
<div class="d-flex gap-3 mt-3 flex-wrap">
    <button class="btn btn-outline-primary" id="downloadNoteBtn">Download Note</button>
    <button class="btn btn-outline-danger" id="clearNoteBtn">Clear Note</button>
</div>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function() {
    const notepad = document.getElementById('notepad');
    const downloadNoteBtn = document.getElementById('downloadNoteBtn');
    const clearNoteBtn = document.getElementById('clearNoteBtn');

    // Load from localStorage
    const savedNote = localStorage.getItem('note');
    if (savedNote) {
        notepad.value = savedNote;
    }

    // Autosave on input
    notepad.addEventListener('input', function() {
        localStorage.setItem('note', notepad.value);
    });

    // Download as .txt
    downloadNoteBtn.addEventListener('click', function() {
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
    });

    clearNoteBtn.addEventListener('click', function() {
        if (confirm('Are you sure you want to clear your note?')) {
            notepad.value = '';
            localStorage.removeItem('note');
        }
    });
});
</script>
@endpush