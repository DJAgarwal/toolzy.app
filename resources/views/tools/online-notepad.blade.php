@extends('layouts.app')

@section('content')
<div class="mb-4">
    <!-- Tabs for multi-note support -->
    <div id="noteTabs" class="nav nav-tabs mb-2" role="tablist" aria-label="Notes navigation"></div>
    <div class="d-flex gap-2 mb-3">
        <button class="btn btn-sm btn-outline-success" id="addNoteTabBtn" aria-label="Add new note tab" title="Add new note tab">+ Add Note</button>
        <button class="btn btn-sm btn-outline-danger" id="removeNoteTabBtn" aria-label="Remove current note tab" title="Remove current note tab" disabled>Remove Note</button>
    </div>
    <textarea id="notepad" class="form-control" rows="15" placeholder="Start typing your notes here..." aria-label="Note area"></textarea>
</div>
<div class="d-flex gap-3 mt-3 flex-wrap align-items-center">
    <input type="file" id="importNoteInput" accept=".txt,.md,.html,.htm,.json" hidden>
    <button class="btn btn-outline-secondary" id="importNoteBtn" aria-label="Import note from file">Import Note</button>
    <button class="btn btn-outline-success" id="printNoteBtn" aria-label="Print note">Print Note</button>
    <button class="btn btn-outline-danger" id="clearNoteBtn" aria-label="Clear note">Clear Note</button>
    <label for="filenameInput" class="visually-hidden">Filename</label>
    <input type="text" id="filenameInput" class="form-control w-auto" style="max-width:200px;" value="my-note.txt" title="Filename" aria-label="Filename">
    <button class="btn btn-outline-primary" id="downloadNoteBtn" aria-label="Download note">Download Note</button>
</div>
<div class="mt-2 text-muted small">
    <span id="notepadStats">0 words, 0 characters</span>
    <span id="savedIndicator" class="ms-3 text-success d-none" role="status" aria-live="polite">Saved</span>
</div>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function() {
    // Notes structure: [{id, name, content, filename}]
    let notes = [];
    let currentIdx = 0;
    const defaultNoteName = 'Note 1';
    const notesStorageKey = 'notes-v2';

    const notepad = document.getElementById('notepad');
    const downloadNoteBtn = document.getElementById('downloadNoteBtn');
    const clearNoteBtn = document.getElementById('clearNoteBtn');
    const filenameInput = document.getElementById('filenameInput');
    const importNoteBtn = document.getElementById('importNoteBtn');
    const importNoteInput = document.getElementById('importNoteInput');
    const printNoteBtn = document.getElementById('printNoteBtn');
    const notepadStats = document.getElementById('notepadStats');
    const savedIndicator = document.getElementById('savedIndicator');
    const noteTabs = document.getElementById('noteTabs');
    const addNoteTabBtn = document.getElementById('addNoteTabBtn');
    const removeNoteTabBtn = document.getElementById('removeNoteTabBtn');

    // Accessibility: focus management helpers
    function focusTab(idx) {
        const tabEl = noteTabs.querySelector(`[data-idx="${idx}"]`);
        if(tabEl) tabEl.focus();
    }

    // Load notes from storage (multi-note aware)
    function loadNotes() {
        const saved = localStorage.getItem(notesStorageKey);
        if (saved) {
            try {
                notes = JSON.parse(saved);
                if (!Array.isArray(notes) || !notes.length) throw 0;
            } catch { notes = [{id: Date.now(), name: defaultNoteName, content: '', filename: 'my-note.txt'}]; }
        } else {
            notes = [{id: Date.now(), name: defaultNoteName, content: '', filename: 'my-note.txt'}];
        }
        currentIdx = 0;
    }
    function saveNotes() {
        localStorage.setItem(notesStorageKey, JSON.stringify(notes));
    }

    // Render tabs
    function renderTabs() {
        noteTabs.innerHTML = '';
        notes.forEach((note, idx) => {
            const tab = document.createElement('button');
            tab.className = 'nav-link' + (idx === currentIdx ? ' active' : '');
            tab.type = 'button';
            tab.textContent = note.name;
            tab.setAttribute('data-idx', idx);
            tab.setAttribute('role', 'tab');
            tab.setAttribute('aria-selected', idx === currentIdx ? 'true' : 'false');
            tab.setAttribute('tabindex', idx === currentIdx ? '0' : '-1');
            tab.title = 'Switch to ' + note.name;
            tab.addEventListener('click', function() { switchToTab(idx); });
            tab.addEventListener('keydown', function(e) {
                // Keyboard navigation for tabs
                if(e.key === 'ArrowRight') {
                    switchToTab((idx+1)%notes.length); focusTab((idx+1)%notes.length);
                } else if(e.key === 'ArrowLeft') {
                    switchToTab((idx-1+notes.length)%notes.length); focusTab((idx-1+notes.length)%notes.length);
                }
            });
            // Double click to rename
            tab.addEventListener('dblclick', function() {
                const newName = prompt('Rename note', note.name);
                if (newName && newName.trim()) {
                    notes[idx].name = newName.trim();
                    saveNotes();
                    renderTabs();
                }
            });
            noteTabs.appendChild(tab);
        });
        removeNoteTabBtn.disabled = notes.length <= 1;
    }

    // Switch to a tab (save current, load new)
    function switchToTab(idx) {
        if(idx === currentIdx) return;
        saveCurrentNote();
        currentIdx = idx;
        loadCurrentNote();
        renderTabs();
    }

    // Save current note content to notes array
    function saveCurrentNote() {
        notes[currentIdx].content = notepad.value;
        notes[currentIdx].filename = filenameInput.value.trim() || `note-${currentIdx+1}.txt`;
        saveNotes();
    }

    // Load current note content from notes array
    function loadCurrentNote() {
        notepad.value = notes[currentIdx].content || '';
        filenameInput.value = notes[currentIdx].filename || `note-${currentIdx+1}.txt`;
        updateStats();
    }

    // Add new note
    addNoteTabBtn.addEventListener('click', function() {
        saveCurrentNote();
        const newNote = {
            id: Date.now(),
            name: `Note ${notes.length+1}`,
            content: '',
            filename: `note-${notes.length+1}.txt`
        };
        notes.push(newNote);
        currentIdx = notes.length-1;
        saveNotes();
        renderTabs();
        loadCurrentNote();
        focusTab(currentIdx);
    });

    // Remove current note
    removeNoteTabBtn.addEventListener('click', function() {
        if(notes.length <= 1) return;
        if(!confirm('Remove this note? This cannot be undone.')) return;
        notes.splice(currentIdx, 1);
        currentIdx = Math.max(0, currentIdx-1);
        saveNotes();
        renderTabs();
        loadCurrentNote();
        focusTab(currentIdx);
    });

    // Accessibility: aria-live for saved indicator
    savedIndicator.setAttribute('aria-live', 'polite');

    // Initial load
    loadNotes();
    renderTabs();
    loadCurrentNote();

    // Autosave on input
    notepad.addEventListener('input', function() {
        notes[currentIdx].content = notepad.value;
        saveNotes();
        updateStats();
        showSavedIndicator();
    });
    filenameInput.addEventListener('input', function() {
        notes[currentIdx].filename = filenameInput.value.trim() || `note-${currentIdx+1}.txt`;
        saveNotes();
    });

    // Download as .txt or custom extension
    downloadNoteBtn.addEventListener('click', function() {
        const text = notepad.value;
        if (!text.trim()) {
            showToast('Note is empty!', 'danger');
            return;
        }
        let filename = filenameInput.value.trim();
        if (!filename) filename = `note-${currentIdx+1}.txt`;
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
            notes[currentIdx].content = notepad.value;
            filenameInput.value = file.name;
            notes[currentIdx].filename = file.name;
            saveNotes();
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
            notes[currentIdx].content = '';
            saveNotes();
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

    // Accessibility: keyboard shortcuts for tabs
    notepad.addEventListener('keydown', function(e) {
        if(e.ctrlKey && e.key === 'Tab') {
            // Ctrl+Tab to next tab
            switchToTab((currentIdx+1)%notes.length);
            focusTab((currentIdx+1)%notes.length);
            e.preventDefault();
        } else if(e.ctrlKey && e.shiftKey && e.key === 'Tab') {
            // Ctrl+Shift+Tab to previous tab
            switchToTab((currentIdx-1+notes.length)%notes.length);
            focusTab((currentIdx-1+notes.length)%notes.length);
            e.preventDefault();
        }
    });

    // Accessibility: allow tab renaming via keyboard
    noteTabs.addEventListener('keydown', function(e) {
        if(e.target.classList.contains('nav-link') && (e.key === 'Enter' || e.key === ' ')) {
            const idx = +e.target.getAttribute('data-idx');
            const newName = prompt('Rename note', notes[idx].name);
            if (newName && newName.trim()) {
                notes[idx].name = newName.trim();
                saveNotes();
                renderTabs();
            }
        }
    });

    // Toast utility (assumes you have showToast globally, else use alert)
    window.showToast = window.showToast || function(msg, type='info') {
        alert(msg);
    };
});
</script>
@endpush