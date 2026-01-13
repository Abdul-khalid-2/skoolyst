<div class="dropdown">
    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ strtoupper($currentLocale) }}
    </button>
    <ul class="dropdown-menu">
        @foreach($locales as $localeCode => $properties)
            @if($localeCode !== $currentLocale)
                <li>
                    <a class="dropdown-item" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                        {{ $properties['native'] }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>