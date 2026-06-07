<!-- Tools Cards -->
<div class="row g-4" id="tools-grid">
    @forelse ($tools as $tool)
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-lg hover-shadow transition-all">
            <div class="card-body text-center p-4 d-flex flex-column">
                <h3 class="card-title mb-3 fw-semibold">{{ $tool->meta_title }}</h3>
                <p class="card-text text-muted mb-4 small flex-grow-1">{{ $tool->meta_description }}</p>
                <a href="{{ url('/tools/' . $tool->page_name) }}" class="btn btn-outline-primary btn-lg w-100 mt-auto">Use Tool</a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <p class="text-muted fs-4">No tools found matching your search.</p>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($tools->hasPages())
<div class="d-flex justify-content-center mt-4" id="tools-pagination">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item {{ $tools->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $tools->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            @for ($i = 1; $i <= $tools->lastPage(); $i++)
                <li class="page-item {{ $tools->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $tools->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
            <li class="page-item {{ $tools->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $tools->nextPageUrl() }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
@endif
