<x-app-layout>
    @push('css')
        <link rel="stylesheet" href="{{ asset('css/dashboard/schooles/index.css') }}">
    @endpush
    <main class="main-content">
        <section id="schools" class="page-section">
            <x-page-header class="mb-4 flex-wrap gap-2">
                <x-slot name="heading">
                    <h2 class="mb-0">Schools</h2>
                    <p class="text-muted">Manage registered schools</p>
                </x-slot>
                @role('super-admin')
                    <x-slot name="actions">
                        <x-button href="{{ route('schools.create') }}" variant="primary">
                            <i class="fas fa-plus me-2"></i>Add School
                        </x-button>
                    </x-slot>
                @endrole
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
                    ['label' => 'Name', 'key' => 'name', 'sortable' => true],
                    ['label' => 'Email', 'key' => 'email', 'sortable' => true],
                    ['label' => 'Phone', 'key' => 'contact_number', 'sortable' => true],
                    ['label' => 'Address', 'key' => 'address', 'sortable' => true],
                    ['label' => 'Status', 'key' => 'status', 'sortable' => true],
                    ['label' => 'Actions', 'key' => 'actions', 'sortable' => false],
                ]"
                :records="$schools"
                :sortBy="request('sort_by')"
                :sortDir="request('sort_dir', 'desc')"
                :searchValue="request('search')"
                emptyTitle="No schools found"
                emptyDescription="Add a school to get started."
                emptyIcon="fa-school"
            >
                @role('super-admin')
                    <x-slot name="emptyActions">
                        <x-button href="{{ route('schools.create') }}" variant="primary">
                            <i class="fas fa-plus me-2"></i>Add School
                        </x-button>
                    </x-slot>
                @endrole
                <x-slot name="rows">
                    @foreach ($schools as $school)
                        <tr>
                            <td>{{ ($schools->currentPage() - 1) * $schools->perPage() + $loop->iteration }}</td>
                            <td>{{ $school->name }}</td>
                            <td class="text-truncate school-email-col">{{ $school->email ?? '-' }}</td>
                            <td>{{ $school->contact_number ?? '-' }}</td>
                            <td class="text-truncate school-address-col">{{ $school->address ?? '-' }}</td>
                            <td>
                                @if ($school->status?->value === 'active' || $school->status === 'active')
                                    <x-badge variant="success">Active</x-badge>
                                @else
                                    <x-badge variant="secondary">Inactive</x-badge>
                                @endif
                            </td>
                            <td class="text-end">
                                <x-table-action>
                                    <x-table-action-item href="{{ route('schools.edit', $school->id) }}" icon="fa-edit">
                                        Edit
                                    </x-table-action-item>
                                    <x-table-action-item href="{{ route('schools.show', $school->id) }}" icon="fa-eye">
                                        View
                                    </x-table-action-item>
                                    @role('super-admin')
                                        <x-table-action-item
                                            href="{{ route('schools.destroy', $school->id) }}"
                                            icon="fa-trash"
                                            variant="danger"
                                            method="DELETE"
                                            onclick="return confirm('Are you sure?')"
                                        >
                                            Delete
                                        </x-table-action-item>
                                    @endrole
                                </x-table-action>
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-data-table>
        </section>
    </main>
</x-app-layout>
