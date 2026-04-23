@php
    $activeLabel = 'All';
    if (request('difficulty') === 'easy') {
        $activeLabel = 'Easy';
    } elseif (request('difficulty') === 'medium') {
        $activeLabel = 'Medium';
    } elseif (request('difficulty') === 'hard') {
        $activeLabel = 'Hard';
    }
@endphp
<div class="filter-card filter-card--difficulty-static">
    <div class="filter-header filter-header--static" role="status">
        <h5 class="d-flex align-items-center mb-0 text-start">
            <i class="fas fa-sliders-h me-2" aria-hidden="true"></i>
            <span>Filter by Difficulty</span>
        </h5>
        <span class="filter-header-pill" aria-label="Current filter">{{ $activeLabel }}</span>
    </div>
    <div class="filter-body">
        <div class="difficulty-filters">
            <a href="{{ request()->fullUrlWithQuery(['difficulty' => null]) }}"
                class="difficulty-filter {{ ! request('difficulty') ? 'active' : '' }}">
                All ({{ $difficultyStats->sum('count') }})
            </a>
            <a href="{{ request()->fullUrlWithQuery(['difficulty' => 'easy']) }}"
                class="difficulty-filter {{ request('difficulty') === 'easy' ? 'active' : '' }}">
                Easy ({{ $difficultyStats['easy']->count ?? 0 }})
            </a>
            <a href="{{ request()->fullUrlWithQuery(['difficulty' => 'medium']) }}"
                class="difficulty-filter {{ request('difficulty') === 'medium' ? 'active' : '' }}">
                Medium ({{ $difficultyStats['medium']->count ?? 0 }})
            </a>
            <a href="{{ request()->fullUrlWithQuery(['difficulty' => 'hard']) }}"
                class="difficulty-filter {{ request('difficulty') === 'hard' ? 'active' : '' }}">
                Hard ({{ $difficultyStats['hard']->count ?? 0 }})
            </a>
        </div>
    </div>
</div>
