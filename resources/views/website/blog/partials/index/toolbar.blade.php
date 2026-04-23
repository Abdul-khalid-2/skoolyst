<div class="row mb-4">
    <div class="col-md-6">
        <div class="d-flex align-items-center">
            <span class="me-3 text-muted">Sort by:</span>
            <div class="btn-group">
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}"
                    class="btn btn-outline-primary {{ request('sort', 'latest') === 'latest' ? 'active' : '' }}">
                    Latest
                </a>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}"
                    class="btn btn-outline-primary {{ request('sort') === 'popular' ? 'active' : '' }}">
                    Popular
                </a>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'featured']) }}"
                    class="btn btn-outline-primary {{ request('sort') === 'featured' ? 'active' : '' }}">
                    Featured
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6 text-md-end">
        <span class="text-muted">Showing {{ $posts->total() }} articles</span>
    </div>
</div>
