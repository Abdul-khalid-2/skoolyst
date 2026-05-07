<tr>
    <td>{{ $rowIndex }}</td>
    <td class="text-truncate announcement-title-col">{{ $announcement->title }}</td>
    <td class="text-truncate announcement-branch-col">{{ $announcement->branch?->name ?? __('All Branches') }}</td>
    <td>
        @include('dashboard.announcements.partials.status-badge', ['announcement' => $announcement])
    </td>
    <td>{{ $announcement->view_count }}</td>
    <td>{{ $announcement->publish_at?->format('M d, Y') ?? __('Not set') }}</td>
    <td class="text-end">
        <x-table-action>
            <x-table-action-item href="{{ route('announcements.show', $announcement->uuid) }}" icon="fa-eye">
                {{ __('View') }}
            </x-table-action-item>
            <x-table-action-item href="{{ route('announcements.edit', $announcement->uuid) }}" icon="fa-edit">
                {{ __('Edit') }}
            </x-table-action-item>
            <x-table-action-item
                href="{{ route('announcements.destroy', $announcement->uuid) }}"
                icon="fa-trash"
                variant="danger"
                method="DELETE"
                onclick="return confirm(@json(__('Are you sure?')))"
            >
                {{ __('Delete') }}
            </x-table-action-item>
        </x-table-action>
    </td>
</tr>
