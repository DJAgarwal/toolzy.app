@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-5 text-center fw-bold">Our Tools</h1>

    @php $i = 0; @endphp
@foreach ($tools as $category => $toolsInCategory)
    @if ($i % 2 == 0)
        <div class="row mb-5">
    @endif

    <div class="col-12 col-md-6">
        <h2 class="mb-4 text-dark border-bottom pb-2 border-primary custom-border-blue">{{ $category }}</h2>
        <div class="row">
            @foreach ($toolsInCategory as $tool)
                <div class="col-12 col-md-6">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <ul class="list-unstyled">
                            <li class="d-flex align-items-center">
                                <span class="me-2 fs-4">&#8226;</span>
                                <a href="{{ url('/tools/' . $tool->page_name) }}" class="text-decoration-none fw-semibold">{{ $tool->meta_title }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @php $i++; @endphp
    @if ($i % 2 == 0 || $loop->last)
        </div>
    @endif
@endforeach

</div>
@endsection