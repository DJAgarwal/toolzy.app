@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h1 class="mb-4">Our Tools</h1>

    <div class="row">
    @foreach ($tools as $tool)
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 shadow-lg hover-shadow">
                    <div class="card-body text-center p-4">
                        <h5 class="card-title mb-3 fw-bold">{{ $tool->meta_title }}</h5>
                        <p class="card-text text-muted mb-4">{{ $tool->meta_description }}</p>
                        <a href="{{ url('/tools/' . $tool->page_name) }}" class="btn btn-outline-primary btn-lg">Use Tool</a>
                    </div>
                </div>
            </div>
            @endforeach
    </div>
</div>
@endsection