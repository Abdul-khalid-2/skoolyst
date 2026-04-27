{{-- Bilingual: English fields stay in the main form; Urdu is stored in school_translations (locale=ur). --}}
@php
    $urFields = ['name', 'description', 'facilities', 'banner_title', 'banner_tagline', 'school_motto', 'mission', 'vision'];
    $urDefaults = array_fill_keys($urFields, '');
    if (isset($school) && $school->relationLoaded('translations')) {
        $t = $school->translations->where('locale', 'ur')->first();
        if ($t) {
            foreach ($urFields as $f) {
                $urDefaults[$f] = $t->{$f} ?? '';
            }
        }
    }
    $ur = old('translations.ur', $urDefaults);
@endphp

<x-card class="border-0 bg-light mt-4">
    <div class="card-body">
        <h5 class="mb-1">Urdu (اردو) <span class="text-muted small fw-normal">— optional</span></h5>
        <p class="text-muted small mb-3">Visitors on the Urdu version of the site see these when provided; empty fields fall back to English above.</p>

        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" type="button" data-bs-toggle="tab" data-bs-target="#ur-tab-basic" role="tab">Basic</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" type="button" data-bs-toggle="tab" data-bs-target="#ur-tab-banner" role="tab">Banner</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" type="button" data-bs-toggle="tab" data-bs-target="#ur-tab-profile" role="tab">Mission &amp; vision</button>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="ur-tab-basic" role="tabpanel">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="translations_ur_name">School name (Urdu)</label>
                        <input type="text" class="form-control" dir="rtl" id="translations_ur_name" name="translations[ur][name]"
                            value="{{ $ur['name'] ?? '' }}" placeholder="اُردو میں اسکول کا نام">
                        @error('translations.ur.name')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label" for="translations_ur_description">Description (Urdu)</label>
                        <textarea class="form-control" dir="rtl" id="translations_ur_description" name="translations[ur][description]" rows="4"
                            placeholder="تفصیل">{{ $ur['description'] ?? '' }}</textarea>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label" for="translations_ur_facilities">Facilities (Urdu)</label>
                        <textarea class="form-control" dir="rtl" id="translations_ur_facilities" name="translations[ur][facilities]" rows="3"
                            placeholder="سہولیات">{{ $ur['facilities'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="ur-tab-banner" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="translations_ur_banner_title">Banner title (Urdu)</label>
                        <input type="text" class="form-control" dir="rtl" id="translations_ur_banner_title" name="translations[ur][banner_title]"
                            value="{{ $ur['banner_title'] ?? '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="translations_ur_banner_tagline">Banner tagline (Urdu)</label>
                        <input type="text" class="form-control" dir="rtl" id="translations_ur_banner_tagline" name="translations[ur][banner_tagline]"
                            value="{{ $ur['banner_tagline'] ?? '' }}">
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="ur-tab-profile" role="tabpanel">
                <div class="mb-3">
                    <label class="form-label" for="translations_ur_school_motto">School motto (Urdu)</label>
                    <input type="text" class="form-control" dir="rtl" id="translations_ur_school_motto" name="translations[ur][school_motto]"
                        value="{{ $ur['school_motto'] ?? '' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="translations_ur_mission">Mission (Urdu)</label>
                    <textarea class="form-control" dir="rtl" id="translations_ur_mission" name="translations[ur][mission]" rows="3">{{ $ur['mission'] ?? '' }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="translations_ur_vision">Vision (Urdu)</label>
                    <textarea class="form-control" dir="rtl" id="translations_ur_vision" name="translations[ur][vision]" rows="3">{{ $ur['vision'] ?? '' }}</textarea>
                </div>
            </div>
        </div>
    </div>
</x-card>
