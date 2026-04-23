@if($schools->hasPages())
<div class="row mt-5">
    <div class="col-12">
        <nav aria-label="School pagination">
            <ul class="pagination justify-content-center">
                @if ($schools->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">Previous</span>
                </li>
                @else
                <li class="page-item">
                    <a class="page-link" href="{{ $schools->previousPageUrl() }}" rel="prev">Previous</a>
                </li>
                @endif

                @foreach ($schools->getUrlRange(1, $schools->lastPage()) as $page => $url)
                @if ($page == $schools->currentPage())
                <li class="page-item active">
                    <span class="page-link">{{ $page }}</span>
                </li>
                @else
                <li class="page-item">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
                @endif
                @endforeach

                @if ($schools->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $schools->nextPageUrl() }}" rel="next">Next</a>
                </li>
                @else
                <li class="page-item disabled">
                    <span class="page-link">Next</span>
                </li>
                @endif
            </ul>
        </nav>
    </div>
</div>
@endif
