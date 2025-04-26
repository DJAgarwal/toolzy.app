@if (!empty($breadcrumbs) && count($breadcrumbs) > 1)
    <nav aria-label="breadcrumb" class="py-2">
        <ol class="breadcrumb bg-light rounded mb-0 px-4 py-3 shadow-sm small">
            @foreach ($breadcrumbs as $index => $crumb)
                @if ($index !== count($breadcrumbs) - 1)
                    <li class="breadcrumb-item">
                        <a href="{{ $crumb['url'] }}" class="text-decoration-none text-primary">{{ $crumb['name'] }}</a>
                    </li>
                @else
                    <li class="breadcrumb-item active text-muted" aria-current="page">{{ $crumb['name'] }}</li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif