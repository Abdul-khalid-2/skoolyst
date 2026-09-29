<x-app-layout>
    <main class="main-content">
        <section id="oauth-client-create" class="page-section">
            <x-page-header class="mb-4">
                <x-slot name="heading">
                    <h2 class="h4 mb-0">Add Connected App</h2>
                    <p class="mb-0 text-muted">Register a new Skoolyst app to allow "Login with Skoolyst"</p>
                </x-slot>
            </x-page-header>

            <x-card>
                <div class="card-body">
                    <form action="{{ route('oauth-clients.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">App Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" placeholder="Skoolyst Blogs" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="redirect_uris" class="form-label">Redirect URL(s) *</label>
                            <textarea class="form-control @error('redirect_uris') is-invalid @enderror"
                                      id="redirect_uris" name="redirect_uris" rows="4"
                                      placeholder="https://blogs.skoolyst.com/auth/skoolyst/callback&#10;http://localhost:8000/auth/skoolyst/callback">{{ old('redirect_uris') }}</textarea>
                            <small class="form-text text-muted">
                                One URL per line. Must be <code>https://</code> in production
                                (<code>http://localhost</code> / <code>127.0.0.1</code> allowed for local dev).
                                Login only redirects to a URL that exactly matches one of these.
                            </small>
                            @error('redirect_uris')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Create App</button>
                            <a href="{{ route('oauth-clients.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </x-card>
        </section>
    </main>
</x-app-layout>
