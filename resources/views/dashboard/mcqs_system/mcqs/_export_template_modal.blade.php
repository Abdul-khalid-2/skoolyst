{{--
    Smart Export Template Modal
    --------------------------------------------------------------
    Lets the user pick a Subject, Topic and (optional) Test Types,
    then downloads a pre-filled CSV template ready for bulk import.

    Required parent variables:
      - $subjects   : collection of active Subject models
--}}
<x-bs-modal
    id="exportTemplateModal"
    title="Download MCQ Import Template"
    size="lg"
    :scrollable="true"
    labelledBy="exportTemplateModalLabel"
>
    <p class="text-muted mb-4">
        Select subject, topic and test type to pre-fill your template.
        The downloaded CSV will contain the correct headers and sample rows ready for editing.
    </p>

    <x-alert
        variant="error"
        :dismissible="false"
        :icon="false"
        id="exportTemplateError"
        class="d-none mb-3 mx-0"
    />

    <form id="exportTemplateForm" novalidate>
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="exportTemplateSubject" class="form-label">
                    Subject <span class="text-danger">*</span>
                </label>
                <select class="form-select" id="exportTemplateSubject" name="subject_id" required>
                    <option value="">Select Subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-6">
                <label for="exportTemplateTopic" class="form-label">
                    Topic <span class="text-danger">*</span>
                </label>
                <select class="form-select" id="exportTemplateTopic" name="topic_id" required disabled>
                    <option value="">Select a subject first</option>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label d-flex align-items-center justify-content-between mb-2">
                    <span>Test Types <small class="text-muted">(optional)</small></span>
                    <small class="text-muted" id="exportTemplateTestTypeHint"></small>
                </label>
                <div
                    id="exportTemplateTestTypesContainer"
                    class="border rounded p-3 bg-light"
                    style="min-height: 80px;"
                >
                    <p class="text-muted mb-0">Select a subject to see available test types.</p>
                </div>
                <small class="text-muted d-block mt-1">
                    Selected test type names will be joined with commas in the <code>test_types</code> column
                    and attached to every imported MCQ. The <code>question_type</code> column controls the
                    question format (<code>mcq</code> / <code>true_false</code> / <code>multi_select</code>).
                </small>
            </div>
        </div>
    </form>

    <x-slot name="footer">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center w-100">
            <small class="text-muted" id="exportTemplateFooterHint">Choose a subject and topic to enable download.</small>
            <div class="d-flex gap-2">
                <x-button variant="outline-secondary" type="button" data-bs-dismiss="modal">Cancel</x-button>
                <x-button variant="primary" type="button" id="exportTemplateDownloadBtn" disabled>
                    <i class="fas fa-download me-1"></i> Download Template
                </x-button>
            </div>
        </div>
    </x-slot>
</x-bs-modal>

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('exportTemplateModal');
        if (!modalEl) return;

        const EXPORT_URL = @json(route('mcqs.exportTemplate'));
        const TOPICS_URL = @json(route('mcqs.get-topics'));
        const TEST_TYPES_URL = @json(route('mcqs.get-test-types'));

        const subjectSelect = document.getElementById('exportTemplateSubject');
        const topicSelect = document.getElementById('exportTemplateTopic');
        const testTypesContainer = document.getElementById('exportTemplateTestTypesContainer');
        const testTypeHint = document.getElementById('exportTemplateTestTypeHint');
        const downloadBtn = document.getElementById('exportTemplateDownloadBtn');
        const footerHint = document.getElementById('exportTemplateFooterHint');
        const errorBox = document.getElementById('exportTemplateError');

        modalEl.addEventListener('hidden.bs.modal', resetForm);

        subjectSelect.addEventListener('change', function () {
            const subjectId = this.value;
            resetTopic();
            resetTestTypes();
            if (subjectId) {
                loadTopics(subjectId);
                loadTestTypes(subjectId);
            }
            updateDownloadState();
        });

        topicSelect.addEventListener('change', updateDownloadState);

        testTypesContainer.addEventListener('change', function (e) {
            if (e.target && e.target.matches('input[name="test_type_ids[]"]')) {
                updateTestTypeHint();
            }
        });

        downloadBtn.addEventListener('click', function () {
            hideError();
            const subjectId = subjectSelect.value;
            const topicId = topicSelect.value;
            if (!subjectId || !topicId) {
                showError('Please select both a subject and a topic.');
                return;
            }

            const params = new URLSearchParams();
            params.append('subject_id', subjectId);
            params.append('topic_id', topicId);
            getSelectedTestTypeIds().forEach(id => params.append('test_type_ids[]', id));

            window.location.href = `${EXPORT_URL}?${params.toString()}`;
        });

        function loadTopics(subjectId) {
            topicSelect.innerHTML = '<option value="">Loading topics...</option>';
            topicSelect.disabled = true;

            fetch(`${TOPICS_URL}?subject_id=${encodeURIComponent(subjectId)}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(topics => {
                    if (!Array.isArray(topics) || topics.length === 0) {
                        topicSelect.innerHTML = '<option value="">No topics available for this subject</option>';
                        topicSelect.disabled = true;
                        return;
                    }
                    let html = '<option value="">Select Topic</option>';
                    topics.forEach(topic => {
                        html += `<option value="${topic.id}">${escapeHtml(topic.title)}</option>`;
                    });
                    topicSelect.innerHTML = html;
                    topicSelect.disabled = false;
                })
                .catch(err => {
                    console.error('Error loading topics:', err);
                    topicSelect.innerHTML = '<option value="">Error loading topics</option>';
                    topicSelect.disabled = true;
                    showError('Could not load topics. Please try again.');
                })
                .finally(updateDownloadState);
        }

        function loadTestTypes(subjectId) {
            testTypesContainer.innerHTML = `
                <div class="d-flex align-items-center text-muted">
                    <div class="spinner-border spinner-border-sm me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span>Loading test types...</span>
                </div>
            `;

            fetch(`${TEST_TYPES_URL}?subject_id=${encodeURIComponent(subjectId)}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(testTypes => {
                    if (!Array.isArray(testTypes) || testTypes.length === 0) {
                        testTypesContainer.innerHTML = '<p class="text-muted mb-0">No test types available for this subject.</p>';
                        updateTestTypeHint();
                        return;
                    }
                    let html = '<div class="row g-2">';
                    testTypes.forEach(type => {
                        const iconHtml = type.icon ? `<i class="${escapeHtml(type.icon)} me-1"></i>` : '';
                        html += `
                            <div class="col-md-4 col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           name="test_type_ids[]"
                                           value="${type.id}"
                                           id="export_test_type_${type.id}">
                                    <label class="form-check-label" for="export_test_type_${type.id}">
                                        ${iconHtml}${escapeHtml(type.name)}
                                    </label>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    testTypesContainer.innerHTML = html;
                    updateTestTypeHint();
                })
                .catch(err => {
                    console.error('Error loading test types:', err);
                    testTypesContainer.innerHTML = `
                        <p class="text-danger mb-0">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            Could not load test types. Please try again.
                        </p>
                    `;
                    updateTestTypeHint();
                });
        }

        function getSelectedTestTypeIds() {
            return Array.from(testTypesContainer.querySelectorAll('input[name="test_type_ids[]"]:checked'))
                .map(cb => cb.value);
        }

        function updateTestTypeHint() {
            const count = getSelectedTestTypeIds().length;
            testTypeHint.textContent = count > 0 ? `${count} selected` : '';
        }

        function updateDownloadState() {
            const ready = !!subjectSelect.value && !!topicSelect.value;
            downloadBtn.disabled = !ready;
            footerHint.textContent = ready
                ? 'Ready to download.'
                : 'Choose a subject and topic to enable download.';
        }

        function resetTopic() {
            topicSelect.innerHTML = '<option value="">Select a subject first</option>';
            topicSelect.disabled = true;
        }

        function resetTestTypes() {
            testTypesContainer.innerHTML = '<p class="text-muted mb-0">Select a subject to see available test types.</p>';
            updateTestTypeHint();
        }

        function resetForm() {
            subjectSelect.value = '';
            resetTopic();
            resetTestTypes();
            hideError();
            updateDownloadState();
        }

        function exportTemplateErrorTarget() {
            return errorBox && errorBox.querySelector ? (errorBox.querySelector('.flex-grow-1') || errorBox) : null;
        }

        function showError(msg) {
            const t = exportTemplateErrorTarget();
            if (t) {
                t.textContent = msg;
            }
            if (errorBox) {
                errorBox.classList.remove('d-none');
            }
        }

        function hideError() {
            const t = exportTemplateErrorTarget();
            if (t) {
                t.textContent = '';
            }
            if (errorBox) {
                errorBox.classList.add('d-none');
            }
        }

        function escapeHtml(value) {
            if (value === null || value === undefined) return '';
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }
    });
</script>
@endpush
