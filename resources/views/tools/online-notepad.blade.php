@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center fw-bold">Online Notepad - Toolzy</h1>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <textarea id="notepad" class="form-control" rows="10" placeholder="Start typing your notes here..."></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button class="btn btn-outline-secondary" onclick="saveNote()">Save Note</button>
                        <button class="btn btn-outline-danger" onclick="clearNote()">Clear Note</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="my-5">
        @include('components.what-is')
    </section>

    <section class="my-5">
        @include('components.faq')
    </section>
</div>
@endsection

@push('scripts')
<script>
    const notepad = document.getElementById('notepad');

    window.onload = function() {
        const savedNote = localStorage.getItem('note');
        if (savedNote) {
            notepad.value = savedNote;
        }
    };

    function saveNote() {
        const noteContent = notepad.value;
        localStorage.setItem('note', noteContent);
        alert('Note saved!');
    }

    function clearNote() {
        if (confirm('Are you sure you want to clear your note?')) {
            notepad.value = '';
            localStorage.removeItem('note');
        }
    }
</script>
@endpush