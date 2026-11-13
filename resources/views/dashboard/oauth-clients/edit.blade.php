<x-app-layout>
    <main class="main-content">
        <section id="oauth-client-edit" class="page-section">
            <x-page-header class="mb-4">
                <x-slot name="heading">
                    <h2 class="h4 mb-0">Edit Connected App</h2>
                    <p class="mb-0 text-muted">{{ $client->name }} — <code>{{ $client->client_id }}</code></p>
                </x-slot>
            </x-page-header>

            <x-card>
                <div class="card-body">
                    <form action="{{ route('oauth-clients.update', $client) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">App Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $client->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="redirect_uris" class="form-label">Redirect URL(s) *</label>
                            <textarea class="form-control @error('redirect_uris') is-invalid @enderror"
                                      id="redirect_uris" name="redirect_uris" rows="4">{{ old('redirect_uris', implode("\n", $client->redirect_uris)) }}</textarea>
                            <small class="form-text text-muted">
                                One URL per line. Must be <code>https://</code> in production
                                (<code>http://localhost</code> / <code>127.0.0.1</code> allowed for local dev).
                            </small>
                            @error('redirect_uris')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3 form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $client->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active (uncheck to immediately block this app from logging users in)
                            </label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{ route('oauth-clients.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </x-card>
        </section>
    </main>
</x-app-layout>
