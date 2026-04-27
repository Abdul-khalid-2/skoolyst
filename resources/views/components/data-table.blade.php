{{--
USAGE EXAMPLE
———
(Use :records for the paginator/list used by total count + pagination links. A :rows prop would conflict with the <x-slot name="rows"> in Blade.)

<x-data-table
    :headers="[
        ['label' => '#',       'key' => 'id',         'sortable' => true],
        ['label' => 'Name',    'key' => 'name',        'sortable' => true],
        ['label' => 'Status',  'key' => 'status',      'sortable' => false],
        ['label' => 'Actions', 'key' => 'actions',     'sortable' => false],
    ]"
    :records="$schools"
    :sortBy="request('sort_by')"
    :sortDir="request('sort_dir', 'asc')"
    :searchValue="request('search')"
>
    <x-slot name="rows">
        @foreach($schools as $school)
        <tr>
            <td>{{ $school->id }}</td>
            <td>{{ $school->name }}</td>
            <td><x-badge variant="success">Active</x-badge></td>
            <td>
                <x-table-action>
                    <x-table-action-item href="{{ route('schools.show', $school) }}" icon="fa-eye">View</x-table-action-item>
                    <x-table-action-item href="{{ route('schools.edit', $school) }}" icon="fa-pen">Edit</x-table-action-item>
                    <x-table-action-item href="{{ route('schools.destroy', $school) }}" icon="fa-trash" variant="danger" method="DELETE">Delete</x-table-action-item>
                </x-table-action>
            </td>
        </tr>
        @endforeach
    </x-slot>
</x-data-table>
———
--}}

@props([
    'headers' => [],
    'sortBy' => null,
    'sortDir' => 'asc',
    'searchable' => true,
    'searchValue' => '',
    'bulkActions' => false,
    'records' => null,
    'emptyTitle' => 'No records found',
    'emptyDescription' => null,
    'emptyIcon' => 'fa-inbox',
])

@php
    $sortDir = strtolower((string) $sortDir) === 'desc' ? 'desc' : 'asc';
    $hasPaginator = is_object($records) && method_exists($records, 'links');
    $totalRecords = null;
    if ($records !== null) {
        $totalRecords = $hasPaginator ? $records->total() : (method_exists($records, 'count') ? $records->count() : null);
    }
    $showAutoEmpty = $records !== null && $totalRecords === 0;
    $colCount = count($headers) + ($bulkActions ? 1 : 0);

    $mergeQuery = static function (array $overrides, bool $resetPage = false): string {
        $query = array_merge(request()->query(), $overrides);
        if ($resetPage) {
            unset($query['page']);
        }
        return http_build_query($query);
    };
@endphp

<x-card {{ $attributes }}>
    <div class="card-header bg-white py-3 border-bottom">
        <div class="row align-items-center g-2">
            <div class="col">
                @if ($totalRecords !== null)
                    <span class="text-muted small">{{ $totalRecords }} {{ Str::plural('record', $totalRecords) }}</span>
                @endif
            </div>
            @if ($searchable)
                <div class="col-auto">
                    <form method="GET" action="{{ request()->url() }}" class="d-flex align-items-center gap-2">
                        @foreach (request()->except(['search', 'page']) as $name => $value)
                            @if (is_array($value))
                                @foreach ($value as $subKey => $subVal)
                                    <input type="hidden" name="{{ $name }}[{{ $subKey }}]" value="{{ $subVal }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <input type="hidden" name="page" value="1">
                        <div class="input-group input-group-sm" style="min-width: 220px;">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted" aria-hidden="true"></i></span>
                            <input
                                type="search"
                                name="search"
                                value="{{ $searchValue }}"
                                class="form-control border-start-0"
                                placeholder="{{ __('Search…') }}"
                                aria-label="{{ __('Search') }}"
                            >
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>

    @if ($bulkActions && isset($bulkBar))
        <div class="border-bottom px-3 py-2 bg-light">
            {{ $bulkBar }}
        </div>
    @endif

    <div class="card-body p-0">
        <div class="table-responsive overflow-x-auto" style="-webkit-overflow-scrolling: touch;">
            <table class="table table-striped table-hover align-middle mb-0 text-nowrap">
                <thead class="table-light">
                    <tr>
                        @if ($bulkActions)
                            <th scope="col" class="ps-3" style="width: 2.5rem;">
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    aria-label="{{ __('Select all') }}"
                                    onchange="(function (master) { var root = master.closest('table'); if (!root) return; var v = master.checked; root.querySelectorAll('tbody .data-table-row-checkbox').forEach(function (cb) { cb.checked = v; }); })(this)"
                                >
                            </th>
                        @endif
                        @foreach ($headers as $col)
                            @php
                                $key = $col['key'] ?? '';
                                $label = $col['label'] ?? '';
                                $isSortable = ! empty($col['sortable']);
                                $nextDir = ($sortBy === $key && $sortDir === 'asc') ? 'desc' : 'asc';
                                $sortUrl = $isSortable
                                    ? request()->url() . '?' . $mergeQuery(['sort_by' => $key, 'sort_dir' => $nextDir], true)
                                    : null;
                            @endphp
                            <th scope="col">
                                @if ($isSortable && $sortUrl)
                                    <a href="{{ $sortUrl }}" class="text-decoration-none text-body d-inline-flex align-items-center gap-1">
                                        <span>{{ $label }}</span>
                                        @if ($sortBy === $key)
                                            <i class="fas {{ $sortDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }} text-primary" aria-hidden="true"></i>
                                        @else
                                            <i class="fas fa-sort text-muted opacity-50" style="font-size: 0.75em;" aria-hidden="true"></i>
                                        @endif
                                    </a>
                                @else
                                    {{ $label }}
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @if ($showAutoEmpty)
                        <tr>
                            <td colspan="{{ $colCount }}" class="p-0 border-0">
                                <x-empty-state
                                    :title="$emptyTitle"
                                    :description="$emptyDescription"
                                    :icon="$emptyIcon"
                                >
                                    @isset($emptyActions)
                                        <x-slot name="actions">
                                            {{ $emptyActions }}
                                        </x-slot>
                                    @endisset
                                </x-empty-state>
                            </td>
                        </tr>
                    @else
                        {{ $rows }}
                    @endif
                </tbody>
            </table>
        </div>

        @if ($hasPaginator && ! $showAutoEmpty)
            <div class="border-top px-3 py-3">
                {{ $records->withQueryString()->links() }}
            </div>
        @endif
    </div>
</x-card>
