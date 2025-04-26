@extends('layouts.app')
@section('content')
<div class="container py-5">
    <h1 class="mb-5 text-center fw-bold">Our Tools</h1>

    @php
        $groupedTools = $tools->groupBy('category');
    @endphp

    @foreach ($groupedTools as $category => $toolsInCategory)
        <div class="mb-5">
            <h2 class="mb-4 text-primary">{{ $category }}</h2>
            <div class="row g-4">
                @foreach ($toolsInCategory as $tool)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-lg hover-shadow transition-all">
                            <div class="card-body text-center p-4 d-flex flex-column">
                                <h5 class="card-title mb-3 fw-semibold">{{ $tool->meta_title }}</h5>
                                <p class="card-text text-muted mb-4 small flex-grow-1">{{ $tool->meta_description }}</p>
                                <a href="{{ url('/tools/' . $tool->page_name) }}" class="btn btn-outline-primary btn-lg w-100 mt-auto">Use Tool</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection