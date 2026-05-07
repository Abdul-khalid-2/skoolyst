<x-app-layout>
    @push('css')
        <link rel="stylesheet" href="{{ asset('css/dashboard/announcements/index.css') }}">
    @endpush
    <main class="main-content">
        <section id="announcements" class="page-section">
            <x-page-header class="mb-4 flex-wrap gap-2">
                <x-slot name="heading">
                    <h2 class="mb-0">{{ __('Announcements') }}</h2>
                    <p class="text-muted">{{ __('Create and manage school announcements') }}</p>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('announcements.create') }}" variant="primary">
                        <i class="fas fa-plus me-2"></i>{{ __('Create Announcement') }}
                    </x-button>
                </x-slot>
            </x-page-header>

            @if (session('success'))
                <x-alert variant="success" class="mb-4">
                    {{ session('success') }}
                </x-alert>
            @endif

            @if (session('error'))
                <x-alert variant="danger" class="mb-4">
                    {{ session('error') }}
                </x-alert>
            @endif

            <x-data-table
                class="mb-0"
                :headers="[
                    ['label' => '#', 'key' => 'id', 'sortable' => true],
                    ['label' => __('Title'), 'key' => 'title', 'sortable' => true],
                    ['label' => __('Branch'), 'key' => 'branch_id', 'sortable' => true],
                    ['label' => __('Status'), 'key' => 'status', 'sortable' => true],
                    ['label' => __('Views'), 'key' => 'view_count', 'sortable' => true],
                    ['label' => __('Publish date'), 'key' => 'publish_at', 'sortable' => true],
                    ['label' => __('Actions'), 'key' => 'actions', 'sortable' => false],
                ]"
                :records="$announcements"
                :sortBy="request('sort_by')"
                :sortDir="request('sort_dir', 'desc')"
                :searchValue="request('search')"
                :emptyTitle="__('No announcements found')"
                :emptyDescription="__('Create an announcement to get started.')"
                emptyIcon="fa-bullhorn"
            >
                <x-slot name="emptyActions">
                    <x-button href="{{ route('announcements.create') }}" variant="primary">
                        <i class="fas fa-plus me-2"></i>{{ __('Create Announcement') }}
                    </x-button>
                </x-slot>
                <x-slot name="rows">
                    @include('dashboard.announcements.partials.table-rows', ['announcements' => $announcements])
                </x-slot>
            </x-data-table>
        </section>
    </main>
</x-app-layout>
