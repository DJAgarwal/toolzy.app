@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-5 text-center fw-bold">Our Tools</h1>

    @foreach ($tools as $category => $toolsInCategory)
        <div class="mb-5">
        <h2 class="mb-4 text-dark" style="border-bottom: 2px solid #007bff; padding-bottom: 5px;">{{ $category }}</h2>
            <div class="row">
                @foreach ($toolsInCategory as $tool)
                    <div class="col-12 col-md-4">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <!-- Bullet point using a simple inline list -->
                            <ul class="list-unstyled">
                                <li class="d-flex align-items-center">
                                    <span class="me-2" style="font-size: 1.5rem;">&#8226;</span> <!-- Unicode for bullet -->
                                    <a href="{{ url('/tools/' . $tool->page_name) }}" class="text-decoration-none fw-semibold">{{ $tool->meta_title }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection