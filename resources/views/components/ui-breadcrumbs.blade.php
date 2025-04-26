@if (!empty($breadcrumbs) && count($breadcrumbs) > 1)
    <nav aria-label="breadcrumb" class="my-3 custom-breadcrumb">
        <ol class="breadcrumb mb-0 bg-white px-3 py-2 rounded shadow-sm">
            @foreach ($breadcrumbs as $index => $crumb)
                @if ($index !== count($breadcrumbs) - 1)
                    <li class="breadcrumb-item">
                        <a href="{{ $crumb['url'] }}">{{ $crumb['name'] }}</a>
                    </li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">{{ $crumb['name'] }}</li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif