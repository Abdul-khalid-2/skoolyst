document.addEventListener('DOMContentLoaded', function () {
    // Registration Type Toggle
    const typeButtons = document.querySelectorAll('.type-btn');
    const userRegistration = document.getElementById('userRegistration');
    const schoolRegistration = document.getElementById('schoolRegistration');

    typeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const type = this.getAttribute('data-type');

            // Update active button
            typeButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Show appropriate form
            if (type === 'user') {
                userRegistration.style.display = 'block';
                schoolRegistration.classList.remove('active');
            } else {
                userRegistration.style.display = 'none';
                schoolRegistration.classList.add('active');
            }
        });
    });

    // Collected errors for the summary box — reset at the start of each validateForm run
    let validationErrors = [];

    function getFieldLabel(field) {
        const parent = field.closest('.form-group') || field.parentNode;
        const lbl = parent.querySelector('.form-label, label.form-check-label');
        return lbl ? lbl.textContent.replace(/\*/g, '').trim() : (field.name || 'Field');
    }

    // Inline error helpers — reuse the same `.input-error` div the server-side renders
    function setFieldError(field, message) {
        field.classList.add('is-invalid');
        field.style.borderColor = '#e53e3e';

        const parent = field.closest('.form-group') || field.parentNode;
        let err = parent.querySelector('.input-error.js-error');
        if (!err) {
            err = document.createElement('div');
            err.className = 'input-error js-error';
            parent.appendChild(err);
        }
        err.textContent = message;

        validationErrors.push({
            label: getFieldLabel(field),
            message,
            fieldName: field.name
        });
    }

    function clearFieldError(field) {
        field.classList.remove('is-invalid');
        field.style.borderColor = '';
        const parent = field.closest('.form-group') || field.parentNode;
        const err = parent.querySelector('.input-error.js-error');
        if (err) err.remove();
    }

    const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const PHONE_RE = /^[0-9+\-\s()]{7,20}$/;

    function validateField(field) {
        const val = (field.value || '').trim();
        const name = field.name;
        const type = field.type;

        if (field.hasAttribute('required') && !val) {
            setFieldError(field, 'This field is required.');
            return false;
        }
        if (!val) { clearFieldError(field); return true; } // optional & empty = ok

        if (type === 'email' && !EMAIL_RE.test(val)) {
            setFieldError(field, 'Please enter a valid email address.');
            return false;
        }
        if (name === 'school_contact' && !PHONE_RE.test(val)) {
            setFieldError(field, 'Please enter a valid phone number.');
            return false;
        }
        if (name === 'school_name' && (val.length < 3 || val.length > 100)) {
            setFieldError(field, 'School name must be 3–100 characters.');
            return false;
        }
        if (name === 'school_website') {
            try { new URL(val); } catch (_) {
                setFieldError(field, 'Please enter a valid URL (https://…).');
                return false;
            }
        }
        if (name === 'admin_password' && val.length < 8) {
            setFieldError(field, 'Password must be at least 8 characters.');
            return false;
        }
        if (name === 'admin_password_confirmation') {
            const pw = document.querySelector('input[name="admin_password"]');
            if (pw && val !== pw.value) {
                setFieldError(field, 'Passwords do not match.');
                return false;
            }
        }

        clearFieldError(field);
        return true;
    }

    // Validate the entire school registration form at once (single-step form
    // — every field is always visible, so there's nothing to page through).
    function validateForm() {
        validationErrors = [];
        let isValid = true;

        const fields = document.querySelectorAll('#schoolRegistration input, #schoolRegistration select, #schoolRegistration textarea');
        fields.forEach(f => {
            if (f.type === 'hidden' || f.disabled) return;
            if (!validateField(f)) isValid = false;
        });

        // Fee structure specifics
        const feeType = document.querySelector('input[name="fee_structure_type"]:checked');
        if (!feeType) {
            isValid = false;
        } else if (feeType.value === 'class_wise') {
            const rows = document.querySelectorAll('#fees-container .fees-row');
            if (rows.length === 0) isValid = false;
            rows.forEach(row => {
                const range  = row.querySelector('.class-range');
                const amount = row.querySelector('.fees-amount');
                if (range && !range.value.trim()) {
                    setFieldError(range, 'Class range is required.'); isValid = false;
                } else if (range) { clearFieldError(range); }
                if (amount) {
                    const v = amount.value.trim();
                    if (!v) { setFieldError(amount, 'Fee amount is required.'); isValid = false; }
                    else if (isNaN(v) || parseFloat(v) < 0) {
                        setFieldError(amount, 'Fee amount must be a non-negative number.'); isValid = false;
                    } else { clearFieldError(amount); }
                }
            });
        }

        const terms = document.getElementById('school_terms');
        if (terms && !terms.checked) {
            setFieldError(terms, 'You must accept the confirmation to continue.');
            isValid = false;
        } else if (terms) { clearFieldError(terms); }

        if (!isValid) {
            const firstErr = document.querySelector('#schoolRegistration .is-invalid');
            if (firstErr) {
                firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
                try { firstErr.focus({ preventScroll: true }); } catch (_) {}
            }
        }

        return { isValid, errors: validationErrors.slice() };
    }

    // ── Sticky error summary box (shared by both forms — each has its own
    // summary/list element ids so School and Parent/Student errors never mix) ──
    function showErrorSummary(errors, summaryId = 'schoolErrorSummary', listId = 'schoolErrorList') {
        const summary = document.getElementById(summaryId);
        const list    = document.getElementById(listId);
        if (!summary || !list || !errors || errors.length === 0) return;

        list.innerHTML = errors.map(e => {
            const lbl = e.label ? `<strong>${escapeHtml(e.label)}</strong> — ` : '';
            return `<li>${lbl}${escapeHtml(e.message)}</li>`;
        }).join('');

        // restart the shake animation
        summary.style.display = 'block';
        summary.style.animation = 'none';
        void summary.offsetWidth;
        summary.style.animation = '';
        summary.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function hideErrorSummary(summaryId = 'schoolErrorSummary', listId = 'schoolErrorList') {
        const summary = document.getElementById(summaryId);
        const list    = document.getElementById(listId);
        if (summary) summary.style.display = 'none';
        if (list)    list.innerHTML = '';
    }

    function escapeHtml(s) {
        return String(s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    // Expose for inline blade script (backend-error path)
    window.showSchoolErrorSummary = (errors) => showErrorSummary(errors, 'schoolErrorSummary', 'schoolErrorList');
    window.hideSchoolErrorSummary = () => hideErrorSummary('schoolErrorSummary', 'schoolErrorList');
    window.showUserErrorSummary = (errors) => showErrorSummary(errors, 'userErrorSummary', 'userErrorList');
    window.hideUserErrorSummary = () => hideErrorSummary('userErrorSummary', 'userErrorList');

    // Close buttons
    document.getElementById('closeErrorSummary')?.addEventListener('click', () => hideErrorSummary('schoolErrorSummary', 'schoolErrorList'));
    document.getElementById('closeUserErrorSummary')?.addEventListener('click', () => hideErrorSummary('userErrorSummary', 'userErrorList'));

    // Clear error as soon as the user starts correcting the field;
    // auto-hide the summary box once no invalid fields remain.
    function onFieldCorrected(field) {
        clearFieldError(field);
        if (!document.querySelector('#schoolRegistration .is-invalid')) {
            hideErrorSummary();
        }
    }
    document.querySelectorAll('#schoolRegistration input, #schoolRegistration select, #schoolRegistration textarea')
        .forEach(field => {
            field.addEventListener('input',  () => onFieldCorrected(field));
            field.addEventListener('change', () => onFieldCorrected(field));
        });

    // Final-submit guard — validate everything before letting the form POST,
    // and surface every error at once in the summary box.
    const schoolForm = document.querySelector('#schoolRegistration form');
    if (schoolForm) {
        schoolForm.addEventListener('submit', function (e) {
            const result = validateForm();
            if (!result.isValid) {
                e.preventDefault();
                showErrorSummary(result.errors);
                return false;
            }
            hideErrorSummary();
        });
    }
});
