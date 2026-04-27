<x-app-layout>
    <main class="main-content">
        <section id="blog-categories" class="page-section">
            <x-page-header class="mb-4">
                <x-slot name="heading">
                    <h2 class="h4 mb-0">Blog Categories</h2>
                    <p class="mb-0 text-muted">Manage your blog categories</p>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('admin.blog-categories.create') }}" variant="primary">
                        <i class="fas fa-plus me-2"></i> Create Category
                    </x-button>
                </x-slot>
            </x-page-header>

            @if(session('success'))
                <x-alert variant="success" class="mb-4">
                    {{ session('success') }}
                </x-alert>
            @endif

            <x-data-table
                class="mb-0"
                :headers="[
                    ['label' => 'Name', 'key' => 'name', 'sortable' => true],
                    ['label' => 'Slug', 'key' => 'slug', 'sortable' => true],
                    ['label' => 'Icon', 'key' => 'icon', 'sortable' => true],
                    ['label' => 'Status', 'key' => 'is_active', 'sortable' => true],
                    ['label' => 'Posts', 'key' => 'blog_posts_count', 'sortable' => true],
                    ['label' => 'Created', 'key' => 'created_at', 'sortable' => true],
                    ['label' => 'Actions', 'key' => 'actions', 'sortable' => false],
                ]"
                :records="$categories"
                :sortBy="request('sort_by')"
                :sortDir="request('sort_dir', 'desc')"
                :searchValue="request('search')"
                emptyTitle="No categories found"
                emptyDescription="Create your first category to get started."
                emptyIcon="fa-folder-open"
            >
                <x-slot name="emptyActions">
                    <x-button href="{{ route('admin.blog-categories.create') }}" variant="primary">
                        <i class="fas fa-plus me-2"></i>Create Category
                    </x-button>
                </x-slot>
                <x-slot name="rows">
                    @foreach ($categories as $category)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if ($category->icon)
                                        <i class="fas fa-{{ $category->icon }} text-primary me-2"></i>
                                    @endif
                                    <strong>{{ $category->name }}</strong>
                                </div>
                                @if ($category->description)
                                    <small class="text-muted">{{ Str::limit($category->description, 30) }}</small>
                                @endif
                            </td>
                            <td>
                                <code>{{ $category->slug }}</code>
                            </td>
                            <td>
                                @if ($category->icon)
                                    <i class="fas fa-{{ $category->icon }} text-muted"></i>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <x-badge :variant="$category->is_active ? 'success' : 'secondary'">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </x-badge>
                            </td>
                            <td>
                                <x-badge variant="info">
                                    {{ $category->blog_posts_count ?? 0 }}
                                </x-badge>
                            </td>
                            <td>
                                <small class="text-muted">{{ $category->created_at->format('M j, Y') }}</small>
                            </td>
                            <td class="text-end">
                                <x-table-action>
                                    <x-table-action-item href="{{ route('admin.blog-categories.edit', $category) }}" icon="fa-edit">
                                        Edit
                                    </x-table-action-item>
                                    <x-table-action-item
                                        href="{{ route('admin.blog-categories.destroy', $category) }}"
                                        icon="fa-trash"
                                        variant="danger"
                                        method="DELETE"
                                        onclick="return confirm('Are you sure you want to delete this category?')"
                                    >
                                        Delete
                                    </x-table-action-item>
                                </x-table-action>
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-data-table>
        </section>
    </main>
</x-app-layout>
