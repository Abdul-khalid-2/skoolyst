@php
    $selectedCurriculumIds = $selectedCurriculumIds ?? old('curriculum_ids', []);
@endphp

<div class="mb-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
        <label class="form-label mb-0">Curriculum <span class="text-danger">*</span></label>
        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCurriculumModal">
            <i class="fas fa-plus me-1"></i> Add Curriculum
        </button>
    </div>
    <div class="input-group input-group-sm mb-2 curriculum-search-wrap">
        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
        <input type="search" class="form-control" id="curriculum-search" placeholder="Search curriculum..." autocomplete="off">
        <button type="button" class="btn btn-outline-secondary" id="curriculum-search-clear" title="Clear search" aria-label="Clear search" style="display:none;">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div id="curriculum-no-results" class="text-muted small mb-2 d-none">No curriculum matches your search.</div>
    <div class="row curriculum-list-scroll" id="curriculum-list">
        @foreach($curriculums as $curriculum)
        <div class="col-md-6 mb-2 curriculum-item" data-curriculum-id="{{ $curriculum->id }}">
            <div class="form-check">
                <input class="form-check-input curriculum-checkbox" type="checkbox" name="curriculum_ids[]"
                    value="{{ $curriculum->id }}" id="curriculum_{{ $curriculum->id }}"
                    {{ in_array($curriculum->id, $selectedCurriculumIds) ? 'checked' : '' }}>
                <label class="form-check-label" for="curriculum_{{ $curriculum->id }}">{{ $curriculum->name }}</label>
            </div>
            @if($curriculum->description)
                <small class="text-muted ms-4 curriculum-description">{{ $curriculum->description }}</small>
            @endif
        </div>
        @endforeach
    </div>
    <div id="curriculum-error" class="text-danger small mt-1" style="display:none;">Please select at least one curriculum.</div>
    @error('curriculum_ids')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
</div>

<div class="modal fade" id="addCurriculumModal" tabindex="-1" aria-labelledby="addCurriculumModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCurriculumModalLabel">Add Curriculum</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="new_curriculum_name" class="form-label">Curriculum Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="new_curriculum_name" maxlength="255" placeholder="e.g., Cambridge O-Level">
                </div>
                <div class="mb-3">
                    <label for="new_curriculum_description" class="form-label">Description</label>
                    <textarea class="form-control" id="new_curriculum_description" rows="3" maxlength="500" placeholder="Optional description"></textarea>
                </div>
                <div id="add-curriculum-error" class="alert alert-danger py-2 small d-none"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="save-new-curriculum-btn">
                    <i class="fas fa-plus me-1"></i> Add &amp; Select
                </button>
            </div>
        </div>
    </div>
</div>

@once
    @push('css')
    <style>
        .curriculum-list-scroll {
            max-height: 280px;
            overflow-y: auto;
            padding-right: 0.25rem;
        }
        .curriculum-item.curriculum-hidden {
            display: none !important;
        }
    </style>
    @endpush
    @push('js')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var listEl = document.getElementById('curriculum-list');
        var searchInput = document.getElementById('curriculum-search');
        var searchClear = document.getElementById('curriculum-search-clear');
        var noResultsEl = document.getElementById('curriculum-no-results');
        var saveBtn = document.getElementById('save-new-curriculum-btn');
        var modalEl = document.getElementById('addCurriculumModal');

        function escHtml(value) {
            return String(value || '').replace(/[&<>"']/g, function (char) {
                return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[char];
            });
        }

        function getCurriculumItemText(item) {
            var label = item.querySelector('.form-check-label');
            var desc = item.querySelector('.curriculum-description');
            return ((label ? label.textContent : '') + ' ' + (desc ? desc.textContent : '')).toLowerCase();
        }

        function filterCurriculumList() {
            if (!listEl || !searchInput) return;

            var query = searchInput.value.trim().toLowerCase();
            var items = listEl.querySelectorAll('.curriculum-item');
            var visibleCount = 0;

            items.forEach(function (item) {
                var matches = !query || getCurriculumItemText(item).indexOf(query) !== -1;
                item.classList.toggle('curriculum-hidden', !matches);
                if (matches) visibleCount++;
            });

            if (noResultsEl) {
                noResultsEl.classList.toggle('d-none', visibleCount > 0 || !query);
            }
            if (searchClear) {
                searchClear.style.display = query ? 'inline-block' : 'none';
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', filterCurriculumList);
            searchInput.addEventListener('search', filterCurriculumList);
        }
        if (searchClear) {
            searchClear.addEventListener('click', function () {
                searchInput.value = '';
                filterCurriculumList();
                searchInput.focus();
            });
        }

        if (!saveBtn || !modalEl) return;

        var nameInput = document.getElementById('new_curriculum_name');
        var descInput = document.getElementById('new_curriculum_description');
        var errorBox  = document.getElementById('add-curriculum-error');
        var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        function showCurriculumError(message) {
            if (!errorBox) return;
            errorBox.textContent = message;
            errorBox.classList.remove('d-none');
        }

        function clearCurriculumError() {
            if (!errorBox) return;
            errorBox.textContent = '';
            errorBox.classList.add('d-none');
        }

        function appendCurriculumItem(curriculum) {
            if (!listEl || document.querySelector('#curriculum_' + curriculum.id)) return;

            var col = document.createElement('div');
            col.className = 'col-md-6 mb-2 curriculum-item';
            col.setAttribute('data-curriculum-id', curriculum.id);
            col.innerHTML =
                '<div class="form-check">' +
                    '<input class="form-check-input curriculum-checkbox" type="checkbox" name="curriculum_ids[]" ' +
                        'value="' + escHtml(curriculum.id) + '" id="curriculum_' + escHtml(curriculum.id) + '" checked>' +
                    '<label class="form-check-label" for="curriculum_' + escHtml(curriculum.id) + '">' + escHtml(curriculum.name) + '</label>' +
                '</div>' +
                (curriculum.description
                    ? '<small class="text-muted ms-4 curriculum-description">' + escHtml(curriculum.description) + '</small>'
                    : '');

            listEl.appendChild(col);
            filterCurriculumList();

            var currErr = document.getElementById('curriculum-error');
            if (currErr) currErr.style.display = 'none';
        }

        saveBtn.addEventListener('click', function () {
            clearCurriculumError();
            var name = nameInput.value.trim();
            if (!name) {
                showCurriculumError('Curriculum name is required.');
                nameInput.focus();
                return;
            }

            saveBtn.disabled = true;

            fetch(@json(route('curriculums.quick-store')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    name: name,
                    description: descInput.value.trim() || null,
                }),
            })
            .then(function (response) {
                return response.json().then(function (data) {
                    return { ok: response.ok, data: data };
                });
            })
            .then(function (result) {
                if (!result.ok) {
                    var message = result.data.message || 'Could not add curriculum.';
                    if (result.data.errors) {
                        var firstKey = Object.keys(result.data.errors)[0];
                        if (firstKey && result.data.errors[firstKey][0]) {
                            message = result.data.errors[firstKey][0];
                        }
                    }
                    showCurriculumError(message);
                    return;
                }

                appendCurriculumItem(result.data.curriculum);
                nameInput.value = '';
                descInput.value = '';

                if (window.bootstrap && modalEl) {
                    bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                }
            })
            .catch(function () {
                showCurriculumError('Something went wrong. Please try again.');
            })
            .finally(function () {
                saveBtn.disabled = false;
            });
        });

        modalEl.addEventListener('hidden.bs.modal', function () {
            clearCurriculumError();
            nameInput.value = '';
            descInput.value = '';
        });
    });
    </script>
    @endpush
@endonce
