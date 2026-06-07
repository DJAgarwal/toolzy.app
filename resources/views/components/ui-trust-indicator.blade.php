@props([
    'message' => 'This tool processes your data entirely in your browser. Your data is never sent to our servers.',
    'icon' => 'bi-shield-check',
    'type' => 'info'
])

<div class="alert alert-{{ $type }} d-flex align-items-center shadow-sm border-0 rounded-3 mb-4" role="alert">
    <i class="bi {{ $icon }} fs-3 me-3"></i>
    <div>
        <strong>Privacy First:</strong> {{ $message }}
    </div>
</div>
