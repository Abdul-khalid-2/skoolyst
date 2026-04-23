@if ($topics->count() > 0)
    @php
        $selected = request('topic') ? $topics->firstWhere('id', (int) request('topic')) : null;
    @endphp
    <div class="filter-card filter-card--collapsible">
        <button class="filter-header filter-header--toggle" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMcqFilterTopics" aria-expanded="true" aria-controls="collapseMcqFilterTopics">
            <h5 class="d-flex align-items-center mb-0 text-start flex-grow-1 min-w-0">
                <i class="fas fa-folder me-2 flex-shrink-0" aria-hidden="true"></i>
                <span>Topics</span>
            </h5>
            <span class="filter-header-pill" aria-label="Selected topic">{{ $selected->title ?? 'All' }}</span>
            <i class="fas fa-chevron-down filter-chevron ms-2 flex-shrink-0" aria-hidden="true"></i>
        </button>
        <div class="collapse show" id="collapseMcqFilterTopics">
            <div class="filter-body">
                <div class="mb-3">
                    <label for="mcqTopicSearch" class="form-label small text-muted mb-1 d-block">Filter topics by name</label>
                    <input type="search" class="form-control form-control-sm" id="mcqTopicSearch" placeholder="Type to search topics…" autocomplete="off" inputmode="search">
                </div>
                <div class="topic-filters topic-filters--scroll" id="mcqTopicFilterList">
                    <a href="{{ request()->fullUrlWithQuery(['topic' => null]) }}"
                        class="topic-filter text-start w-100 {{ ! request('topic') ? 'active' : '' }}"
                        data-topic-title="all topics">
                        All topics ({{ $topics->sum('mcqs_count') }})
                    </a>
                    @foreach ($topics as $topic)
                        <a href="{{ request()->fullUrlWithQuery(['topic' => $topic->id]) }}"
                            class="topic-filter text-start w-100 {{ (string) request('topic') == (string) $topic->id ? 'active' : '' }}"
                            data-topic-title="{{ \Illuminate\Support\Str::lower($topic->title) }}">
                            {{ $topic->title }} ({{ $topic->mcqs_count }})
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
