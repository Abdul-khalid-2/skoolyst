<x-app-layout>
    <main class="main-content">
        <section id="oauth-clients" class="page-section">
            <x-page-header class="mb-4">
                <x-slot name="heading">
                    <h2 class="h4 mb-0">Connected Apps</h2>
                    <p class="mb-0 text-muted">Apps allowed to use "Login with Skoolyst" (blogs, mcqs, store, ...)</p>
                </x-slot>
                <x-slot name="actions">
                    <a href="{{ route('oauth-clients.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Add App
                    </a>
                </x-slot>
            </x-page-header>

            @if(session('success'))
                <x-alert variant="success" class="mb-3">{{ session('success') }}</x-alert>
            @endif
            @if(session('error'))
                <x-alert variant="error" class="mb-3">{{ session('error') }}</x-alert>
            @endif

            @if($newCredentials ?? null)
                @php $creds = $newCredentials; @endphp
                <x-card class="mb-3" style="border: 2px solid #f59e0b;">
                    <div class="card-body">
                        <h5 class="mb-2"><i class="fas fa-triangle-exclamation text-warning me-2"></i>Save these credentials now</h5>
                        <p class="text-muted mb-3">
                            The secret is shown <strong>only once</strong>. Copy both values into the
                            <code>{{ $creds['name'] }}</code> app's server-side <code>.env</code> right now — if you
                            lose it, you'll have to regenerate (which breaks the old secret immediately).
                        </p>
                        <div class="mb-2">
                            <label class="form-label fw-bold mb-1">Client ID</label>
                            <input type="text" class="form-control" readonly value="{{ $creds['client_id'] }}" onclick="this.select()">
                        </div>
                        <div>
                            <label class="form-label fw-bold mb-1">Client Secret</label>
                            <input type="text" class="form-control" readonly value="{{ $creds['client_secret'] }}" onclick="this.select()">
                        </div>
                    </div>
                </x-card>
            @endif

            <x-card>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>App Name</th>
                                    <th>Client ID</th>
                                    <th>Redirect URLs</th>
                                    <th width="100">Status</th>
                                    <th width="160">Last Used</th>
                                    <th width="220" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clients as $client)
                                <tr>
                                    <td><strong>{{ $client->name }}</strong></td>
                                    <td><code>{{ $client->client_id }}</code></td>
                                    <td>
                                        @foreach($client->redirect_uris as $uri)
                                            <div class="small text-muted">{{ $uri }}</div>
                                        @endforeach
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $client->is_active ? 'success' : 'secondary' }}">
                                            {{ $client->is_active ? 'Active' : 'Disabled' }}
                                        </span>
                                    </td>
                                    <td class="small text-muted">
                                        {{ $client->last_used_at?->diffForHumans() ?? 'Never' }}
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('oauth-clients.edit', $client) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('oauth-clients.regenerate-secret', $client) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning"
                                                        onclick="return confirm('Regenerate secret for {{ $client->name }}? The old secret will stop working immediately.')">
                                                    <i class="fas fa-key"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('oauth-clients.destroy', $client) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Remove {{ $client->name }}? It will no longer be able to log users in.')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No connected apps yet. Click "Add App" to register the first one (e.g. Skoolyst Blogs).
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </x-card>
        </section>
    </main>
</x-app-layout>
