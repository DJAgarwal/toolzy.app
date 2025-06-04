@extends('layouts.app')

@section('content')
<div class="mb-4">
    <textarea id="notepad" class="form-control" rows="15" placeholder="Start typing your notes here..." aria-label="Note area"></textarea>
</div>
<div class="d-flex gap-3 mt-3 flex-wrap">
    <input type="file" id="importNoteInput" accept=".txt,.md, .html, .htm" hidden>
    <button class="btn btn-outline-secondary" id="importNoteBtn">Import Note</button>
    <button class="btn btn-outline-success" id="printNoteBtn">Print Note</button>
    <button class="btn btn-outline-danger" id="clearNoteBtn">Clear Note</button>
    <input type="text" id="filenameInput" class="form-control w-auto" style="max-width:200px;" value="my-note.txt" title="Filename">
    <button class="btn btn-outline-primary" id="downloadNoteBtn">Download Note</button>
</div>
<div class="mt-2 text-muted small">
    <span id="notepadStats">0 words, 0 characters</span>
    <span id="savedIndicator" class="ms-3 text-success d-none">Saved</span>
</div>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function() {
    const notepad = document.getElementById('notepad');
    const downloadNoteBtn = document.getElementById('downloadNoteBtn');
    const clearNoteBtn = document.getElementById('clearNoteBtn');
    const filenameInput = document.getElementById('filenameInput');
    const importNoteBtn = document.getElementById('importNoteBtn');
    const importNoteInput = document.getElementById('importNoteInput');
    const printNoteBtn = document.getElementById('printNoteBtn');
    const notepadStats = document.getElementById('notepadStats');
    const savedIndicator = document.getElementById('savedIndicator');

    // Load from localStorage
    const savedNote = localStorage.getItem('note');
    if (savedNote) {
        notepad.value = savedNote;
    }
    updateStats();

    // Autosave on input
    notepad.addEventListener('input', function() {
        localStorage.setItem('note', notepad.value);
        updateStats();
        showSavedIndicator();
    });

    // Download as .txt or custom extension
    downloadNoteBtn.addEventListener('click', function() {
        const text = notepad.value;
        if (!text.trim()) {
            showToast('Note is empty!', 'danger');
            return;
        }
        let filename = filenameInput.value.trim();
        if (!filename) filename = 'my-note.txt';
        const blob = new Blob([text], { type: 'text/plain' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    });

    // Import .txt/.md file
    importNoteBtn.addEventListener('click', function() {
        importNoteInput.click();
    });
    importNoteInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            notepad.value = e.target.result;
            localStorage.setItem('note', notepad.value);
            updateStats();
            showSavedIndicator();
        };
        reader.readAsText(file);
    });

    // Print note
    printNoteBtn.addEventListener('click', function() {
        const win = window.open('', '', 'width=800,height=600');
        win.document.write('<pre>' + escapeHtml(notepad.value) + '</pre>');
        win.document.close();
        win.print();
    });

    clearNoteBtn.addEventListener('click', function() {
        if (confirm('Are you sure you want to clear your note?')) {
            notepad.value = '';
            localStorage.removeItem('note');
            updateStats();
            showSavedIndicator();
        }
    });

    // Word & char count
    function updateStats() {
        const text = notepad.value;
        const words = text.trim() ? text.trim().split(/\s+/).length : 0;
        const chars = text.length;
        notepadStats.textContent = `${words} word${words!==1?'s':''}, ${chars} character${chars!==1?'s':''}`;
    }

    // Autosave feedback
    function showSavedIndicator() {
        savedIndicator.classList.remove('d-none');
        clearTimeout(savedIndicator._timer);
        savedIndicator._timer = setTimeout(() => savedIndicator.classList.add('d-none'), 1200);
    }

    // Escape HTML for print
    function escapeHtml(str) {
        return str.replace(/[&<>"']/g, function(m) {
            return ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            })[m];
        });
    }

    // Toast utility (assumes you have showToast globally, else use alert)
    window.showToast = window.showToast || function(msg, type='info') {
        alert(msg);
    };
});
</script>
@endpush