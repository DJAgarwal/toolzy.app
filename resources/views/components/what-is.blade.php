<h2 class="fw-semibold mb-3">{{ $tool['question'] }}</h2>
<div class="mb-4">
    @foreach ($tool['description'] as $desc)
        <p class="text-muted mb-2">{{ $desc }}</p>
    @endforeach
</div>