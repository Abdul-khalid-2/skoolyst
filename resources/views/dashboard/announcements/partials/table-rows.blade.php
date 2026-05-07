@foreach ($announcements as $announcement)
    @include('dashboard.announcements.partials.table-row', [
        'announcement' => $announcement,
        'rowIndex' => ($announcements->currentPage() - 1) * $announcements->perPage() + $loop->iteration,
    ])
@endforeach
